<?php
/**
 * Created by PhpStorm.
 * User: Lwaxana
 * Date: 25/04/2015
 * Time: 12:47
 */

namespace SERCOM\AppBundle\Controller;


use SERCOM\AppBundle\Entity\ForumGroup;
use SERCOM\AppBundle\Entity\Forum;
use SERCOM\AppBundle\Form\ForumGroupAddForumType;
use SERCOM\AppBundle\Form\ForumType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


class AdminForumController extends Controller {

    public function adminforumAction(){
        $p = $this->get('security.context')->getToken()->getUser();
        if ( $p->isGranted('ROLE_PRESIDENT') or $p->isGranted('ADMIN_FORUM')){
            $rep = $this->getDoctrine()->getRepository('SERCOMAppBundle:Forum');
            $forums = $rep->findAll();
            return $this->render('SERCOMAppBundle:AdminForum:adminforum.html.twig', array('forums' => $forums));
        }
        else{
            throw new AccessDeniedException();
        }
    }

    public function modifygroupsAction($id, Request $request){
        $p = $this->get('security.context')->getToken()->getUser();
        if ( $p->isGranted('ROLE_PRESIDENT') or $p->isGranted('ADMIN_FORUM')){
            $rep = $this->getDoctrine()->getRepository('SERCOMAppBundle:Forum');
            $forum = $rep->find($id);
            if ( !empty($forum)){
                $form = $this->createForm(new ForumType(), $forum);
                $form->handleRequest($request);
                if ($form -> isValid() ){
                    try{
                        $em = $this->getDoctrine()->getManager();
                        $em->persist($forum);
                        $em->flush();
                        $this->get('session')->getFlashBag()->add('succes', 'Sauvegarde des changements effectuée');
                    }
                    catch(\Exception $e){
                        $this->get('session')->getFlashBag()->add('error', 'Une erreur est survenue');
                    }
                    finally{
                        return $this->redirect($this->generateUrl('sercom_admin_forum'));
                    }
                }
                return $this->render('@SERCOMApp/AdminForum/modifygroups.html.twig', array('form' => $form->createView()));
            }
            else{
                throw new NotFoundHttpException();
            }
        }
        else{
            throw new AccessDeniedException();
        }
    }

    public function addforumAction(Request $request){
        $p = $this->get('security.context')->getToken()->getUser();
        if ( $p->isGranted('ROLE_PRESIDENT') or $p->isGranted('ADMIN_FORUM')){
            $forum = new Forum();
            $form_forum =  $this->createForm(new ForumType(), $forum);
            $form_forum->handleRequest($request);
            if ( $form_forum->isValid()){
                try{
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($forum);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('succes', 'Ajout du nouveau forum effectué');
                }
                catch(\Exception $e){
                    $this->get('session')->getFlashBag()->add('error', 'Une erreur est survenue');
                }
                finally{
                    return $this->redirect($this->generateUrl('sercom_admin_forum'));
                }
            }
            return $this->render('@SERCOMApp/AdminForum/addforum.html.twig', array('form' => $form_forum->createView()));
        }
        else{
            throw new AccessDeniedException();
        }


    }

    public function addGroupsAction(Request $request){
        $p = $this->get('security.context')->getToken()->getUser();
        if ( $p->isGranted('ROLE_PRESIDENT') or $p->isGranted('ADMIN_FORUM')){
            $group = new ForumGroup();
            $form_group = $this->createForm(new ForumGroupAddForumType(), $group)->add('save','submit');
            $form_group->handleRequest($request);
            if ( $form_group->isValid()){
                try{
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($group);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('succes', 'Ajout du nouveau groupe effectué');
                }
                catch(\Exception $e){
                    $this->get('session')->getFlashBag()->add('error', 'Une erreur est survenue');
                }
                finally{
                    return $this->redirect($this->generateUrl('sercom_admin_forum'));
                }
            }
            return $this->render('@SERCOMApp/AdminForum/addgroup.html.twig', array('form' => $form_group->createView()));
        }
        else{
            throw new AccessDeniedException();
        }


    }

    public function delForumAction(){
        $p = $this->get('security.context')->getToken()->getUser();
        if ( $p->isGranted('ROLE_PRESIDENT') or $p->isGranted('ADMIN_FORUM')){
            $rep = $this->getDoctrine()->getRepository('SERCOMAppBundle:Forum');
            $forums = $rep->findAll();
            return $this->render('@SERCOMApp/AdminForum/delforum.html.twig', array('forums' => $forums));
        }
        else{
            throw new AccessDeniedException();
        }
    }

    public function delGroupsAction(){
        $p = $this->get('security.context')->getToken()->getUser();
        if ( $p->isGranted('ROLE_PRESIDENT') or $p->isGranted('ADMIN_FORUM')){
            $rep = $this->getDoctrine()->getRepository('SERCOMAppBundle:ForumGroup');
            $groups = $rep->findAll();
            return $this->render('@SERCOMApp/AdminForum/delgroup.html.twig', array('groups' => $groups));
        }
        else{
            throw new AccessDeniedException();
        }

    }

    public function deleteForumAction($id){
        $p = $this->get('security.context')->getToken()->getUser();
        if ( $p->isGranted('ROLE_PRESIDENT') or $p->isGranted('ADMIN_FORUM')){
            $rep = $this->getDoctrine()->getRepository('SERCOMAppBundle:Forum');
            $forum = $rep->find($id);
            if (!empty($forum)) {
                try{
                    $em = $this->getDoctrine()->getManager();
                    $em->remove($forum);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('succes', 'Le forum a été supprimé');
                }
                catch(\Exception $e){
                    $this->get('session')->getFlashBag()->add('error', 'Une erreur est survenue');
                }
                finally{
                   return $this->redirect($this->generateUrl('sercom_members_forum_delete_forum'));
                }
            }
            else {
                throw new NotFoundHttpException();
            }
        }
        else{
            throw new AccessDeniedException();
        }
    }

    public function deleteGroupAction($id){
        $p = $this->get('security.context')->getToken()->getUser();
        if ( $p->isGranted('ROLE_PRESIDENT') or $p->isGranted('ADMIN_FORUM')){
            $rep = $this->getDoctrine()->getRepository('SERCOMAppBundle:ForumGroup');
            $group = $rep->find($id);
            if (!empty($group)) {
                try{
                    $em = $this->getDoctrine()->getManager();
                    $em->remove($group);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('succes', 'Le groupe a été supprimé');
                }
                catch(\Exception $e){
                    $this->get('session')->getFlashBag()->add('error', 'Une erreur est survenue');
                }
                finally{
                    return $this->redirect($this->generateUrl('sercom_members_forum_delete_groups'));
                }
            }
            else {
                throw new NotFoundHttpException();
            }
        }
        else{
            throw new AccessDeniedException();
        }
    }

} 