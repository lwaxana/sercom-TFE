<?php
/**
 * Created by PhpStorm.
 * User: Lwaxana
 * Date: 11/05/2015
 * Time: 13:33
 */

namespace SERCOM\AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use SERCOM\AppBundle\Form\MemberSearchType;

class AdminProfController extends Controller {

    public function indexAction($page, Request $request){
        $rep = $this->getDoctrine()->getManager()->getRepository('SERCOMAppBundle:Teacher');
        $max_per_page = 9;
        $persons = $rep->findTeachers($page, $max_per_page);
        $count = $rep->countTeachers();

        $pagination = array(
            'page' => $page,
            'route' => 'sercom_admin_teachers',
            'pages_count' => ceil($count / $max_per_page),
            'route_params' => array('page' => $page)
        );

        $form = $this->createForm(new MemberSearchType());
        $form->handleRequest($request);
        if ( $form->isValid()){
            $data = $form->getData();
            $search = $data{'search'};
            $rep = $this->getDoctrine()->getRepository('SERCOMAppBundle:Teacher');
            $persons = $rep->search($search);
            $form = $this->createForm(new MemberSearchType());
            return $this->render('SERCOMAppBundle:AdminProf:searchresult.html.twig', array('persons' => $persons, 'form' => $form->createView()));
        }
        return $this->render('SERCOMAppBundle:AdminProf:index.html.twig', array('persons' => $persons, 'pagination' => $pagination, 'form' => $form->createView()));
    }





} 