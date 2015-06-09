<?php

namespace SERCOM\AppBundle\Controller;

use SERCOM\AppBundle\Entity\Member;
use SERCOM\AppBundle\Form\MemberSearchType;
use SERCOM\AppBundle\Form\MemberType;
use SERCOM\AppBundle\Form\AddressType;
use SERCOM\AppBundle\Form\PersonCountryType;
use SERCOM\AppBundle\Form\PersonInfosType;
use SERCOM\AppBundle\Form\PersonPictureType;
use SERCOM\AppBundle\Form\PersonPwdType;
use SERCOM\AppBundle\Image\Image;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


class MembersController extends Controller {

    public function indexAction(){
        $person = $this->get('security.context')->getToken()->getUser();
        $rep = $this->getDoctrine()->getManager()->getRepository('SERCOMAppBundle:Member');
        $post = $rep->getLastPost($person->getMember());
        if ( !empty($post)){
            $post = $post[0];
        }
        $rep = $this->getDoctrine()->getManager()->getRepository('SERCOMAppBundle:SiteArticle');
        $article = $rep->findOneBy(array(), array('publishDate' => 'DESC'));

        $rep = $this->getDoctrine()->getManager()->getRepository('SERCOMAppBundle:Member');
        $upload = $rep->getLastDocs($person->getMember());
        if ( !empty($upload)){
            $upload = $upload[0];
        }
        $rep = $this->getDoctrine()->getManager()->getRepository('SERCOMAppBundle:Event');
        $agenda = $rep->getLastEven();
        if ( !empty($agenda)){
            $agenda = $agenda[0];
        }

        $rep = $this->getDoctrine()->getManager()->getRepository('SERCOMAppBundle:Member');
        $mail = $rep->getLastMail($person->getMember());
        if ( !empty($mail)){
            $mail = $mail[0];
        }


        return $this->render('SERCOMAppBundle:Members:index.html.twig', array('post' => $post, 'article' => $article, 'upload' => $upload , 'agenda' => $agenda, 'mail' => $mail));

    }

    public function infosAction(Request $request){

        $person = $this->get('security.context')->getToken()->getUser();
        $member = $person->getMember();
        $address = $person->getAddress();

        $form = $this->createForm(new PersonInfosType(), $person);
        $form2 = $this->createForm(new PersonPwdType(), $person);
        $form3 = $this->createForm(new AddressType(), $address)->add('save','submit');
        $form4 = $this->createForm(new PersonCountryType(), $person);
        $form5 = $this->createForm(new PersonPictureType(), $person);
        $form6 = $this->createForm(new MemberType(), $member)->remove('actif')->remove('person')->remove('asblfunctions')->remove('forumgroups')->add('save','submit');
        $countries = $person->getCountries();
        $pic = $person->getPicture();


        if( $request->isMethod('POST')) {

            if ($request->request->has($form2->getName())) {
                $form2->handleRequest($request);
                if ( $form2->isValid()){
                    try{
                        $em = $this->getDoctrine()->getManager();
                        $pwd = $this->get('security.encoder_factory')->getEncoder($person)->encodePassword($person->getPassword(), $person->getSalt());
                        $person->setPassword($pwd);
                        $em->persist($person);
                        $em->flush();
                        $this->get('session')->getFlashBag()->add('succes', 'Sauvegarde des modifications effectuée');
                    }catch(\Exception $e){
                        $this->get('session')->getFlashBag()->add('error', 'Une erreur s\'est produite');
                    }finally{
                        return $this->redirect($this->generateUrl('sercom_members_infos'));
                    }
                }
            }

            if ($request->request->has($form->getName())) {
                $form->handleRequest($request);
                if ( $form->isValid()){
                    try{
                        $em = $this->getDoctrine()->getManager();
                        $em->persist($person);
                        $em->flush();
                        $this->get('session')->getFlashBag()->add('succes', 'Sauvegarde des modifications effectuée');
                    }catch(\Exception $e){
                        $this->get('session')->getFlashBag()->add('error', 'Une erreur s\'est produite');
                    }finally{
                        return $this->redirect($this->generateUrl('sercom_members_infos'));
                    }
                }
            }

            if ($request->request->has($form3->getName())) {
                $form3->handleRequest($request);
                if ( $form3->isValid()) {
                    try {
                        $em = $this->getDoctrine()->getManager();
                        $em->persist($person);
                        $em->flush();
                        $this->get('session')->getFlashBag()->add('succes', 'Sauvegarde des modifications effectuée');
                    } catch (\Exception $e) {
                        $this->get('session')->getFlashBag()->add('error', 'Une erreur s\'est produite');
                    } finally {
                        return $this->redirect($this->generateUrl('sercom_members_infos'));
                    }
                }
            }

            if ($request->request->has($form4->getName())) {
                $form4->handleRequest($request);
                if ( $form4->isValid()){
                    try{
                        $em = $this->getDoctrine()->getManager();
                        $em->persist($person);
                        $em->flush();
                        $this->get('session')->getFlashBag()->add('succes', 'Sauvegarde des modifications effectuée');
                    }catch(\Exception $e){
                        $this->get('session')->getFlashBag()->add('error', 'Une erreur s\'est produite');
                    }finally{
                        return $this->redirect($this->generateUrl('sercom_members_infos'));
                    }
                }
            }

            if ($request->request->has($form5->getName())) {
                $form5->handleRequest($request);
                if ( $form5->isValid()){
                    $webpath = 'bundles/AppBundle/images/profil/';
                    $path = $this->get('kernel')->getRootDir().'/../web/bundles/AppBundle/images/profil/';
                    $file = $person->getFile();
                    $nom = sha1(uniqid(mt_rand(), true)).'.'.$file->guessExtension();
                    $ext = $file->guessExtension();
                    $path2 = $this->get('kernel')->getRootDir().'/../web/bundles/AppBundle/images/profil/'.$nom;
                    $oldpic = null;
                    if ( $person->getPicture() == null ){
                        try{
                            $file->move($path , $nom );
                            //$image = new Image($path2, $ext);
                            //$image->resizeProfile($path, $nom);
                            $person->setPicture($webpath.$nom);
                        }
                        catch(\Exception $e){
                            $this->get('session')->getFlashBag()->add('error', 'Une erreur s\'est produite');
                            return $this->redirect($this->generateUrl('sercom_members_infos'));
                        }

                        try{
                            $em = $this->getDoctrine()->getManager();
                            $em->persist($person);
                            $em->flush();
                            $this->get('session')->getFlashBag()->add('succes', 'Sauvegarde des modifications effectuée');
                        }
                        catch(\Exception $e){
                            $this->get('session')->getFlashBag()->add('error', 'Une erreur s\'est produite');
                        }
                        finally{
                            return $this->redirect($this->generateUrl('sercom_members_infos'));
                        }
                    }
                    else{
                        //si photo effacer ancienne avant sauver
                        $oldpic = $person->getPicture();
                        try{
                            $file->move($path , $nom );
                            //$image = new Image($path2, $ext);
                            //$image->resizeProfile($path, $nom);
                            $person->setPicture($webpath.$nom);
                        }
                        catch(\Exception $e){
                            $this->get('session')->getFlashBag()->add('error', 'Une erreur s\'est produite');
                            return $this->redirect($this->generateUrl('sercom_members_infos'));
                        }

                        try{
                            unlink($this->get('kernel')->getRootDir().'/../web/'.$oldpic);
                        }
                        catch(\Exception $e){
                            $this->get('session')->getFlashBag()->add('error', 'Une erreur s\'est produite');
                            return $this->redirect($this->generateUrl('sercom_members_infos'));
                        }

                        try{
                            $em = $this->getDoctrine()->getManager();
                            $em->persist($person);
                            $em->flush();
                            $this->get('session')->getFlashBag()->add('succes', 'Sauvegarde des modifications effectuée');
                        }
                        catch(\Exception $e){
                            $this->get('session')->getFlashBag()->add('error', 'Une erreur s\'est produite');
                        }
                        finally{
                            return $this->redirect($this->generateUrl('sercom_members_infos'));
                        }
                    }
                }

            }

            if ($request->request->has($form6->getName())) {
                $form6->handleRequest($request);
                if ( $form6->isValid()){
                    try{
                        $em = $this->getDoctrine()->getManager();
                        $em->persist($member);
                        $em->flush();
                        $this->get('session')->getFlashBag()->add('succes', 'Sauvegarde des modifications effectuée');
                    }
                    catch(\Exception $e){
                        $this->get('session')->getFlashBag()->add('error', 'Une erreur s\'est produite');
                    }
                    finally{
                        return $this->redirect($this->generateUrl('sercom_members_infos'));
                    }
                }
            }

        }

        return $this->render('SERCOMAppBundle:Members:myinfos.html.twig', array(                'form' => $form->createView(),
                                                                                'form2' => $form2->createView(),
                                                                                'form3' => $form3->createView(),
                                                                                'form4' => $form4->createView(),
                                                                                'countries' => $countries,
                                                                                'form5' => $form5->createView(),
                                                                                'pic' => $pic,
                                                                                'form6' => $form6->createView(),

        ));

    }

