<?php
/**
 * Created by PhpStorm.
 * User: Lwaxana
 * Date: 11/05/2015
 * Time: 13:33
 */

namespace SERCOM\AppBundle\Controller;


use Doctrine\DBAL\DBALException;
use Doctrine\ORM\ORMException;
use SERCOM\AppBundle\Entity\Classe;
use SERCOM\AppBundle\Entity\Subject;
use SERCOM\AppBundle\Form\ClasseType;
use SERCOM\AppBundle\Form\SubjectType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SERCOM\AppBundle\Form\MemberSearchType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AdminClasseController extends Controller {

    public function indexAction($page, Request $request){
            $person = $this->get('security.context')->getToken()->getUser();
            if ( $person->isGranted('ROLE_PRESIDENT') or $person->isGranted('ADMIN_CLASSES')){
                $rep = $this->getDoctrine()->getRepository('SERCOMAppBundle:Classe');
                $max_per_page = 10;
                $classes = $rep->findClasses();
                $count = $rep->countClasses();

                $pagination = array(
                    'page' => $page,
                    'route' => 'sercom_admin_classes',
                    'pages_count' => ceil($count / $max_per_page),
                    'route_params' => array('page' => $page)
                );

                $form = $this->createForm(new MemberSearchType());
                $form->handleRequest($request);
                if ( $form->isValid()){
                    $data = $form->getData();
                    $search = $data{'search'};
                    $persons = null;
                    $rep = $this->getDoctrine()->getRepository('SERCOMAppBundle:Classe');
                    $classes = $rep->search($search);
                    $form = $this->createForm(new MemberSearchType());
                    return $this->render('SERCOMAppBundle:AdminClasse:searchresult.html.twig', array('classes' => $classes, 'form' => $form->createView()));
                }

                return $this->render('@SERCOMApp/AdminClasse/index.html.twig', array('pagination' => $pagination, 'classes' => $classes, 'form' => $form->createView()));
        }
        else{
            throw new AccessDeniedException();
        }
    }

    public function addclasseAction(Request $request){
        $person = $this->get('security.context')->getToken()->getUser();
        if ( $person->isGranted('ROLE_PRESIDENT') or $person->isGranted('ADMIN_CLASSES')){
            $classe = new Classe();
            $form = $this->createForm( new ClasseType(), $classe)->add('save','submit');

            $form->handleRequest($request);
            if ( $form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $t = $form->get('teacher')->getData();
                $students = $form->get('students')->getData();

                $rep = $this->getDoctrine()->getRepository('SERCOMAppBundle:Teacher');
                $teacher = $rep->find($t);
                $classe->setTeacher($teacher);
                $em->persist($classe);
                $em->flush();
                 return $this->redirect($this->generateUrl('sercom_admin_classes'));
            }
            return $this->render('@SERCOMApp/AdminClasse/addclasse.html.twig', array('form' => $form->createView()));
        }
        else{
            throw new AccessDeniedException();
        }


    }

    public function deleteClasseAction(Classe $classe){
        $person = $this->get('security.context')->getToken()->getUser();
        if ( $person->isGranted('ROLE_PRESIDENT') or $person->isGranted('ADMIN_CLASSES')){
            try {
                $em = $this->getDoctrine()->getManager();
                $em->remove(@$classe);
                $em->flush();
                $this->get('session')->getFlashBag()->add('succes', 'Suppression effectuée');
            }catch (\Exception $e) {
                $this->get('session')->getFlashBag()->add('error', 'Une erreur est survenue');
            }
            finally{
            /*
            }catch( ORMException $e){
                $this->get('session')->getFlashBag()->add('error', 'Une erreur est survenue');
            }catch( DBALException $e){
                $this->get('session')->getFlashBag()->add('error', 'Une erreur est survenue');
            }catch( \Exception $e){
                $this->get('session')->getFlashBag()->add('error', 'Une erreur est survenue');
            }
            finally{*/
                return $this->redirect($this->generateUrl('sercom_admin_classes'));
            }
        }
        else{
            throw new AccessDeniedException();
        }
    }

    public function addSubjectAction(Request $request){
        $person = $this->get('security.context')->getToken()->getUser();
        if ( $person->isGranted('ROLE_PRESIDENT') or $person->isGranted('ADMIN_CLASSES')){
            $subject = new Subject();
            $form = $this->createForm(new SubjectType(), $subject)->add('save', 'submit');
            $form->handleRequest($request);
            if ( $form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $em->persist($subject);
                $em->flush();
                return $this->redirect($this->generateUrl('sercom_admin_classes'));
            }

            return $this->render('@SERCOMApp/AdminClasse/addsubject.html.twig', array('form' => $form->createView()));
        }
        else{
            throw new AccessDeniedException();
        }

    }

    public function modifyClasseAction(Classe $classe, Request $request){
        $form = $this->createForm(new ClasseType(), $classe)->add('save','submit');
        $form->handleRequest($request);
        if ( $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($classe);
            $em->flush();
            return $this->redirect($this->generateUrl('sercom_admin_classes'));
        }
        return $this->render('@SERCOMApp/AdminClasse/modify.html.twig', array('form' => $form->createView()));
    }

    public function delSubjectPageAction(){
        $person = $this->get('security.context')->getToken()->getUser();
        if ( $person->isGranted('ROLE_PRESIDENT') or $person->isGranted('ADMIN_CLASSES')){
            $rep = $this->getDoctrine()->getRepository('SERCOMAppBundle:Subject');
            $subjects = $rep->findAll();
            return $this->render('@SERCOMApp/AdminClasse/delsubject.html.twig', array('subjects' => $subjects));
        }
    }


    public function delSubjectAction(Subject $subject){
        $person = $this->get('security.context')->getToken()->getUser();
        if ( $person->isGranted('ROLE_PRESIDENT') or $person->isGranted('ADMIN_CLASSES')){
            try{
                $em = $this->getDoctrine()->getManager();
                $em->remove($subject);
                $em->flush();
                $this->get('session')->getFlashBag()->add('succes', 'Suppression effectuée');
            }catch( ORMException $e){
                $this->get('session')->getFlashBag()->add('error', 'Une erreur est survenue');
            }catch( DBALException $e){
                $this->get('session')->getFlashBag()->add('error', 'Une erreur est survenue');
            }catch( \Exception $e){
                $this->get('session')->getFlashBag()->add('error', 'Une erreur est survenue');
            }

            finally{
                return $this->redirect($this->generateUrl('sercom_admin_del_subject_page'));
            }
        }
        else{
            throw new AccessDeniedException();
        }
    }
} 