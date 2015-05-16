<?php

namespace SERCOM\AppBundle\Controller;

use SERCOM\AppBundle\Form\PersonPwdType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SERCOM\AppBundle\Entity\Person;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Util\StringUtils;

class HomeController extends Controller{

    public function indexAction(){
        return $this->render('SERCOMAppBundle:Home:index.html.twig');
    }

    public function loginAction(){
        return $this->render('SERCOMAppBundle:Home:login.html.twig');
    }

    public function projetAction(){
        return $this->render('@SERCOMApp/Home/qui-sommes-nous/projet.html.twig');
    }

    public function motpresidentAction(){
        return $this->render('@SERCOMApp/Home/qui-sommes-nous/motpresident.html.twig');
    }

    public function historiqueAction(){
        return $this->render('@SERCOMApp/Home/qui-sommes-nous/historique.html.twig');
    }

    public function equipeAction(){
        return $this->render('@SERCOMApp/Home/qui-sommes-nous/equipe.html.twig');
    }

    public function fleAction(){
        return $this->render('@SERCOMApp/Home/activite/fle.html.twig');
    }

    public function citoyenneteAction(){
        return $this->render('@SERCOMApp/Home/activite/ciotyennete.html.twig');
    }

    public function remediationsAction(){
        return $this->render('@SERCOMApp/Home/activite/remediation.html.twig');
    }

    public function codevAction(){
        return $this->render('@SERCOMApp/Home/activite/codev.html.twig');
    }

    public function dialoguesAction(){
        return $this->render('@SERCOMApp/Home/activite/dialogues.html.twig');
    }

    public function valorisationsAction(){
        return $this->render('@SERCOMApp/Home/activite/valorisations.html.twig');
    }

    public function partenairesAction(){
        return $this->render('@SERCOMApp/Home/partenaires/partenaire.html.twig');
    }

    public function contactAction(){
        return $this->render('@SERCOMApp/Home/contact/contact.html.twig');
    }

    public function newpwdAction(Person $person, $activ, Request $request){

        if ( !empty($person) && StringUtils::equals($activ, $person->getActivationcode())){
            if ( !$person->getBan() && $person->getEmailvalid() && $person->getValidate() && $person->getActivationcode() != NULL){
                $form = $this->createForm(new PersonPwdType(), $person);
                $form->handleRequest($request);
                if ( $form->isValid()){
                    try{
                        $em = $this->getDoctrine()->getManager();
                        $pwd = $this->get('security.encoder_factory')->getEncoder($person)->encodePassword($person->getPassword(), $person->getSalt());
                        $person->setPassword($pwd);
                        $person->setActivationcode(NULL);
                        $em->persist($person);
                        $em->flush();
                        $this->get('session')->getFlashBag()->add('succes', 'Enregistrement effectué');
                    }
                    catch(\Exception $e){
                        $this->get('session')->getFlashBag()->add('error', 'Une erreur est survenue');
                    }
                    finally{
                        return $this->render('@SERCOMApp/Home/changepwddone.html.twig');
                    }
                }
                return $this->render('@SERCOMApp/Home/newpwd.html.twig', array('form' => $form->createView()));
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