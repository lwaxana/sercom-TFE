<?php
/**
 * Created by PhpStorm.
 * User: Lwaxana
 * Date: 06/04/2015
 * Time: 13:27
 */

namespace SERCOM\AppBundle\Controller;

use SERCOM\AppBundle\Entity\Member;
use SERCOM\AppBundle\Entity\Student;
use SERCOM\AppBundle\Entity\Teacher;
use SERCOM\AppBundle\Form\PersonModifInscriptionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


class AdminController extends Controller {

    public function indexAction(){
        $person = $this->get('security.context')->getToken()->getUser();
        if ( $person->isGranted('ROLE_PRESIDENT') or $person->isGranted('ADMIN_INSCRIPTION') or $person->isGranted('ADMIN_FORUM') or
            $person->isGranted('ADMIN_USER') or $person->isGranted('ADMIN_AGENDA') or $person->isGranted('ADMIN_DOCS') or $person->isGranted('ADMIN_ARTICLES')){

            return $this->render('SERCOMAppBundle:Admin:index.html.twig');
        }
        else{
            throw new AccessDeniedException();
        }
    }

    public function validerinscriptionAction($page){

        $person = $this->get('security.context')->getToken()->getUser();
        if ( $person->isGranted('ROLE_PRESIDENT') or $person->isGranted('ADMIN_INSCRIPTION')){
            $rep = $this->getDoctrine()->getRepository('SERCOMAppBundle:Person');
            $max_per_page = 10;
            $users = $rep->getToValid($page, $max_per_page);
            $count = $rep->countToValid();
            $pagination = array(
                'page' => $page,
                'route' => 'sercom_admin_valider_inscription',
                'pages_count' => ceil($count / $max_per_page),
                'route_params' => array()
            );

            return $this->render('SERCOMAppBundle:Admin:valider.html.twig', array('users' => $users, 'pagination' => $pagination));
        }
        else{
            throw new AccessDeniedException();
        }
    }

    public function voirAction($id){

        $person = $this->get('security.context')->getToken()->getUser();
        if ( $person->isGranted('ROLE_PRESIDENT') or $person->isGranted('ADMIN_INSCRIPTION')){
            $rep =  $rep = $this->getDoctrine()->getManager()->getRepository('SERCOMAppBundle:Person');
            $person = $rep->find($id);
            if ( !empty($person)){
                $member = false;
                $teacher = false;
                $student = false;
                if ( !empty($person->getMember())){
                    $member = true;
                }
                if ( !empty($person->getTeacher())){
                    $teacher = true;
                }
                if ( !empty($person->getStudent())){
                    $student = true;
                }
                $response = new JsonResponse();
                $response->setData(array( 'prenom' => $person->getFirstname(),
                    'nom' => $person->getLastname(),
                    'mail' => $person->getEmail(),
                    'date_inscription' => $person->getDateInscription(),
                    'username' => $person->getUsername(),
                    'member' => $member,
                    'teacher' => $teacher,
                    'student' => $student   ));
                return $response;
            }
            else{
                throw new NotFoundHttpException();
            }
        }
        else{
            throw new AccessDeniedException();
        }




    }

