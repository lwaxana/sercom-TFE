<?php
/**
 * Created by PhpStorm.
 * User: Lwaxana
 * Date: 30/04/2015
 * Time: 11:16
 */

namespace SERCOM\AppBundle\Controller;


use SERCOM\AppBundle\Entity\ReceiveMessage;
use SERCOM\AppBundle\Entity\PrivateMessage;
use SERCOM\AppBundle\Form\PrivateMessage2Type;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use SERCOM\AppBundle\Form\PrivateMessage3Type;
use Symfony\Component\HttpFoundation\Response;

class MailController extends Controller{

    public function mailInboxAction(){
        $person = $this->get('security.context')->getToken()->getUser();
        $rep = $this->getDoctrine()->getManager()->getRepository('SERCOMAppBundle:ReceiveMessage');
        $msg_in_box = $rep->getMessages($person->getPersonId());
        return $this->render('@SERCOMApp/Mail/inbox.html.twig', array('msg' => $msg_in_box));
    }

    public function mailOutboxAction(){
        $person = $this->get('security.context')->getToken()->getUser();
        $rep = $this->getDoctrine()->getManager()->getRepository('SERCOMAppBundle:PrivateMessage');
        $msg_in_box = $rep->getMessages($person->getPersonId());
        return $this->render('@SERCOMApp/Mail/outbox.html.twig', array('msg' => $msg_in_box));
    }

    public function newmailAction(Request $request){
        $p = $person = $this->get('security.context')->getToken()->getUser();
        $msg = new PrivateMessage();
        $form = $this->createForm( new PrivateMessage2Type(), $msg, array('attr'=> array('idp'=> $p->getPersonid())));

        $form->handleRequest($request);
        if ( $form->isValid()){

            $em = $this->getDoctrine()->getManager();
            $persons = $form->get('persons')->getData();
            $msg->setMessageDelete(false);
            $msg->setSender($p->getMember());
            $msg->setDateMessage(new \DateTime());
            $msg->setMessagecontent(str_replace("&nbsp;", "<br/>", $msg->getMessagecontent()));



            if ( !(empty($msg->getAttachements())  )){
                $path = $this->get('kernel')->getRootDir().'/../src/attachements/';
                foreach ($msg->getAttachements() as $attach){
                    $file = $attach->getFile();
                    $new_name = sha1(uniqid(mt_rand(), true)).'.'.$file->guessExtension();
                    $file->move($path, $new_name);
                    $attach->setPrivatemessage($msg);
                    $attach->setName($file->getClientOriginalName());
                    $attach->setUrl($path ."/". $new_name);

                }
            }

            $em->persist($msg);
            $em->flush();

            foreach($persons as $per){
                $receive = new ReceiveMessage();
                $receive->setPrivatemessages($msg);
                $receive->setMember($per->getMember());
                $receive->setReadMessage(false);
                $receive->setMessageDelete(false);
                $em->persist($receive);
                $em->flush();

            }
            if ( count($persons) > 1){
                $this->get('session')->getFlashBag()->add('succes', 'Messages envoyés');
            }
            else{
                $this->get('session')->getFlashBag()->add('succes', 'Message envoyé');
            }

            return $this->redirect($this->generateUrl('sercom_member_mail_new_mail'));
        }
        return $this->render('@SERCOMApp/Mail/newmail.html.twig', array('form' => $form->createView()));
    }

    // delete inbox mail
    public function loschenmailAction($id){
        $person = $this->get('security.context')->getToken()->getUser();
        $rep = $this->getDoctrine()->getManager()->getRepository('SERCOMAppBundle:ReceiveMessage');
        $msg = $rep->find($id);
        if ( !empty($msg) ){
            if ( $person->getMember() == $msg->getMember()){
                $em = $this->getDoctrine()->getManager();
                $msg->setMessagedelete(true);
                $em->persist($msg);
                $em->flush();
                $m2 = $msg->getPrivatemessages();
                $this->deleteDeletedMessage($m2);
                $this->get('session')->getFlashBag()->add('succes', 'Message supprimé');
                return $this->redirect($this->generateUrl('sercom_member_mail_index'));
            }
            else{
                throw new AccessDeniedException();
            }
        }
        else{
            throw new NotFoundHttpException();
        }
    }

    // see inbox mail
    public function sehenmailAction($id){
        $person = $this->get('security.context')->getToken()->getUser();
        $rep = $this->getDoctrine()->getManager()->getRepository('SERCOMAppBundle:ReceiveMessage');
        $msg = $rep->find($id);
        if ( !empty($msg)){
            if ( $msg->getMember() == $person->getMember()){
                $em = $this->getDoctrine()->getManager();
                $msg->setReadMessage(true);
                $em->persist($msg);
                $em->flush();
                return $this->render('@SERCOMApp/Mail/inboxvoir.html.twig', array('msg' => $msg));
            }
            else{
                throw new AccessDeniedException();
            }
        }
        else{
            throw new NotFoundHttpException();
        }
    }

    // see outbox mail
    public function sehengeschiktmailAction($id, $member){
        $person = $this->get('security.context')->getToken()->getUser();
        $rep = $this->getDoctrine()->getManager()->getRepository('SERCOMAppBundle:ReceiveMessage');
        $msg = $rep->getMessage($id,$member);
        $msg = $msg[0];
        if ( !empty($msg)){
            if ( $msg->getPrivatemessages()->getSender() == $person->getMember()){
                return $this->render('@SERCOMApp/Mail/outboxvoir.twig', array('msg' => $msg));
            }
            else{
                throw new AccessDeniedException();
            }
        }
        else{
            throw new NotFoundHttpException();
        }
    }

