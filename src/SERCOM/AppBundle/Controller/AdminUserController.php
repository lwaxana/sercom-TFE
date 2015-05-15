<?php
/**
 * Created by PhpStorm.
 * User: Lwaxana
 * Date: 25/04/2015
 * Time: 13:06
 */

namespace SERCOM\AppBundle\Controller;

use SERCOM\AppBundle\Entity\Member;
use SERCOM\AppBundle\Entity\Student;
use SERCOM\AppBundle\Entity\Teacher;
use SERCOM\AppBundle\Form\MemberAsblFunctionType;
use SERCOM\AppBundle\Form\MemberType;
use SERCOM\AppBundle\Form\PersonModifRightsType;
use SERCOM\AppBundle\Form\PersonSiteGroupType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


class AdminUserController extends Controller {

    public function adminusersAction($page){
        $person = $this->get('security.context')->getToken()->getUser();
        if ( $person->isGranted('ROLE_PRESIDENT') or $person->isGranted('ADMIN_USER')){
            $rep = $this->getDoctrine()->getRepository('SERCOMAppBundle:Person');

            $max_per_page = 10;
            $users = $rep->getValid($page, $max_per_page); ;
            $count = $rep->countValid();

            $pagination = array(
                'page' => $page,
                'route' => 'sercom_admin_users',
                'pages_count' => ceil($count / $max_per_page),
                'route_params' => array()
            );

            return $this->render('SERCOMAppBundle:AdminUser:adminuser.html.twig', array('users' => $users, 'pagination' => $pagination));
        }
        else{
            throw new AccessDeniedException();
        }
    }

