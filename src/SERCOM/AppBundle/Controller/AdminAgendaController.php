<?php
/**
 * Created by PhpStorm.
 * User: Lwaxana
 * Date: 28/04/2015
 * Time: 08:33
 */

namespace SERCOM\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AdminAgendaController extends Controller {

    public function adminAgendaAction(){
        $person = $this->get('security.context')->getToken()->getUser();
        if ( $person->isGranted('ROLE_PRESIDENT') or $person->isGranted('ADMIN_AGENDA')){
            $rep = $this->getDoctrine()->getRepository('SERCOMAppBundle:Event');
            $events = $rep->findBy(array('validate' => false) );
            $eventsc = $rep->findBy(array('validate' => true));
            $datenow = new \DateTime();
            return $this->render('@SERCOMApp/AdminAgenda/adminagenda.html.twig', array('events' => $events , 'eventsc' => $eventsc, 'datenow' => $datenow));

        }
        else{
            throw new AccessDeniedException();
        }
    }

    public function adminAgendaDeleteAction($id){
        $person = $this->get('security.context')->getToken()->getUser();
        if ( $person->isGranted('ROLE_PRESIDENT') or $person->isGranted('ADMIN_AGENDA')){
            $rep = $this->getDoctrine()->getRepository('SERCOMAppBundle:Event');
            $event = $rep->find($id);
            if ( !empty($event)){
                try{
                    $em = $this->getDoctrine()->getManager();
                    $em->remove($event);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('succes', 'Enregistrement effectué');
                }
                catch(\Exception $e){
                    $this->get('session')->getFlashBag()->add('error', 'Une erreur est survenue');
                }
                finally{
                    $this->redirect($this->generateUrl('sercom_admin_agenda_voir'));
                }
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