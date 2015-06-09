<?php

namespace SERCOM\AppBundle\Controller;

use SERCOM\AppBundle\agenda\Agenda;
use SERCOM\AppBundle\Entity\contact\Contact;
use SERCOM\AppBundle\Entity\SiteArticle;
use SERCOM\AppBundle\Form\ContactType;
use SERCOM\AppBundle\Form\PersonPwdType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SERCOM\AppBundle\Entity\Person;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Util\StringUtils;

class HomeController extends Controller{

    public function indexAction(){
        $rep = $this->getDoctrine()->getManager()->getRepository('SERCOMAppBundle:SiteArticle');
        $articles = $rep->getLastArticles();
        $rep = $this->getDoctrine()->getManager()->getRepository('SERCOMAppBundle:CoursePlanning');
        $courses = $rep->getLastCourses();
        $rep = $this->getDoctrine()->getManager()->getRepository('SERCOMAppBundle:Event');
        $events = $rep->getAgenda();
        $agenda = array();
        foreach( $courses as $course){
            $ag = new Agenda();
            $ag->setDatehour($course->getDatecours());
            $ag->setTitle($course->getClasse()->getName());
            array_push($agenda, $ag);
        }
        foreach( $events as $event){
            $ag = new Agenda();
            $ag->setDatehour($event->getDatehourevent());
            $ag->setTitle($event->getName());
            array_push($agenda, $ag);
        }

        usort($agenda, array("SERCOM\AppBundle\agenda\Agenda" ,"cmp"));
        if ( count($agenda) > 2){
            $agenda = array_slice($agenda, 0, 2);
        }


        return $this->render('SERCOMAppBundle:Home:index.html.twig', array('articles' => $articles, 'agenda' => $agenda));
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
        $rep = $this->getDoctrine()->getManager()->getRepository('SERCOMAppBundle:Member');
        $president = $rep->getByFonction('Président');
        $vp = $rep->getByFonction("Vice-Président");
        $anim  = $rep->getByFonction('Animateur');
        $sec = $rep->getByFonction('Secrétaire');
        $trez = $rep->getByFonction('Trésorier');
        if ( !empty($president)){
            $president = $president[0];
        }
        if ( !empty($vp)){
            $vp = $vp[0];
        }
        if ( !empty($anim)){
            $anim = $anim[0];
        }
        if ( !empty($sec)){
            $sec = $sec[0];
        }
        if ( !empty($trez)){
            $trez = $trez[0];
        }
        return $this->render('@SERCOMApp/Home/qui-sommes-nous/equipe.html.twig', array('prez' => $president, 'vp' => $vp, 'anim' => $anim, 'sec' => $sec, 'trez' => $trez ) );
    }

    public function fleAction(){
        $rep = $this->getDoctrine()->getManager()->getRepository('SERCOMAppBundle:SiteArticle');
        $articles = $rep->getBySection("FLE");
        return $this->render('@SERCOMApp/Home/activite/fle.html.twig', array('articles' => $articles));
    }

    public function citoyenneteAction(){
        $rep = $this->getDoctrine()->getManager()->getRepository('SERCOMAppBundle:SiteArticle');
        $articles = $rep->getBySection("Citoyenneté");
        return $this->render('@SERCOMApp/Home/activite/ciotyennete.html.twig', array('articles' => $articles));
    }

    public function remediationsAction(){
        $rep = $this->getDoctrine()->getManager()->getRepository('SERCOMAppBundle:SiteArticle');
        $articles = $rep->getBySection("Remédiations");
        return $this->render('@SERCOMApp/Home/activite/remediation.html.twig', array('articles' => $articles));
    }

    public function codevAction(){
        $rep = $this->getDoctrine()->getManager()->getRepository('SERCOMAppBundle:SiteArticle');
        $articles = $rep->getBySection("Co-Développement");
        return $this->render('@SERCOMApp/Home/activite/codev.html.twig', array('articles' => $articles));
    }

    public function dialoguesAction(){
        $rep = $this->getDoctrine()->getManager()->getRepository('SERCOMAppBundle:SiteArticle');
        $articles = $rep->getBySection("Dialogues Inter-culturels");
        return $this->render('@SERCOMApp/Home/activite/dialogues.html.twig', array('articles' => $articles));
    }

    public function valorisationsAction(){
        $rep = $this->getDoctrine()->getManager()->getRepository('SERCOMAppBundle:SiteArticle');
        $articles = $rep->getBySection("Valorisatiobs");
        return $this->render('@SERCOMApp/Home/activite/valorisations.html.twig' ,array('articles' => $articles));
    }

    public function articleAction(SiteArticle $article){
        $rep = $this->getDoctrine()->getRepository('SERCOMAppBundle:ArticleDocument');
        $doc = $rep->findOneBy(array('article' => $article, 'picture' => false));
        $docs = $rep->findBy((array('article' => $article, 'picture' => true)));
        $text = file_get_contents(realpath($doc->getUrl()));
        return $this->render('@SERCOMApp/Home/activite/article.html.twig', array('text' => $text, 'docs' => $docs, 'article' => $article));
    }

    public function partenairesAction(){
        return $this->render('@SERCOMApp/Home/partenaires/partenaire.html.twig');
    }

    public function contactAction(Request $request){
        $person = new Contact();
        $form = $this->createForm(new ContactType(), $person);
        $form->handleRequest($request);
        if ( $form->isValid()){
            $message = \Swift_Message::newInstance()
                ->setSubject("Demande CONTACT SERCOM")
                ->setContentType('text/html')
                ->setFrom('mf.sercom@gmail.com')
                ->setTo("jankowski.philippe@gmail.com")
                ->setBody($this->renderView('SERCOMAppBundle:Email:contact.html.twig', array('contact' => $person)));
            $this->get('swiftmailer.mailer.default')->send($message);
            return $this->render('@SERCOMApp/Home/contact/contactdone.html.twig');
        }
        return $this->render('@SERCOMApp/Home/contact/contact.html.twig', array('form' => $form->createView()));
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

    public function cmp($a, $b){
        return ($a->getDatehour() < $b->getDatehour()) ? -1 : 1;
    }

    public function faqAction(){
        return $this->render('@SERCOMApp/Home/footer/faq.html.twig');
    }

    public function conditionsAction(){
        return $this->render('@SERCOMApp/Home/footer/conditions.html.twig');
    }

    public function cookieAction(){
        return $this->render('@SERCOMApp/Home/footer/cookie.html.twig');
    }

}