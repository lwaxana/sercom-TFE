<?php
/**
 * Created by PhpStorm.
 * User: Lwaxana
 * Date: 25/04/2015
 * Time: 13:02
 */

namespace SERCOM\AppBundle\Controller;

use SERCOM\AppBundle\Entity\AsblDocument;
use SERCOM\AppBundle\Entity\AsblDocumentCat;
use SERCOM\AppBundle\Entity\AsblDocumentSousCat;
use SERCOM\AppBundle\Form\AsblDocument2Type;
use SERCOM\AppBundle\Form\AsblDocumentCatType;
use SERCOM\AppBundle\Form\AsblDocumentSousCatType;
use SERCOM\AppBundle\Form\AsblDocumentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


class AdminDocumentsController extends Controller {

    public function adminDocsAction(Request $request){
        $person = $this->get('security.context')->getToken()->getUser();
        if ( $person->isGranted('ROLE_PRESIDENT') or $person->isGranted('ADMIN_DOCS')){
            $rep = $this->getDoctrine()->getRepository('SERCOMAppBundle:AsblDocumentCat');
            $docs = $rep->findAll();
            return $this->render('SERCOMAppBundle:AdminDocuments:admindoc.html.twig', array('docs' => $docs));
        }
        else{
            throw new AccessDeniedException();
        }
    }

    public function modifyDocumentCatAction($id, Request $request){
        $person = $this->get('security.context')->getToken()->getUser();
        if ( $person->isGranted('ROLE_PRESIDENT') or $person->isGranted('ADMIN_DOCS')){
            $rep = $this->getDoctrine()->getRepository('SERCOMAppBundle:AsblDocumentCat');
            $cat = $rep->find($id);
            if ( !empty($cat)){
                $form = $this->createForm(new AsblDocumentCatType(), $cat);
                $form->handleRequest($request);
                if ( $form->isValid()){
                    try{
                        $em = $this->getDoctrine()->getManager();
                        $em->persist($cat);
                        $em->flush();
                        $this->get('session')->getFlashBag()->add('succes', 'Modifications de la catégorie effectuées');
                    }
                    catch(\Exception $e){
                        $this->get('session')->getFlashBag()->add('error', 'Une erreur est survenue');
                    }
                    finally{
                        return $this->redirect($this->generateUrl('sercom_admin_docs'));
                    }
                }
                return $this->render('SERCOMAppBundle:AdminDocuments:modifycatdoc.html.twig',array('form' => $form->createView()));
            }
            else{
                throw new NotFoundHttpException();
            }
        }
        else{
            throw new AccessDeniedException();
        }
    }