    public function modifyAction($id, Request $request){
        $person = $this->get('security.context')->getToken()->getUser();
        if ( $person->isGranted('ROLE_PRESIDENT') or $person->isGranted('ADMIN_USER')){
            $rep = $this->getDoctrine()->getRepository('SERCOMAppBundle:Person');
            $p = $rep->find($id);
            if ( !empty($p)){

                $m = ( empty($p->getMember()) || !$p->getMember()->getActif())?1:0;
                $t = ( empty($p->getTeacher()) ||  !$p->getTeacher()->getActif())?1:0;
                $s = ( empty($p->getStudent()) || !$p->getStudent()->getActif())?1:0;

                $d = $rep->getRoleUser($p->getPersonid());
                $d = $d[0]->getName();

                $rep = $this->getDoctrine()->getRepository('SERCOMAppBundle:SiteGroup');
                $roless = $rep->getMembersRoles();
                $d2 = "";
                foreach ( $p->getSitegroups() as $gr){
                    if ( in_array($gr, $roless)){

                        $d2 = $gr->getSitegroupid();
                    }
                }

                $user_teacher_rights = 0;
                $user_student_rights = 0;
                $roles = $p->getSitegroups();

                foreach( $roles as $role){
                    if ( $role->getName() == "ROLE_TEACHER"){
                        $user_teacher_rights = 1;
                    }
                    if ( $role->getName() == "ROLE_STUDENT"){
                        $user_student_rights = 1;
                    }
                }

                $form = $this->createForm(new PersonModifRightsType(), $p, array('attr'=> array('m' => $m ,
                            't' => $t, 's' => $s, 'd' => $d, 'd2' => $d2)));

                $form->handleRequest($request);
                if ( $form->isValid()){

                    $membre = $form->get('membre')->getData();
                    if ( $membre != null){
                        $membre = $membre[0];
                    }
                    $membre_groupe = $form->get('accesmembre')->getData();


                    $prof = $form->get('teacher')->getData();
                    if ( $prof != null){
                        $prof = $prof[0];
                    }
                    $student = $form->get('student')->getData();
                    if ( $student != null){
                        $student = $student[0];
                    }
                    $prof_group = $form->get('accesprof')->getData();
                    $student_group = $form->get('accesetudiant')->getData();

                    $rep = $this->getDoctrine()->getRepository('SERCOMAppBundle:SiteGroup');
                    $roles_membres = $rep->getMembersRoles();
                    $user_roles = $p->getSitegroups()->toArray();
                    $em = $this->getDoctrine()->getManager();

                    if ( !empty($p->getMember())){
                        if ( $membre == 0){
                            // membre existe et case cochée ---> CHECK DROITS
                            if ( !in_array($membre_groupe, $p->getSitegroups()->toArray())){
                                foreach ( $user_roles as $role){
                                    if ( in_array($role, $roles_membres)){
                                        $p->removeSitegroup($role);
                                    }
                                }
                                $p->getMember()->setActif(true);
                                $em->persist($p);
                                $p->addSitegroup($membre_groupe);
                                $em->persist($p);
                                $em->flush();
                            }

                        }
                        else{
                            //effacer membre + droits eventuels
                            foreach( $roles_membres as $r) {
                                if (in_array($r, $user_roles)) {
                                    $p->removeSitegroup($r);
                                }
                            }
                            foreach( $rep->getAdminRoles() as $r){
                                if ( in_array($r, $user_roles)){
                                    $p->removeSitegroup($r);
                                }
                            }
                            $em->persist($p);
                            $em->flush();
                            $member = $p->getMember();
                            $member->setActif(false);
                            $em->persist($member);
                            $em->flush();


                        }
                    }
                    else{
                        if ( $membre == 0){
                            $membre = new Member();
                            $membre->setPerson($p);
                            $membre->setActif(true);
                            $em->persist($membre);
                            $em->flush();
                            $p->addSitegroup($membre_groupe);
                            $em->persist($p);
                            $em->flush();
                        }
                    }

                    $prof_role = $rep->findOneByName('ROLE_TEACHER');

                    if ( !empty($p->getTeacher())){
                        if ( $prof == 0){
                            if ( !in_array($prof_role, $user_roles)){
                                $p->addSitegroup($prof_role);
                            }
                            $p->getTeacher()->setActif(true);
                            $em->persist($p);
                            $em->flush();
                        }
                        else{
                            foreach ( $user_roles as $role){
                                if ( $role->getName() == $prof_role->getName()){
                                    $p->removeSitegroup($prof_role);
                                }
                            }
                            $em->persist($p);
                            $em->flush();
                            $teacher = $p->getTeacher();
                            $teacher->setActif(false);
                            $em->persist($teacher);
                            $em->flush();
                        }
                    }
                    else{
                        // creer prof + droits
                        if ( $prof == 0){
                            $teacher = new Teacher();
                            $teacher->setPerson($p);
                            $teacher->setActif(true);
                            $em->persist($teacher);
                            $em->flush();
                            $p->addSitegroup($prof_role);
                            $em->persist($p);
                            $em->flush();
                        }
                    }

                    $student_role = $rep->findOneByName('ROLE_STUDENT');

                    if ( !empty($p->getStudent())){
                        if ( $student == 0){
                            // droits
                            if ( !in_array($student_role, $user_roles)){
                                $p->addSitegroup($student_role);
                            }
                            $p->getStudent()->setActif(true);
                            $em->persist($p);
                            $em->flush();
                        }
                        else{
                            // effacer + droits
                            foreach ( $user_roles as $role){
                                if ( $role->getName() == $student_role->getName()){
                                    $p->removeSitegroup($student_role);
                                }
                            }
                            $em->persist($p);
                            $em->flush();
                            $student = $p->getStudent();
                            $student->setActif(false);
                            $em->persist($student);
                            $em->flush();
                        }
                    }
                    else{
                        // creer prof + droits
                        if ( $student == 0){
                            $etudiant = new Student();
                            $etudiant->setPerson($p);
                            $etudiant->setActif(true);
                            $em->persist($etudiant);
                            $em->flush();
                            $p->addSitegroup($student_role);
                            $em->persist($p);
                            $em->flush();
                        }
                    }

                    /*
                    $message = \Swift_Message::newInstance()
                        ->setSubject("Votre compte est validé")
                        ->setFrom('mf.sercom@gmail.com')
                        ->setTo($person->getEmail())
                        ->setBody($this->renderView('SERCOMAppBundle:Email:accountvalid.html.twig', array('person' => $person)));
                    $this->get('swiftmailer.mailer.default')->send($message);*/

                    return $this->redirect($this->generateUrl('sercom_admin_users'));
                }
                return $this->render('SERCOMAppBundle:AdminUser:modifyuser.html.twig', array('form' => $form->createView(), 'person' => $p, 'd2' => $d2, 'tea' => $user_teacher_rights, 'stu' => $user_student_rights));
            }
            else{
                throw new NotFoundHttpException();
            }
        }
        else{
            throw new AccessDeniedException();
        }
    }

