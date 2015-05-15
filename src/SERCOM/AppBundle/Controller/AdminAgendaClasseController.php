<?php
/**
 * Created by PhpStorm.
 * User: Lwaxana
 * Date: 11/05/2015
 * Time: 13:33
 */

namespace SERCOM\AppBundle\Controller;



use SERCOM\AppBundle\Entity\CoursePlace;
use SERCOM\AppBundle\Entity\CoursePlanning;
use SERCOM\AppBundle\Form\CoursePlaceType;
use SERCOM\AppBundle\Form\CoursePlanningType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AdminAgendaClasseController extends Controller {

    public function indexAction(Request $request){
        $person = $this->get('security.context')->getToken()->getUser();
        if ( $person->isGranted('ROLE_PRESIDENT') or $person->isGranted('ADMIN_AGENDA_CLASSE')){
            $planning = new CoursePlanning();
            $form = $this->createForm(new CoursePlanningType(), $planning);
            $form->handleRequest($request);
            if ( $form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $em->persist($planning);
                $em->flush();
                return $this->redirect($this->generateUrl('sercom_admin_agenda_classes'));
            }
            return $this->render('@SERCOMApp/AgendaClasse/index.html.twig', array('form' => $form->createView()));
        }
        else{
            throw new AccessDeniedException();
        }

    }



    public function calendarAction(Request $request){

        if ( $request->isXmlHttpRequest() ){
            $em = $this->getDoctrine()->getManager();
            $datas = $em->getRepository('SERCOMAppBundle:CoursePlanning')->getCal();
            $datas2 = array();

            foreach ($datas as $data) {
                $data = array( "title" => $data->getClasse()->getName(),'start' => $data->getDatecours()->format('Y-m-d H:i'), "timezone"=> "Europe/Paris");
                array_push($datas2, $data);
            }
            return new JsonResponse($datas2);
        }
    }

    public function placesAction(){
        $person = $this->get('security.context')->getToken()->getUser();
        if ( $person->isGranted('ROLE_PRESIDENT') or $person->isGranted('ADMIN_AGENDA_CLASSE')){
            $rep = $this->getDoctrine()->getManager()->getRepository('SERCOMAppBundle:CoursePlace');
            $lieux = $rep->findAll();
            return $this->render('@SERCOMApp/AgendaClasse/lieuxdecours.html.twig', array('lieux' => $lieux));
        }
        else{
            throw new AccessDeniedException();
        }

    }

    public function addplacesAction(Request $request){
        $person = $this->get('security.context')->getToken()->getUser();
        if ( $person->isGranted('ROLE_PRESIDENT') or $person->isGranted('ADMIN_AGENDA_CLASSE')){
            $lieu = new CoursePlace();
            $form = $this->createForm( new CoursePlaceType(), $lieu);
            $form->handleRequest($request);
            if ( $form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $em->persist($lieu);
                $em->flush();
                return $this->redirect($this->generateUrl('sercom_admin_agenda_classe_places'));

            }
            return $this->render('@SERCOMApp/AgendaClasse/addlieu.html.twig', array('form' => $form->createView()));
        }
        else{
            throw new AccessDeniedException();
        }

    }

    public function modifyplacesAction(CoursePlace $place, Request $request){
        $person = $this->get('security.context')->getToken()->getUser();
        if ( $person->isGranted('ROLE_PRESIDENT') or $person->isGranted('ADMIN_AGENDA_CLASSE')){
            if ( !empty($place)){
                $form = $this->createForm( new CoursePlaceType(), $place);
                $form->handleRequest($request);
                if ( $form->isValid()){
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($place);
                    $em->flush();
                    return $this->redirect($this->generateUrl('sercom_admin_agenda_classe_places'));

                }
                return $this->render('@SERCOMApp/AgendaClasse/addlieu.html.twig', array('form' => $form->createView()));
            }
            else{
                throw new NotFoundHttpException();
            }
        }
        else{
            throw new AccessDeniedException();
        }

    }

    public function delplaceAction(CoursePlace $place){
        $person = $this->get('security.context')->getToken()->getUser();
        if ( $person->isGranted('ROLE_PRESIDENT') or $person->isGranted('ADMIN_AGENDA_CLASSE')){
            if ( !empty($place)){
                $em = $this->getDoctrine()->getManager();
                $em->remove($place);
                $em->flush();
                return $this->redirect($this->generateUrl('sercom_admin_agenda_classe_places'));
            }
            else{
                throw new NotFoundHttpException();
            }
        }
        else{
            throw new AccessDeniedException();
        }


    }

    public function eventAction(Request $request){

        if (  $request->isXmlHttpRequest()){
            $title = $request->query->get('title');
            $time = $request->query->get('time');
            $d = new \DateTime($time);
            $em = $this->getDoctrine()->getManager();
            $data = $em->getRepository('SERCOMAppBundle:CoursePlanning')->findEvent($d, $title);
            $datas2 = array( 'name' => $data->getClasse()->getName() ,
                'place' => $data->getCourseplace()->getName(),
                'subject' => $data->getClasse()->getSuject()->getName() ,
                'prof' => $data->getClasse()->getTeacher()->getPerson()->getLastname()." ".$data->getClasse()->getTeacher()->getPerson()->getFirstname() );
           return new JsonResponse($datas2);
        }
    }

    public function actionEventsAction(){
        $person = $this->get('security.context')->getToken()->getUser();
        if ( $person->isGranted('ROLE_PRESIDENT') or $person->isGranted('ADMIN_AGENDA_CLASSE')){
            $rep = $this->getDoctrine()->getManager()->getRepository('SERCOMAppBundle:CoursePlanning');
            $cours = $rep->findAll(array(), array('datecours' => 'ASC'));
            return $this->render('@SERCOMApp/AgendaClasse/gerer.html.twig', array('courses' => $cours));
        }
        else{
            throw new AccessDeniedException();
        }

    }

    public function deleteEventsAction(CoursePlanning $planning){
        $person = $this->get('security.context')->getToken()->getUser();
        if ( $person->isGranted('ROLE_PRESIDENT') or $person->isGranted('ADMIN_AGENDA_CLASSE')){
            if ( !empty($planning)){
                $em = $this->getDoctrine()->getManager();
                $em->remove($planning);
                $em->flush();
                return $this->redirect($this->generateUrl('sercom_admin_agenda_classe_event_del'));
            }
            else{
                throw new NotFoundHttpException();
            }
        }
        else{
            throw new AccessDeniedException();
        }

    }

    public function modifyEventsAction(CoursePlanning $planning, Request $request){
        $person = $this->get('security.context')->getToken()->getUser();
        if ( $person->isGranted('ROLE_PRESIDENT') or $person->isGranted('ADMIN_AGENDA_CLASSE')){
            if ( !empty($planning)){
                $form = $this->createForm(new CoursePlanningType(), $planning);
                $form->handleRequest($request);
                if ( $form->isValid()){
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($planning);
                    $em->flush();
                    return $this->redirect($this->generateUrl('sercom_admin_agenda_classes'));
                }
                return $this->render('@SERCOMApp/AgendaClasse/modify.html.twig', array('form' => $form->createView()));
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