    public function deleteDocumentCatAction($id){
        $person = $this->get('security.context')->getToken()->getUser();
        if ( $person->isGranted('ROLE_PRESIDENT') or $person->isGranted('ADMIN_DOCS')){
            $rep = $this->getDoctrine()->getRepository('SERCOMAppBundle:AsblDocumentCat');
            $cat = $rep->find($id);
            if ( !empty($cat)){
                try{
                    $em = $this->getDoctrine()->getManager();
                    $em->remove($cat);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('succes', 'Suppression de la catégorie effectuée');
                }
                catch(\Exception $e){
                    $this->get('session')->getFlashBag()->add('error', 'Une erreur est survenue');
                }
                finally{
                    return $this->redirect($this->generateUrl('sercom_admin_docs'));
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

    public function adminDocumentsSousCatAction($id){
        $person = $this->get('security.context')->getToken()->getUser();
        if ( $person->isGranted('ROLE_PRESIDENT') or $person->isGranted('ADMIN_DOCS')){
            $rep = $this->getDoctrine()->getRepository('SERCOMAppBundle:AsblDocumentCat');
            $cat = $rep->find($id);
            if ( !empty($cat)){
                return $this->render('SERCOMAppBundle:AdminDocuments:admindocsscat.html.twig', array('cat' => $cat));
            }
            else{
                throw new NotFoundHttpException();
            }
        }
        else{
            throw new AccessDeniedException();
        }
    }

    public function modifyDocumentssCatAction($id, Request $request){
        $person = $this->get('security.context')->getToken()->getUser();
        if ( $person->isGranted('ROLE_PRESIDENT') or $person->isGranted('ADMIN_DOCS')){
            $rep = $this->getDoctrine()->getRepository('SERCOMAppBundle:AsblDocumentSousCat');
            $cat = $rep->find($id);
            if ( !empty($cat)){
                $form = $this->createForm(new AsblDocumentSousCatType(), $cat)->remove('category');
                $form->handleRequest($request);
                if ( $form->isValid() ){
                    try{
                        $em = $this->getDoctrine()->getManager();
                        $em->persist($cat);
                        $em->flush();
                        $this->get('session')->getFlashBag()->add('succes', 'Ajout de la sous-catégorie effectuée');
                    }
                    catch(\Exception $e){
                        $this->get('session')->getFlashBag()->add('error', 'Une erreur est survenue');
                    }
                    finally{
                        return $this->redirect($this->generateUrl('sercom_admin_docs_sscat', array('id' => $id)));
                    }
                }
                return $this->render('SERCOMAppBundle:AdminDocuments:modifysouscatdoc.html.twig', array('form'=>$form->createView(), 'cat' => $cat));
            }
            else{
                throw new NotFoundHttpException();
            }
        }
        else{
            throw new AccessDeniedException();
        }
    }

    public function deleteDocumentssCatAction($id){
        $person = $this->get('security.context')->getToken()->getUser();
        if ( $person->isGranted('ROLE_PRESIDENT') or $person->isGranted('ADMIN_DOCS')){
            $rep = $this->getDoctrine()->getRepository('SERCOMAppBundle:AsblDocumentSousCat');
            $cat = $rep->find($id);
            if ( !empty($cat)){
                try{
                    $em = $this->getDoctrine()->getManager();
                    $em->remove($cat);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('succes', 'Suppression de la sous-catégorie effectuée');
                }
                catch(\Exception $e){
                    $this->get('session')->getFlashBag()->add('error', 'Une erreur est survenue');
                }
                finally{
                    return $this->redirect($this->generateUrl('sercom_admin_docs_sscat', array('id' => $cat->getCategory()->getDoccatid())));
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

    public function admindocumentsfilesAction($id, Request $request){

        $person = $this->get('security.context')->getToken()->getUser();
        $rep = $this->getDoctrine()->getRepository('SERCOMAppBundle:SiteGroup');
        $groups = $rep->getRolesAdmin($person->getPersonid());
        if ( $this->get('security.context')->isGranted('ROLE_PRESIDENT') or (in_array("ADMIN_DOCS", $groups))){
            $rep = $this->getDoctrine()->getRepository('SERCOMAppBundle:AsblDocumentSousCat');
            $cat = $rep->find($id);
            if (!empty($cat)){
                $doc = new AsblDocument();
                $form = $this->createForm(new AsblDocumentType(), $doc)->add('file')->remove('souscat')->remove('url')->remove('name');
                $form->handleRequest($request);
                if ( $form->isValid()){
                    try{
                        $file = $doc->getFile();
                        $path = $this->get('kernel')->getRootDir().'/../src/documents/';
                        $nom = sha1(uniqid(mt_rand(), true)).'.'.$file->guessExtension();
                        $nom2 = $file->getClientOriginalName();
                        $doc->setName($nom2);
                        $file->move($path, $nom);
                        $doc->setUrl($path.$nom);
                        $doc->setSouscat($cat);
                        $em = $this->getDoctrine()->getManager();
                        $em->persist($doc);
                        $em->flush();
                        $this->get('session')->getFlashBag()->add('succes', 'Ajout du fichier effectué');
                    }
                    catch(\Exception $e){
                        $this->get('session')->getFlashBag()->add('error', 'Une erreur est survenue');
                    }
                    finally{
                        return $this->redirect($this->generateUrl('sercom_admin_docs_files', array('id' => $cat->getSouscatId())));
                    }
                }
                return $this->render('SERCOMAppBundle:AdminDocuments:admindocfiles.html.twig', array('cat' => $cat, 'form'=>$form->createView()));
            }
        }
        else{
            throw new AccessDeniedException();
        }
    }

    public function admindocdeletefileAction($id){
        $person = $this->get('security.context')->getToken()->getUser();
        if ( $person->isGranted('ROLE_PRESIDENT') or $person->isGranted('ADMIN_DOCS')){
            $rep = $this->getDoctrine()->getRepository('SERCOMAppBundle:AsblDocument');
            $doc = $rep->find($id);
            if ( !empty($doc)){
                try{
                    $em = $this->getDoctrine()->getManager();
                    unlink(realpath($doc->getUrl()));
                    $em->remove($doc);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('succes', 'Ajout du fichier effectué');
                }
                catch(\Exception $e){
                    $this->get('session')->getFlashBag()->add('error', 'Une erreur est survenue');
                }
                finally{
                    return $this->redirect($this->generateUrl('sercom_admin_docs_files', array('id' => $doc->getSouscat()->getSouscatid())));
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


    public function modifyfileAction($id, Request $request){
        $person = $this->get('security.context')->getToken()->getUser();
        if ( $person->isGranted('ROLE_PRESIDENT') or $person->isGranted('ADMIN_DOCS')) {
            $rep = $this->getDoctrine()->getRepository('SERCOMAppBundle:AsblDocument');
            $doc = $rep->find($id);
            if (!empty($doc)) {
                $form = $this->createForm(new AsblDocument2Type(), $doc)->remove('file');
                $form->handleRequest($request);
                if ($form->isValid()) {
                    try{
                        $em = $this->getDoctrine()->getManager();
                        $em->persist($doc);
                        $em->flush();
                        $this->get('session')->getFlashBag()->add('succes', 'Enregistrement effectué');
                    }
                    catch(\Exception $e){
                        $this->get('session')->getFlashBag()->add('error', 'Une erreur est survenue');
                    }
                    finally{
                        return $this->redirect($this->generateUrl('sercom_admin_docs'));
                    }
                 }
                return $this->render('SERCOMAppBundle:AdminDocuments:modifyfile.html.twig', array('doc' => $doc, 'form' => $form->createView()));
            }
            else {
                throw new NotFoundHttpException();
            }
        }
        else{
            throw new AccessDeniedException();
        }
    }

    public function addcategoryAction(Request $request){
        $person = $this->get('security.context')->getToken()->getUser();
        if ( $person->isGranted('ROLE_PRESIDENT') or $person->isGranted('ADMIN_DOCS')){
            $cat = new AsblDocumentCat();
            $form = $this->createForm(new AsblDocumentCatType(), $cat);
            $form->handleRequest($request);
            if ( $form->isValid()){
                try{
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($cat);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('succes', 'Ajout de la nouvelle catégorie effectué');
                }
                catch(\Exception $e){
                    $this->get('session')->getFlashBag()->add('error', 'Une erreur est survenue');
                }
                finally{
                    return $this->redirect($this->generateUrl('sercom_admin_docs'));
                }
            }
            return $this->render('@SERCOMApp/AdminDocuments/addcat.html.twig', array('form' => $form->createView()));
        }
        else{
            throw new AccessDeniedException();
        }
    }

    public function addsscategoryAction($id, Request $request){
        $person = $this->get('security.context')->getToken()->getUser();
        if ( $person->isGranted('ROLE_PRESIDENT') or $person->isGranted('ADMIN_DOCS')){
            $rep = $this->getDoctrine()->getRepository('SERCOMAppBundle:AsblDocumentCat');
            $cat = $rep->find($id);
            if ( !empty($cat)){
                $souscat = new AsblDocumentSousCat();
                $souscat->setCategory($cat);
                $form = $this->createForm(new AsblDocumentSousCatType(), $souscat)->remove('category');
                $form->handleRequest($request);
                if ( $form->isValid() ){
                    try{
                        $em = $this->getDoctrine()->getManager();
                        $em->persist($souscat);
                        $em->flush();
                        $this->get('session')->getFlashBag()->add('succes', 'Ajout de la sous-catégorie effectuée');
                    }
                    catch(\Exception $e){
                        $this->get('session')->getFlashBag()->add('error', 'Une erreur est survenue');
                    }
                    finally{
                        return $this->redirect($this->generateUrl('sercom_admin_docs_sscat', array('id' => $id)));
                    }
                }
                return $this->render('@SERCOMApp/AdminDocuments/addsscat.html.twig', array('form' => $form->createView(), 'cat' => $cat));
            }
            else{
                throw new NotFoundHttpException();
            }

        }
        else{
            throw new AccessDeniedException();
        }
    }

    public function sousCatAction(Request $request){
        $cat_id = $request->request->get('cat_id');
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository('SERCOMAppBundle:AsblDocumentSousCat');
        $souscats = $rep->findByCat($cat_id);
        return new JsonResponse($souscats);
    }

    public function addfileAction(Request $request){
        $person = $this->get('security.context')->getToken()->getUser();
        if ( $person->isGranted('ROLE_PRESIDENT') or $person->isGranted('ADMIN_DOCS')){
            $doc = new AsblDocument();
            $form = $this->createForm(new AsblDocument2Type(), $doc );
            $form->handleRequest($request);

            if ( $form->isValid()){
                try{
                    $em = $this->getDoctrine()->getManager();
                    $path = $this->get('kernel')->getRootDir().'/../src/documents/';
                    $file = $doc->getFile();
                    $new_name = sha1(uniqid(mt_rand(), true)).'.'.$file->guessExtension();
                    $file->move($path, $new_name);
                    $doc->setName($file->getClientOriginalName());
                    $doc->setUrl($path ."/". $new_name);
                    $em->persist($doc);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('succes', 'Enregistrement effectué');
                }
                catch(\Exception $e){
                    $this->get('session')->getFlashBag()->add('error', 'Une erreur est survenue');
                }
                finally{
                    return $this->redirect($this->generateUrl('sercom_admin_docs_add_file2'));
                }
            }
            return $this->render('@SERCOMApp/AdminDocuments/addfile.html.twig', array('form' => $form->createView()));
        }
        else{
            throw new AccessDeniedException();
        }
    }

} 