    public function banAction($id){
        $person = $this->get('security.context')->getToken()->getUser();
        if ( $person->isGranted('ROLE_PRESIDENT') or $person->isGranted('ADMIN_USER')){
            $rep = $this->getDoctrine()->getRepository('SERCOMAppBundle:Person');
            $p = $rep->find($id);
            if ( !empty($p)){
                try{
                    $p->setBan(true);
                    $roles = $p->getSitegroups();
                    foreach ( $roles as $role){
                        $p->removeSitegroup($role);
                    }
                    if ( !empty($p->getStudent())) $p->getStudent()->setActif(false);
                    if ( !empty($p->getTeacher())) $p->getTeacher()->setActif(false);
                    if ( !empty($p->getMember())) $p->getMember()->setActif(false);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($p);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('succes', 'L\'utilisateur a été bloqué');
                    $message = \Swift_Message::newInstance()
                        ->setSubject("Votre compte est banni")
                        ->setFrom('mf.sercom@gmail.com')
                        ->setTo($p->getEmail())
                        ->setBody($this->renderView('SERCOMAppBundle:Email:ban.html.twig'));
                    $this->get('swiftmailer.mailer.default')->send($message);
                }
                catch (\Exception $e){
                    $this->get('session')->getFlashBag()->add('error', 'Une erreur est survenue');
                }
                finally{
                    return $this->redirect($this->generateUrl('sercom_admin_users'));
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

    public function unbanAction($id){
        $person = $this->get('security.context')->getToken()->getUser();
        if ( $person->isGranted('ROLE_PRESIDENT') or $person->isGranted('ADMIN_USER')){
            $rep = $this->getDoctrine()->getRepository('SERCOMAppBundle:Person');
            $p = $rep->find($id);
            if ( !empty($p)){
                try{
                    $p->setBan(false);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($p);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('succes', 'L\'utilisateur a été débloqué');
                }
                catch (\Exception $e){
                    $this->get('session')->getFlashBag()->add('error', 'Une erreur est survenue');
                }
                finally{
                    return $this->redirect($this->generateUrl('sercom_admin_users'));
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

    public function modifyparamsAction($id, Request $request){
        $p = $this->get('security.context')->getToken()->getUser();
        if ( $p->isGranted('ROLE_PRESIDENT') or $p->isGranted('ADMIN_USER')){
            $rep = $this->getDoctrine()->getRepository('SERCOMAppBundle:Person');
            $person = $rep->find($id);
            if ( !empty($person)){
                $member = $person->getMember();
                $form = $this->createForm(new MemberType(), $member)->remove('actif')->remove('profstatus')->remove('person')->remove('asblfunctions')->add('save','submit');
                $form2 = $this->createForm(new PersonSiteGroupType(), $person);
                $form3 = $this->createForm(new MemberAsblFunctionType(), $member);
                $rep = $this->getDoctrine()->getRepository('SERCOMAppBundle:SiteGroup');
                $oldsitegroups = $rep->getRolesUser($person->getPersonId());

                if ($request->request->has($form->getName())) {
                    $form->handleRequest($request);
                    if ($form->isValid()) {
                        try {
                            $em = $this->getDoctrine()->getManager();
                            $em->persist($member);
                            $em->flush();
                            $this->get('session')->getFlashBag()->add('succes', 'Sauvegarde des changements effectuée');
                        } catch (\Exception $e) {
                            $this->get('session')->getFlashBag()->add('error', 'Une erreur est survenue');
                        } finally {
                            return $this->redirect($this->generateUrl('sercom_admin_users'));
                        }
                    }
                }


                if ($request->request->has($form2->getName())) {
                    $form2->handleRequest($request);
                    if ( $form2->isValid()){
                        try{
                            $em = $this->getDoctrine()->getManager();
                            $person->addSitegroup($oldsitegroups[0]);
                            $em->persist($person);
                            $em->flush();
                            $this->get('session')->getFlashBag()->add('succes', 'Sauvegarde des changements effectuée');
                        }
                        catch( \Exception $e){
                            $this->get('session')->getFlashBag()->add('error', 'Une erreur est survenue');
                        }
                        finally{
                             return $this->redirect($this->generateUrl('sercom_admin_users'));
                        }
                    }

                }

                if ($request->request->has($form3->getName())) {
                    $form3->handleRequest($request);
                    if ( $form3->isValid()){
                        try{
                            $em = $this->getDoctrine()->getManager();
                            $em->persist($member);
                            $em->flush();
                            //$this->get('session')->getFlashBag()->add('succes', 'Sauvegarde des changements effectuée');
                        }
                        catch( \Exception $e){
                            $this->get('session')->getFlashBag()->add('error', 'Une erreur est survenue');
                        }
                        finally{
                            return $this->redirect($this->generateUrl('sercom_admin_users'));
                        }
                    }
                }

                return $this->render('@SERCOMApp/AdminUser/modifymemberforum.html.twig', array('form' => $form->createView(), 'person' => $person, 'form2' => $form2->createView(),
                                                                                            'form3' => $form3->createView()));
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