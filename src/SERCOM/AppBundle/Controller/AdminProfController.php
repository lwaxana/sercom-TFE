<?php
/**
 * Created by PhpStorm.
 * User: Lwaxana
 * Date: 11/05/2015
 * Time: 13:33
 */

namespace SERCOM\AppBundle\Controller;


use SERCOM\AppBundle\Entity\Teacher;
use SERCOM\AppBundle\Form\TeacherType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use SERCOM\AppBundle\Form\MemberSearchType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AdminProfController extends Controller {

    public function indexAction($page, Request $request){
        $person = $this->get('security.context')->getToken()->getUser();
        if ( $person->isGranted('ROLE_PRESIDENT') or $person->isGranted('ADMIN_PROFS')){
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
        else{
            throw new AccessDeniedException();
        }

    }

    public function voirAction(Teacher $teacher){
        $person = $this->get('security.context')->getToken()->getUser();
        if ( $person->isGranted('ROLE_PRESIDENT') or $person->isGranted('ADMIN_PROFS')){
            if ( !empty($teacher)){
                return $this->render('@SERCOMApp/AdminProf/voir.html.twig', array('teacher' => $teacher));
            }
            else{
                throw new NotFoundHttpException();
            }
        }
        else{
            throw new AccessDeniedException();
        }

    }

    public function modifierAction(Teacher $teacher, Request $request){
        $person = $this->get('security.context')->getToken()->getUser();
        if ( $person->isGranted('ROLE_PRESIDENT') or $person->isGranted('ADMIN_PROFS')){
            if ( !empty($teacher)){
                $form = $this->createForm(new TeacherType(), $teacher)->add('save','submit');
                $form->handleRequest($request);
                if ( $form->isValid()){
                    try{
                        $em = $this->getDoctrine()->getManager();
                        foreach( $teacher->getSubjects() as $s){
                            $s->addTeacher($teacher);
                        }
                        $em->persist($teacher);
                        $em->flush();
                        $this->get('session')->getFlashBag()->add('succes', 'Enregistrement effectué');
                    }
                    catch(\Exception $e){
                        $this->get('session')->getFlashBag()->add('error', 'Une erreur est survenue');
                    }
                    finally{
                        return $this->redirect($this->generateUrl('sercom_admin_teachers'));
                    }
                }
                return $this->render('@SERCOMApp/AdminProf/modify.html.twig', array('form' => $form->createView()));
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