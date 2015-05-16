<?php
/**
 * Created by PhpStorm.
 * User: Lwaxana
 * Date: 29/03/2015
 * Time: 15:59
 */

namespace SERCOM\AppBundle\Controller;


use SERCOM\AppBundle\Entity\Person;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Util\SecureRandom;


class SecurityController extends Controller{

    public function loginAction(Request $request)
    {
        // Si le visiteur est déjà identifié, on le redirige vers l'accueil
        if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('sercom_members'));
        }

        $session = $request->getSession();

        // On vérifie s'il y a des erreurs d'une précédente soumission du formulaire
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        return $this->render('SERCOMAppBundle:Security:login.html.twig', array(
            // Valeur du précédent nom d'utilisateur entré par l'internaute
            'last_username' => $session->get(SecurityContext::LAST_USERNAME),
            'error'         => $error,
        ));
    }

    public function lostpasswordAction(Request $request){
        $data = array('Message' => 'message');
        $form = $this->createFormBuilder($data)->add('email','email')->add('save','submit')->getForm();
        $form->handleRequest($request);
        if ( $form->isValid()){
            $rep = $this->getDoctrine()->getRepository('SERCOMAppBundle:Person');
            $p = $rep->findOneBy(array('email' => $form->get('email')->getData()));
            if ( !empty($p) ){
                if ( !$p->getBan() && $p->getValidate() && $p->getEmailvalid() ){
                    $em= $this->getDoctrine()->getManager();
                    $p->setActivationcode($this->generateActivationCode());
                    $em->persist($p);
                    $em->flush();
                    $message = \Swift_Message::newInstance()
                        ->setSubject("Reinitialisation de votre mot de passe")
                        ->setFrom('mf.sercom@gmail.com')
                        ->setTo($p->getEmail())
                        ->setBody($this->renderView('SERCOMAppBundle:Email:resetpwd.html.twig', array('person' => $p)));
                    $this->get('swiftmailer.mailer.default')->send($message);
                    return $this->render('@SERCOMApp/Security/passwordreste.html.twig');
                }
                else{
                    $this->get('session')->getFlashBag()->add('error', 'Compte non valide');
                    return $this->render('@SERCOMApp/Security/passwordlost.html.twig', array('form' => $form->createView()));
                }
            }
            else{
                $form = $this->createFormBuilder($data)->add('email','email')->add('save','submit')->getForm();
                $this->get('session')->getFlashBag()->add('error', 'Cette adresse mail n\'existe pas ');
                return $this->render('@SERCOMApp/Security/passwordlost.html.twig', array('form' => $form->createView()));
            }
        }
        return $this->render('@SERCOMApp/Security/passwordlost.html.twig', array('form' => $form->createView()));
    }

    private function generateActivationCode(){
        $generator = new SecureRandom();
        $random = base64_encode($generator->nextBytes(256));
        return hash('sha512', $random);
    }


} 