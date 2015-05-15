<?php

namespace SERCOM\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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



}