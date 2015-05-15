<?php
/**
 * Created by PhpStorm.
 * User: Lwaxana
 * Date: 16/04/2015
 * Time: 14:35
 */

namespace SERCOM\AppBundle\Controller;



use SERCOM\AppBundle\Entity\Event;
use SERCOM\AppBundle\Form\Event2Type;
use SERCOM\AppBundle\Form\MemberEventType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use SERCOM\AppBundle\Entity\Member;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AgendaController extends Controller {

    public function indexAction(){
        $rep = $this->getDoctrine()->getRepository('SERCOMAppBundle:Event');
        $events = $rep->findBy(array('validate' => false) );
        $eventsc = $rep->findBy(array('validate' => true));
        $datenow = new \DateTime();
        return $this->render('SERCOMAppBundle:Agenda:index.html.twig', array('events' => $events , 'eventsc' => $eventsc, 'datenow' => $datenow));
    }

    public function eventvoirAction($id){
        $rep = $this->getDoctrine()->getRepository('SERCOMAppBundle:Event');
        $event = $rep->find($id);
        if ( !empty($event) ){
            $rep = $this->getDoctrine()->getRepository('SERCOMAppBundle:EventDateProposal');
            $dates = $rep->findByEvents($id, array('datehour' => 'ASC'));
            $rep = $this->getDoctrine()->getRepository('SERCOMAppBundle:Member');
            $members = $rep->getAllMembers($id);
            return $this->render( 'SERCOMAppBundle:Agenda:voirevent.html.twig', array('dates' => $dates, 'members' => $members, 'event' => $event));
        }
        else{
            throw new NotFoundHttpException();
        }

    }

    public function eventchoisirAction($id, Request $request){

        $rep = $this->getDoctrine()->getRepository('SERCOMAppBundle:Event');
        $event = $rep->find($id);

        if ( !empty($event)){
            $rep = $this->getDoctrine()->getRepository('SERCOMAppBundle:EventDateProposal');
            $dates = $rep->findByEvents($id, array('datehour' => 'ASC'));
            $person = $this->get('security.context')->getToken()->getUser();
            $member = $person->getMember();
            $rep = $this->getDoctrine()->getRepository('SERCOMAppBundle:Member');
            $members = $rep->getMembers($person = $this->get('security.context')->getToken()->getUser()->getPersonid(), $id);
            $form = $this->createForm(new MemberEventType($id), $member, array('attr'=> array('id'=>$id)))->add('save','submit');
            $form->handleRequest($request);
            if ( $request->isMethod('POST') ){
                if ( $form->isValid()){
                    $em = $this->getDoctrine()->getManager();
                    try{
                        $em->persist($member);
                        $em->flush();
                        $this->get('session')->getFlashBag()->add('succes', 'Sauvegarde des changements effectuée');
                    }
                    catch(\Exception $e){
                        $this->get('session')->getFlashBag()->add('error', 'Une erreur est survenue, modifications non sauvées');
                    }
                    finally{
                        $this->redirect($this->generateUrl('sercom_members_agenda_choisir', array('id' =>$id)));
                    }
                }

            }
            return $this->render('SERCOMAppBundle:Agenda:choisir.html.twig', array('event' => $event, 'dates' => $dates, 'form'=>$form->createView(), 'members' => $members));
        }
        else{
            throw new NotFoundHttpException();
        }
    }

    public function eventmodifierAction($id, Request $request){
        $rep = $this->getDoctrine()->getRepository('SERCOMAppBundle:Event');
        $event = $rep->find($id);
        if ( !empty($event)){
            $member = $this->get('security.context')->getToken()->getUser()->getMember();
            if ( $member == $event->getMember()){
                $form = $this->createForm(new Event2Type(), $event)->add('save','submit');
                $form->handleRequest($request);
                if ( $request->isMethod('POST')){
                    if ( $form->isValid()){
                        foreach($event->getDateProposals() as $d){
                            $d->setEvents($event);
                        }
                        $em = $this->getDoctrine()->getManager();
                        try{
                            $em->persist($event);
                            $em->flush();
                            $this->get('session')->getFlashBag()->add('succes', 'Evenement modifié');
                        }catch (\Exception $e){
                            $this->get('session')->getFlashBag()->add('error', 'Une erreur est survenue, modifications non sauvées');
                        }
                        finally{
                            return $this->redirect($this->generateUrl('sercom_members_agenda'));
                        }
                    }
                }
                return $this->render('SERCOMAppBundle:Agenda:modify.html.twig', array('form' => $form->createView(), 'event' => $event));
            }
            else{
                throw new AccessDeniedHttpException();
            }
        }
        else{
            throw new NotFoundHttpException();
        }
    }

    public function deleteEventAction($id){
        $rep = $this->getDoctrine()->getRepository('SERCOMAppBundle:Event');
        $event = $rep->find($id);
        if (!empty($event)){
            $person = $this->get('security.context')->getToken()->getUser();
            $member = $person->getMember();
            if ( $member == $event->getMember()){
                $em = $this->getDoctrine()->getManager();
                try{
                    $em->remove($event);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('succes', 'Evenement supprimé');
                }catch (\Exception $e){
                    $this->get('session')->getFlashBag()->add('error', 'Une erreur est survenue');
                }
                finally{
                    return $this->redirect($this->generateUrl('sercom_members_agenda'));
                }
            }
            else{
                throw new AccessDeniedHttpException();
            }
        }
        else{
            throw new NotFoundHttpException();
        }
    }

    public function validerEventAction($id, Request $request){
        $rep = $this->getDoctrine()->getRepository('SERCOMAppBundle:Event');
        $event = $rep->find($id);
        if ( !empty($event)){
            $person = $this->get('security.context')->getToken()->getUser();
            $member = $person->getMember();
            if ( $member == $event->getMember()){
                $rep = $this->getDoctrine()->getRepository('SERCOMAppBundle:EventDateProposal');
                $dates = $rep->findByEvents($id, array('datehour' => 'ASC'));
                $dates2 = $rep->getDates($id);
                $dates3 = array();
                foreach( $dates2 as $d){
                    array_push($dates3, $d['datehour']);
                }
                $defaultdata = array('message' => 'ici message');
                $form = $this->createFormBuilder($defaultdata)->add('datehour','choice', array('choices' => $dates3 ))->add('valid','checkbox', array('required' => false))->add('save','submit')->getForm();
                $rep = $this->getDoctrine()->getRepository('SERCOMAppBundle:Member');
                $members = $rep->getAllMembers($id);
                if ( $request->isMethod('POST')){
                    $form->handleRequest($request);
                    if ( $form->isValid()) {
                        try{
                            $data = $form->getData();
                            $d = $data['datehour'];
                            $v = $data['valid'];
                            $em = $this->getDoctrine()->getManager();
                            $event->setValidate($v);
                            $event->setDateHourEvent(new \DateTime($dates3[$d]));
                            $em->persist($event);
                            $em->flush();
                            $this->get('session')->getFlashBag()->add('succes', 'Evenement confirmé');

                        }
                        catch(\Exception $e){
                            $this->get('session')->getFlashBag()->add('error', 'Une erreur s\'est produite');
                        }
                        finally{
                            return $this->redirect($this->generateUrl('sercom_members_agenda'));
                        }
                    }
                }
                return $this->render( 'SERCOMAppBundle:Agenda:valider.html.twig', array('form' => $form->createView(), 'dates' => $dates, 'members' => $members, 'event' => $event));
            }
            else{
                throw new AccessDeniedHttpException();
            }
        }
        else{
            throw new NotFoundHttpException();
        }
    }

    public function addEventAction(Request $request){
        $event = new Event();
        $form = $this->createForm(new Event2Type(), $event)->add('save','submit');
        $form->handleRequest($request);
        if ( $request->isMethod('POST')){
            if ( $form->isValid()){
                $person = $this->get('security.context')->getToken()->getUser();
                $member = $person->getMember();
                $event->setMember($member);
                $event->setValidate(false);
                $event->setDateProposals($event->getDateProposals());
                try{
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($event);
                    $em->flush();
                    $event = new Event();
                    $form = $this->createForm(new Event2Type(), $event)->add('save','submit');
                    $this->get('session')->getFlashBag()->add('succes', 'Evenement ajouté');

                }catch(\Exception $e){
                    $this->get('session')->getFlashBag()->add('error', 'Une erreur s\'est produite');
                }
                finally{
                    return $this->redirect($this->generateUrl('sercom_members_agenda'));
                }
            }
        }
        return $this->render('@SERCOMApp/Agenda/addevent.html.twig', array('form' => $form->createView()));
    }

    public function calendarAction(){

            $em = $this->getDoctrine()->getManager();
            $datas = $em->getRepository('SERCOMAppBundle:Event')->getCal();
            $datas2 = array();

            foreach ($datas as $data) {
                $data = array( "title" => $data->getName(),'start' => $data->getDateHourEvent()->format('Y-m-d H:i'), "timezone"=> "Europe/Paris");
                array_push($datas2, $data);
            }
            return new JsonResponse($datas2);

    }


} 