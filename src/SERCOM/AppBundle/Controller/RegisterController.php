<?php

namespace SERCOM\AppBundle\Controller;


use SERCOM\AppBundle\Entity\Teacher;
use SERCOM\AppBundle\Entity\Member;
use SERCOM\AppBundle\Entity\Student;
use SERCOM\AppBundle\Form\PersonInscriptionType;
use SERCOM\AppBundle\Form\PersonPwdType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use SERCOM\AppBundle\Entity\Person;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Util\SecureRandom;
use Symfony\Component\Security\Core\Util\StringUtils;


class RegisterController extends Controller{

    public function registerAction(Request $request){
        $person = new Person();
        $form = $this->createForm(new PersonInscriptionType(), $person);
        $form->handleRequest($request);
        if ($form -> isValid() ){
            // TEST VALIDATION A FAIRE SI OK RECORD + MSG ELSE RETOUR FORM
            $person->setActivationcode($this->generateActivationCode());
            $person->setBan(false);
            $person->setEmailvalid(false);
            $person->setValidate(false);
            $person->setDateInscription(new \DateTime());
            $pwd = $this->get('security.encoder_factory')->getEncoder($person)->encodePassword($person->getPassword(), $person->getSalt());
            $person->setPassword($pwd);
            $em = $this->getDoctrine()->getManager();
            $em->persist($person);
            $em->flush();

            $message = \Swift_Message::newInstance()
                ->setSubject("Enregistrement sur le site SERCOM ASBL")
                ->setFrom('mf.sercom@gmail.com')
                ->setTo($person->getEmail())
                ->setBody($this->renderView('SERCOMAppBundle:Email:register.html.twig', array('person' => $person)));
            $this->get('swiftmailer.mailer.default')->send($message);
            $message = "L'enregistrement est terminé. Vous allez recevoir un mail dans quelques instants.
                            Afin de valider votre adresse email, veuillez cliquez dans sur le lien dans l'email.";
            return $this->render('SERCOMAppBundle:Home:registerdone.html.twig', array('message'=>$message));

        }
        return $this->render('SERCOMAppBundle:Home:register.html.twig', array('form' => $form->createView()));
    }

    /*
     * Activation du compte par email -- Verification email // via login et code activation
     */
    public function validateAction(Request $request){
        $login = $request->query->get('login');
        $code = $request->query->get('code');
        $rep = $this->getDoctrine()->getRepository('SERCOMAppBundle:Person');
        $person = $rep->findByUsername($login);
        if ( !empty($person)){
            $person = $person[0];
            if( StringUtils::equals($code, $person->getActivationcode() )){
                if ( ($person->getBan() == false ) && ($person->getValidate() == false ) && ($person->getEmailvalid() == false) ){
                    $person->setEmailvalid(true);
                    $person->setActivationcode(NULL);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($person);
                    $em->flush();
                    $message = "Adresse mail validée. Veuillez chosir le type d'accès :";
                    return $this->render('SERCOMAppBundle:Home:registercheck.html.twig', array('message'=>$message));
                }
                else {
                    $message = "Problème lors de l'activation  Contactez l'administrateur du site.";
                    return $this->render('SERCOMAppBundle:Home:registerdone.html.twig', array('message' => $message));
                }
            }
            $message = "Problème lors de l'activation  Contactez l'administrateur du site.";
            return $this->render('SERCOMAppBundle:Home:registerdone.html.twig', array('message' => $message));
        }
        $message = "Problème lors de l'activation  Contactez l'administrateur du site.";
        return $this->render('SERCOMAppBundle:Home:registerdone.html.twig', array('message' => $message));
    }

    /**
     * @param $login
     * @return JsonResponse
     * Check login AJAX enregistrement
     */
    public function testloginAction($login){
        $rep = $this->getDoctrine()->getManager()->getRepository('SERCOMAppBundle:Person');
        $data = $rep->findByUsername($login);
        $response = new JsonResponse();
        if ( empty($data)){
            $response->setData(array('login'=>'false'));
        }
        else{
            $response->setData(array('login'=>'true'));
        }
        return $response;
    }

    /**
     * @param $email
     * @return JsonResponse
     * check email AJAX enregistrement
     */
    public function testemailAction($email){
        $rep = $this->getDoctrine()->getManager()->getRepository('SERCOMAppBundle:Person');
        $data = $rep->findByEmail($email);
        $response = new JsonResponse();
        if ( empty($data)){

            $response->setData(array('email'=>'false'));
        }
        else{
            $response->setData(array('email'=>'true'));
        }
        return $response;
    }

    private function generateActivationCode(){
        $generator = new SecureRandom();
        $random = base64_encode($generator->nextBytes(256));
        return hash('sha512', $random);
    }

    public function accessAction(Request $request){

        $uri =  $request->server->get('HTTP_REFERER');
        $v = explode("login=",$uri);
        if ( count($v) >= 2 ){
            $login = strstr( $v[1], "&", TRUE );
            if ( $login == FALSE){
                // ENVOI ERREUR
            }
            else{
                $person = new Person();
                $rep =  $rep = $this->getDoctrine()->getManager()->getRepository('SERCOMAppBundle:Person');
                $person = $rep->findOneByUsername($login);
                //$person = $rep->find($person->getPersonId());

                if ( !empty($person)){
                    $membre = new Member();
                    $student = new Student();
                    $teacher = new Teacher();
                    $em = $this->getDoctrine()->getManager();

                    if ( $request->request->get('member') == 'member'){
                        $membre->setPerson($person);
                        $membre->setActif(false);
                        $em->persist($membre);
                        $em->flush();

                    }
                    if ( $request->request->get('teacher') == 'teacher'){
                        $teacher->setPerson($person);
                        $teacher->setActif(false);
                        $em->persist($teacher);
                        $em->flush();
                    }
                    if ( $request->request->get('student') == 'student'){
                        $student->setPerson($person);
                        $student->setActif(false);
                        $em->persist($student);
                        $em->flush();
                    }
                    $message = "Vos choix ont bien été pris en compte. L'administrateur du site activera votre compte et donnera les autorisations necessaires << LIEN ACCUEIL >>";
                    return $this->render('SERCOMAppBundle:Home:registerdone.html.twig', array('message'=>$message));


                }

            }
        }
        else{
            return new Response('NOK');
        }
    }

    public function resetPwdAction(Request $request){
        $login = $request->query->get('login');
        $code = $request->query->get('code');
        $rep = $this->getDoctrine()->getRepository('SERCOMAppBundle:Person');
        $person = $rep->findOneByUsername($login);
        if ( !empty($person)){
            if( StringUtils::equals($code, $person->getActivationcode() )){
                if ( !$person->getBan() && $person->getValidate() && $person->getEmailValid() ){
                    $person->setActivationcode($this->generateActivationCode());
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($person);
                    $em->flush();
                    $route = $this->generateUrl('sercom_register_newpwd', array('id' => $person->getPersonid(), 'activ' => $person->getActivationcode()));
                    return $this->redirect($route);
                }
                else{
                    throw new AccessDeniedException();
                }
            }
            else{
                throw new NotFoundHttpException();
            }
        }
        else{
            throw new NotFoundHttpException();
        }
    }




} 