    public function listemembresAction($page, Request $request){
        $rep = $this->getDoctrine()->getManager()->getRepository('SERCOMAppBundle:Person');
        $max_per_page = 9;
        $persons = $rep->findMembers($page, $max_per_page);
        $count = $rep->countMembers();

        $pagination = array(
            'page' => $page,
            'route' => 'sercom_members_listmembres',
            'pages_count' => ceil($count / $max_per_page),
            'route_params' => array('page' => $page)
        );

        $form = $this->createForm(new MemberSearchType());
        $form->handleRequest($request);
        if ( $form->isValid()){
            $data = $form->getData();
            $search = $data{'search'};
            $persons = null;
            $rep = $this->getDoctrine()->getRepository('SERCOMAppBundle:Person');
            $persons = $rep->search($search);
            $form = $this->createForm(new MemberSearchType());
            return $this->render('SERCOMAppBundle:Members:searchresult.html.twig', array('persons' => $persons, 'form' => $form->createView()));
        }

        return $this->render('SERCOMAppBundle:Members:listemembres.html.twig', array('persons' => $persons, 'form' => $form->createView(), 'pagination' => $pagination));
    }

    public function filesAction($id){
        $rep = $this->getDoctrine()->getManager()->getRepository('SERCOMAppBundle:AsblDocument');
        $doc = $rep->find($id);
        if ( !empty($doc)){
            if ($this->get('security.context')->isGranted($doc->getSouscat()->getCategory()->getSitegroup()->getName() )){
                // http://stackoverflow.com/questions/9968457/serve-a-download-of-an-uploaded-file-in-symfony2
                $headers = array(
                    'Content-Type' => 'text/plain',
                    'Content-Disposition' => 'attachment; filename="'.$doc->getName().'"' );
                return new Response(file_get_contents($doc->getUrl()), 200, $headers);
            }
            else{
                throw new AccessDeniedException();
            }
        }
        else{
            throw new NotFoundHttpException();
        }
    }

    public function voirAction(Member $member){
        return $this->render('@SERCOMApp/Members/voir.html.twig', array('member' => $member));
    }

} 