    public function deleteAction($id){

        $p = $this->get('security.context')->getToken()->getUser();
        if ( $p->isGranted('ROLE_PRESIDENT') or $p->isGranted('ADMIN_INSCRIPTION')){
            $rep =  $rep = $this->getDoctrine()->getManager()->getRepository('SERCOMAppBundle:Person');
            $person = $rep->find($id);
            if ( !empty($person)){
                try{
                    $em = $this->getDoctrine()->getManager();
                    $em->remove($person);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('succes', 'Utilisateur correctement supprimé');
                }
                catch(\Exception $e){
                    $this->get('session')->getFlashBag()->add('error', 'Une erreur est survenue');
                }
                finally{
                    return $this->redirect($this->generateUrl('sercom_admin_valider_inscription'));
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

    // MAIL DE CONFIRM A ENVOYER
    public function validerAction($id){
        $p = $this->get('security.context')->getToken()->getUser();
        if ( $p->isGranted('ROLE_PRESIDENT') or $p->isGranted('ADMIN_INSCRIPTION')){
            $rep =  $rep = $this->getDoctrine()->getManager()->getRepository('SERCOMAppBundle:Person');
            $person = $rep->find($id);
            if ( !empty($person)){
                try{
                    $person->setValidate(true);
                    $person->setActivationcode(NULL);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($person);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('succes', 'Utilisateur correctement activé');

                }
                catch (\Exception $e){
                    $this->get('session')->getFlashBag()->add('error', 'Une erreur est survenue');
                }
                finally {
                    return $this->redirect($this->generateUrl('sercom_admin_valider_inscription', array('page' => 1) ));
                }
            }
            else{
                throw new AccessDeniedException();
            }
        }
        else{
            throw new AccessDeniedException();
        }
    }

    public function modifyAction($id, Request $request){

        $p = $this->get('security.context')->getToken()->getUser();
        if ( $p->isGranted('ROLE_PRESIDENT') or $p->isGranted('ADMIN_INSCRIPTION')){
            $rep = $this->getDoctrine()->getManager()->getRepository('SERCOMAppBundle:Person');
            $person = $rep->find($id);
            if ( !empty($person)){
                $m = 1;
                $t = 1;
                $s = 1;
                if ( !empty($person->getMember())){
                    $m = 0;
                }
                if ( !empty($person->getTeacher())){
                    $t = 0;
                }
                if ( !empty($person->getStudent())){
                    $s = 0;
                }

                $form = $this->createForm(new PersonModifInscriptionType(), $person, array('attr'=> array('m' => $m , 't' => $t, 's' => $s)));
                $form->handleRequest($request);
                if ( $form->isValid()){

                    $member = $form->get('membre')->getData();
                    $teacher = $form->get('teacher')->getData();
                    $student = $form->get('student')->getData();

                    $em = $this->getDoctrine()->getManager();

                    try{
                        if ( empty($member) ){
                            if ( !empty($person->getMember())){
                                $rep = $this->getDoctrine()->getManager()->getRepository('SERCOMAppBundle:Member');
                                $mem = $rep->find($person->getPersonid());
                                //$mem->setPerson($p);
                                $em->remove($mem);

                            }
                        }
                        else{
                            if ( empty($person->getMember())){
                                $mem = new Member();
                                $mem->setPerson($person);
                                $mem->setActif(true);
                                $em->persist($mem);
                                $em->flush();
                            }
                        }



                        if ( empty($teacher)){
                            if ( !empty($person->getTeacher())){
                                $rep = $this->getDoctrine()->getManager()->getRepository('SERCOMAppBundle:Teacher');
                                $tea = $rep->find($person->getPersonid());
                                //$tea->setPerson($p);
                                $em->remove($tea);

                            }
                        }
                        else{
                            if ( empty($person->getTeacher())){
                                $tea = new Teacher();
                                $tea->setPerson($person);
                                $tea->setActif(true);
                                $em->persist($tea);
                                $em->flush();
                            }
                        }

                        if ( empty($student)){
                            if ( !empty($person->getStudent())){
                                $rep = $this->getDoctrine()->getManager()->getRepository('SERCOMAppBundle:Student');
                                $stu = $rep->find($person->getPersonid());
                                //$stu->setPerson($p);
                                $em->remove($stu);

                            }
                        }
                        else{
                            if ( empty($person->getStudent())){
                                $stu = new Student();
                                $stu->setPerson($person);
                                $stu->setActif(true);
                                $em->persist($stu);
                                $em->flush();
                            }
                        }

                        $em->flush();
                        $this->get('session')->getFlashBag()->add('succes', 'Inscription modifiée');
                    }
                    catch( \Exception $e){
                        $this->get('session')->getFlashBag()->add('error', 'Une erreur est survenue');
                    }
                    finally{
                        return $this->redirect($this->generateUrl('sercom_admin_valider_inscription'));
                    }
                }
                return $this->render('@SERCOMApp/Admin/modify.html.twig', array('form' => $form->createView()) );
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