    //delete outbox mail
    public function loschengeschiktmailAction($id){
        $person = $this->get('security.context')->getToken()->getUser();
        $rep = $this->getDoctrine()->getManager()->getRepository('SERCOMAppBundle:PrivateMessage');
        $msg = $rep->find($id);
        if ( $person->getMember() == $msg->getSender()){
            if ( !empty($msg)){
                $msg->setMessageDelete(true);
                $em = $this->getDoctrine()->getManager();
                $em->persist($msg);
                $em->flush();
                $this->deleteDeletedMessage($msg);
                $this->get('session')->getFlashBag()->add('succes', 'Message supprimé');
                return $this->redirect($this->generateUrl('sercom_member_mail_receive'));
            }
            else{
                throw new NotFoundHttpException();
            }
        }
        else{
            throw new AccessDeniedException();
        }
    }

    private function deleteDeletedMessage(PrivateMessage $message){
        $all_receive_messages = $message->getReceivers();
        $rep = $this->getDoctrine()->getManager()->getRepository('SERCOMAppBundle:ReceiveMessage');
        $message_deleted = $rep->getDeletedMessage($message->getMpId());
        $em = $this->getDoctrine()->getManager();
        if ( count($all_receive_messages) == count($message_deleted)){
            if ( $message->getMessageDelete()){
                foreach ( $all_receive_messages as $m){
                    $em->remove($m);
                    $em->flush();
                }
                if ( !empty($message->getAttachements()) ){
                    foreach($message->getAttachements() as $attach){
                        unlink(realpath($attach->getUrl()));
                    }
                }
                $em->remove($message);
                $em->flush();
            }
        }
    }

    //repondre mail
    public function antwoordmailAction($id, Request $request){
        $p = $this->get('security.context')->getToken()->getUser();
        $rep = $this->getDoctrine()->getManager()->getRepository('SERCOMAppBundle:ReceiveMessage');
        $m = $rep->find($id);
        if ( !empty($m)){
            if ( $m->getMember() == $p->getMember()){
                $message = $m->getPrivatemessages();
                $msg = new PrivateMessage();
                $msg->setSubject("Re: ".$message->getSubject());

                $reply_to = "<br/><br/><cite>Le ". $message->getDateMessage()->format('d-m-Y H:i') ." ".$message->getSender()->getPerson()->getLastname().
                    " ".$message->getSender()->getPerson()->getFirstname()." a écrit :".$message->getMessagecontent()."</cite>";
                $msg->setMessagecontent($reply_to);
                $form = $this->createForm( new PrivateMessage3Type(), $msg, array('attr'=> array('idp'=> $message->getSender()->getPerson()->getPersonId())));
                $form->handleRequest($request);
                if ( $form->isValid()){

                    $msg->setDateMessage(new \DateTime());
                    $msg->setMessageDelete(false);
                    $msg->setSender($p->getMember());
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($msg);
                    $em->flush();

                    $receive = new ReceiveMessage();
                    $receiver = $form->get('persons')->getData();
                    $receive->setPrivatemessages($msg);
                    $receive->setMember($receiver->getMember());
                    $receive->setReadMessage(false);
                    $receive->setMessageDelete(false);
                    $em->persist($receive);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('succes', 'Messages envoyés');
                    return $this->redirect($this->generateUrl('sercom_member_mail_new_mail'));
                }
                return $this->render('@SERCOMApp/Mail/antwoordmail.html.twig', array('form' => $form->createView()));
            }
            else{
                throw new AccessDeniedException();
            }
        }
        else{
            throw new NotFoundHttpException();
        }

    }

    public function fileInboxAction($id){
        $rep = $this->getDoctrine()->getManager()->getRepository('SERCOMAppBundle:Attachement');
        $doc = $rep->find($id);
        if ( !empty($doc)){
            $p = $this->get('security.context')->getToken()->getUser();
            $doc2 = $rep->isInboxFileGranted($id, $p->getPersonId());
            if ( !(empty($doc2)) && $doc2[0] == $doc ){
                // http://stackoverflow.com/questions/9968457/serve-a-download-of-an-uploaded-file-in-symfony2
                $headers = array(
                    'Content-Type' => 'text/plain',
                    'Content-Disposition' => 'attachment; filename="'.$doc->getName().'"' );
            return new Response(file_get_contents($doc->getUrl()), 200, $headers);
            }
            else{
                throw new AccessDeniedException();
            }
        }
        else{
            throw new NotFoundHttpException();
        }
    }

    public function fileOutboxAction($id){
        $rep = $this->getDoctrine()->getManager()->getRepository('SERCOMAppBundle:Attachement');
        $doc = $rep->find($id);
        if ( !empty($doc)){
            $p = $this->get('security.context')->getToken()->getUser();
            $doc2 = $rep->isOutboxFileGranted($id, $p->getPersonId());
            if ( !(empty($doc2)) && $doc2[0] == $doc ){
                // http://stackoverflow.com/questions/9968457/serve-a-download-of-an-uploaded-file-in-symfony2
                $headers = array(
                    'Content-Type' => 'text/plain',
                    'Content-Disposition' => 'attachment; filename="'.$doc->getName().'"' );
                return new Response(file_get_contents($doc->getUrl()), 200, $headers);
            }
            else{
                throw new AccessDeniedException();
            }
        }
        else{
            throw new NotFoundHttpException();
        }
    }

} 