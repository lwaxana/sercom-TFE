<?php
/**
 * Created by PhpStorm.
 * User: Lwaxana
 * Date: 11/05/2015
 * Time: 13:33
 */

namespace SERCOM\AppBundle\Controller;


use SERCOM\AppBundle\Form\StudentClassType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SERCOM\AppBundle\Form\MemberSearchType;
use Symfony\Component\HttpFoundation\Request;
use SERCOM\AppBundle\Entity\Student;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AdminEtudiantController extends Controller{

    public function indexAction($page, Request $request){
        $person = $this->get('security.context')->getToken()->getUser();
        if ( $person->isGranted('ROLE_PRESIDENT') or $person->isGranted('ADMIN_STUDENTS')){
            $rep = $this->getDoctrine()->getManager()->getRepository('SERCOMAppBundle:Student');
            $max_per_page = 9;
            $persons = $rep->findStudents($page, $max_per_page);
            $count = $rep->countStudents();

            $pagination = array(
                'page' => $page,
                'route' => 'sercom_admin_students',
                'pages_count' => ceil($count / $max_per_page),
                'route_params' => array('page' => $page)
            );

            $form = $this->createForm(new MemberSearchType());
            $form->handleRequest($request);
            if ( $form->isValid()){
                $data = $form->getData();
                $search = $data{'search'};
                $persons = null;
                $rep = $this->getDoctrine()->getRepository('SERCOMAppBundle:Student');
                $persons = $rep->search($search);
                $form = $this->createForm(new MemberSearchType());
                return $this->render('SERCOMAppBundle:AdminEtudiant:searchresult.html.twig', array('persons' => $persons, 'form' => $form->createView()));
            }

            return $this->render('SERCOMAppBundle:AdminEtudiant:index.html.twig', array('persons' => $persons, 'pagination' => $pagination, 'form' => $form->createView()));
        }
        else{
            throw new AccessDeniedException();
        }
    }

    public function voirAction(Student $student){
        $person = $this->get('security.context')->getToken()->getUser();
        if ( $person->isGranted('ROLE_PRESIDENT') or $person->isGranted('ADMIN_STUDENTS')){
            if ( !empty($student)){
                return $this->render('@SERCOMApp/AdminEtudiant/voir.html.twig', array('student' => $student));
            }
            else{
                throw new NotFoundHttpException();
            }
        }
        else{
            throw new AccessDeniedException();
        }

    }



} 