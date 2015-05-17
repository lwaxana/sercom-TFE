<?php
/**
 * Created by PhpStorm.
 * User: Lwaxana
 * Date: 11/05/2015
 * Time: 08:53
 */

namespace SERCOM\AppBundle\Controller;


use SERCOM\AppBundle\Form\StudentInfos2Type;
use SERCOM\AppBundle\Form\StudentInfosType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SERCOM\AppBundle\Form\AddressType;
use SERCOM\AppBundle\Form\PersonCountryType;
use SERCOM\AppBundle\Form\PersonInfosType;
use SERCOM\AppBundle\Form\PersonPictureType;
use SERCOM\AppBundle\Form\PersonPwdType;
use Symfony\Component\HttpFoundation\Request;
use SERCOM\AppBundle\Image\Image;



class StudentController extends Controller {

    public function indexAction(){
        $person = $this->get('security.context')->getToken()->getUser();
        $rep = $this->getDoctrine()->getManager()->getRepository('SERCOMAppBundle:CoursePlanning');
        $agenda = $rep->findLastEventClasse($person->getPersonid());

        return $this->render('SERCOMAppBundle:Student:index.html.twig', array('agenda' => $agenda));


    }

    public function infosAction(Request $request){

            $person = $this->get('security.context')->getToken()->getUser();
            $address = $person->getAddress();
            $countries = $person->getCountries();
            $pic = $person->getPicture();

            $form = $this->createForm(new PersonInfosType(), $person);
            $form2 = $this->createForm(new PersonPwdType(), $person);
            $form3 = $this->createForm(new AddressType(), $address)->add('save','submit');
            $form4 = $this->createForm(new PersonCountryType(), $person);
            $form5 = $this->createForm(new PersonPictureType(), $person);
            $form6 = $this->createForm(new StudentInfosType(), $person->getStudent())->add('save','submit');
            $form7 = $this->createForm(new StudentInfos2Type(), $person->getStudent());

            if( $request->isMethod('POST')) {

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
                            return $this->redirect($this->generateUrl('student_infos'));
                        }
                    }
                }

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
                            return $this->redirect($this->generateUrl('student_infos'));
                        }
                    }
                }

                if ($request->request->has($form3->getName())) {
                    $form3->handleRequest($request);
                    if ( $form3->isValid()){
                        try {
                            $em = $this->getDoctrine()->getManager();
                            $em->persist($person);
                            $em->flush();
                            $this->get('session')->getFlashBag()->add('succes', 'Sauvegarde des modifications effectuée');
                        } catch (\Exception $e) {
                            $this->get('session')->getFlashBag()->add('error', 'Une erreur s\'est produite');
                        } finally {
                            return $this->redirect($this->generateUrl('student_infos'));
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
                            return $this->redirect($this->generateUrl('student_infos'));
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
                                $image = new Image($path2, $ext);
                                $image->resizeProfile($path, $nom);
                                $person->setPicture($webpath.$nom);
                            }
                            catch(\Exception $e){
                                $this->get('session')->getFlashBag()->add('error', 'Une erreur s\'est produite');
                                return $this->redirect($this->generateUrl('student_infos'));
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
                                return $this->redirect($this->generateUrl('student_infos'));
                            }
                        }
                        else{
                            //si photo effacer ancienne avant sauver
                            $oldpic = $person->getPicture();
                            try{
                                $file->move($path , $nom );
                                $image = new Image($path2, $ext);
                                $image->resizeProfile($path, $nom);
                                $person->setPicture($webpath.$nom);
                            }
                            catch(\Exception $e){
                                $this->get('session')->getFlashBag()->add('error', 'Une erreur s\'est produite');
                                return $this->redirect($this->generateUrl('student_infos'));
                            }

                            try{
                                unlink($this->get('kernel')->getRootDir().'/../web/'.$oldpic);
                            }
                            catch(\Exception $e){
                                $this->get('session')->getFlashBag()->add('error', 'Une erreur s\'est produite');
                                return $this->redirect($this->generateUrl('student_infos'));
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
                                return $this->redirect($this->generateUrl('student_infos'));
                            }
                        }
                    }
                }

                if ($request->request->has($form6->getName())) {
                    $form6->handleRequest($request);
                    if ( $form6->isValid()){
                        try{
                            $em= $this->getDoctrine()->getManager();
                            $em->persist($person);
                            $em->flush();
                            $this->get('session')->getFlashBag()->add('succes', 'Enregistrement effectué');
                        }
                        catch(\Exception $e){
                            $this->get('session')->getFlashBag()->add('error', 'Une erreur est survenue');
                        }
                        finally{
                            return $this->redirect($this->generateUrl('student_infos'));
                        }


                    }
                }

                if ($request->request->has($form7->getName())) {
                    $form7->handleRequest($request);
                    if ( $form6->isValid()){
                        try{
                            $em= $this->getDoctrine()->getManager();
                            $em->persist($person);
                            $em->flush();
                            $this->get('session')->getFlashBag()->add('succes', 'Enregistrement effectué');
                        }
                        catch(\Exception $e){
                            $this->get('session')->getFlashBag()->add('error', 'Une erreur est survenue');
                        }
                        finally{
                            return $this->redirect($this->generateUrl('student_infos'));
                        }


                    }
                }

            }





            return $this->render('@SERCOMApp/Student/mesinfos.html.twig', array('form' => $form->createView(), 'form2' => $form2->createView(), 'form3' => $form3->createView(),
                'form4' => $form4->createView(),'form5' => $form5->createView(),'form6' => $form6->createView(), 'countries' => $countries, 'pic' => $pic , 'form7' => $form7->createView()));

    }

    public function classesAction(){
        $person = $this->get('security.context')->getToken()->getUser();
        $classes = $person->getStudent()->getClasses();
        return $this->render('@SERCOMApp/Student/mesclasses.html.twig', array('classes' => $classes));
    }



} 