<?php

namespace SERCOM\AppBundle\Controller;


use SERCOM\AppBundle\Entity\Address;

use SERCOM\AppBundle\Entity\Member;

use SERCOM\AppBundle\Entity\Person;
use SERCOM\AppBundle\Entity\PrivateMessage;
use SERCOM\AppBundle\Entity\ReceiveMessage;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;





class TestController extends Controller{

    public function indexAction(){
        $person = new Person();
        $person->setFirstname("Philippe");
        $person->setLastname("Jankowski");
        $person->setEmail("phj@controlz.be");
        $person->setTelgsm("0478481099");
        $person->setBan(false);
        $person->setActivationcode("123456");
        $person->setEmailvalid(true);
        $person->setValidate(true);
        $person->setPassword("123456");
        $person->setPicture("aaa");


        $adress = new Address();
        $adress->setStreet("Rue de la moule");
        $adress->setNumber("69");
        $adress->setCity("Wasmes");
        $adress->setPostcode("7340");

        $countryrep = $this->getDoctrine()->getManager()->getRepository('SERCOMAppBundle:Country');
        $country = $countryrep->find(22);

        $adress->setCountry($country);
        $person->setAddress($adress);

        $sgrep = $this->getDoctrine()->getManager()->getRepository('SERCOMAppBundle:SiteGroup');
        $sg = $sgrep->findOneBy(array('name'=>'administrateurs'));
        $member = new Member();
        $member->setSitegroup($sg);
        $member->setPerson($person);
        $member->setActif(true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($person);
        $em->flush();
        $em->persist($member);
        $em->flush();


        return new Response("ok");

    }

    public function index2Action(){
        $countryrep = $this->getDoctrine()->getManager()->getRepository('SERCOMAppBundle:Country');
        $country = $countryrep->find(22);
        return new Response(var_dump($country));
    }

    public function index3Action(){
        $rep = $this->getDoctrine()->getManager()->getRepository('SERCOMAppBundle:Person');
        $person = $rep->find(1);
        return $this->render('SERCOMAppBundle:Default:index.html.twig', array('person' => $person));

    }

    public function index4Action(){
        var_dump(__DIR__.'/../../../web/bundles/AppBundle');
        if (file_exists(__DIR__.'/../../../../web/bundles/AppBundle')){
            return new Response('exists');
        }else{
            return new Response('not exists');
        }

    }

    public function index5Action(){
       // $rep = $this->getDoctrine()->getManager()->getRepository('SERCOMAppBundle:Person');
       // $person = $rep->findBy(array('firstname' => 'Philippe', 'lastname' => 'Jankowski', 'member'=>true));

        //var_dump($person);

        //$role = $this->getUser()->getRoles();
        //var_dump($role);
        /*
        $em = $this->getDoctrine()->getManager();
        $souscats = $em->getRepository('SERCOMAppBundle:AsblDocumentSousCat')->findByCategory(1);
        $d = new JsonResponse($souscats);
        var_dump($d);*/

        $title = "Coiffure de poney 1";
        $time = "2015-05-15 21:00";
        $d = new \DateTime($time);

        $em = $this->getDoctrine()->getManager();

        $rep = $em->getRepository('SERCOMAppBundle:CoursePlanning');
        $data = $rep->findEvent($d, $title);
        var_dump($data);

        $datas2 = array( 'name' => $data->getClasse()->getName() ,
            'place' => $data->getCourseplace()->getName(),
            'subject' => $data->getClasse()->getSuject()->getName() ,
            'prof' => $data->getClasse()->getTeacher()->getPerson()->getLastname()." ".$data->getClasse()->getTeacher()->getPerson()->getFirstname() );


        var_dump($datas2);


    }
} 