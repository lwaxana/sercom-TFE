<?php


namespace SERCOM\AppBundle\Controller;



use SERCOM\AppBundle\Entity\ForumTopic;
use SERCOM\AppBundle\Entity\Member;
use SERCOM\AppBundle\Form\ForumPost3Type;
use SERCOM\AppBundle\Form\ForumPostAddTopicType;
use SERCOM\AppBundle\Form\ForumPostAddTopic2Type;
use SERCOM\AppBundle\Form\ForumPostType;
use SERCOM\AppBundle\Forum\IndexForum;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SERCOM\AppBundle\Entity\ForumPost;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class ForumController extends Controller{

    /*
     * INDEX TOTAL DU FORUM // AFFICHAGE DES FORUMS AUTORISES // RECUPERE TOPIC DU DERNIER MESSAGE
     * COMPTE POSTS ET TOPICS
     */
    public function indexAction(){

        $person = $this->get('security.context')->getToken()->getUser();
        $member = $person->getMember();
        $rep = $this->getDoctrine()->getManager()->getRepository('SERCOMAppBundle:Forum');
        $forums = $rep->getForums($member);
        $forums_list = array();
        foreach($forums as $forum){
            $val = $rep->getCountForumTopics($forum);
            $nbretopics = $val['COUNT(*)'];
            $posts = $rep->getOrdererPosts($forum);
            $nbreposts = count($posts);
            $rep2 = $this->getDoctrine()->getManager()->getRepository('SERCOMAppBundle:ForumPost');
            $lastpost = end($posts);

            if ( $lastpost != NULL){
                $post = $rep2->find($lastpost['post_id']);
                $topic = $post->getForumtopic();
                $title = trim($topic->getTitle());
                if ( strlen($title) > 40){
                    $title = substr($title,0,40);
                    $title .= "...";
                    $topic->setTitle($title);
                }
                $post_member = $post->getMember();
            }
            else{
                $post = new ForumPost();
                $topic = new ForumTopic();
                $post_member = new Member();
            }
            $f_index = new IndexForum($forum, $topic, $post, $post_member, $nbretopics, $nbreposts);
            array_push($forums_list, $f_index);
        }
        return $this->render('SERCOMAppBundle:Forum:index.html.twig', array('forums' => $forums_list));
    }

    /**
     * INDEX TOPIC // AFFICHAGE DES TOPICS ( PAR TYPE ) + DERNIER MESSAGE + NBRE REPOSES
     */
    public function topicAction($forum, $page){

        $rep = $this->getDoctrine()->getManager()->getRepository('SERCOMAppBundle:Forum');
        $f = $rep->find($forum);
        if ( !empty($f)){
            $person = $this->get('security.context')->getToken()->getUser();
            $rep = $this->getDoctrine()->getManager()->getRepository('SERCOMAppBundle:Forum');
            $fs = $rep->getForums($person->getMember());

            if ( in_array($f, $fs)){
                $max_per_page = $this->container->getParameter('max_topics_per_page');;
                $rep3 = $this->getDoctrine()->getManager()->getRepository('SERCOMAppBundle:ForumPost');
                $topic_p1_list = $rep3->getLastPosts($forum, 1);
                $topic_p2_list = $rep3->getLastPosts($forum, 2);
                $topic_p3_list = $rep3->getLastPostsPage($forum, $page, $max_per_page);

                $posts_count = $rep3->countTopics($forum) ;
                $posts_count = $posts_count[0];

                $pagination = array(
                    'page' => $page,
                    'route' => 'sercom_members_forum_topics',
                    'pages_count' => ceil($posts_count / $max_per_page),
                    'route_params' => array('forum' => $forum, 'page'=>$page)
                );

                return $this->render('SERCOMAppBundle:Forum:forumtopics.html.twig', array('posts_p1' => $topic_p1_list,
                    'postss_p2' => $topic_p2_list,
                    'posts_p3' => $topic_p3_list,
                    'forum' => $f, 'pagination' => $pagination
                ));
            }
            else{
                throw new AccessDeniedHttpException();
            }
        }
        else{
            throw new NotFoundHttpException();
        }



    }

    /**
     * INDEX POST // AFFICHAGE PAR DATE POSTS + FORMULAIRE REPONSE
     */
    public function postAction($forum, $topic, $page, Request $request){

        $rep = $this->getDoctrine()->getManager()->getRepository('SERCOMAppBundle:Forum');
        $f = $rep->find($forum);

        if (!empty($f)){
            $person = $this->get('security.context')->getToken()->getUser();
            $rep = $this->getDoctrine()->getManager()->getRepository('SERCOMAppBundle:Forum');
            $fs = $rep->getForums($person->getMember());
            if ( in_array($f, $fs)){

                $post = new ForumPost();
                $form = $this->createForm(new ForumPostType(), $post)
                    ->remove('datePost')->remove('member')->remove('moderated')->remove('forumtopic');
                $rep = $this->getDoctrine()->getManager()->getRepository('SERCOMAppBundle:ForumTopic');
                $topic = $rep->find($topic);

                $maxposts = $this->container->getParameter('max_posts_per_page');

                $rep = $this->getDoctrine()->getManager()->getRepository('SERCOMAppBundle:ForumPost');
                $posts = $rep->getOrdererPosts($topic->getTopicid(), $page, $maxposts);

                $posts_count = $rep->countPosts($topic);
                $posts_count = $posts_count[0];

                $pagination = array(
                    'page' => $page,
                    'route' => 'sercom_members_forum_posts',
                    'pages_count' => ceil($posts_count / $maxposts),
                    'route_params' => array('forum' => $f->getForumId(), 'topic' => $topic->getTopicId())
                );

                $form->handleRequest($request);

                if ($form -> isValid() ){
                    $post->setPostContent(str_replace("&nbsp;", "<br/>", $post->getPostContent()));
                    $post->setMember($person->getMember());
                    $post->setModerated(false);
                    $post->setForumtopic($topic);
                    $post->setDatePost(new \DateTime());
                    try{
                        $em = $this->getDoctrine()->getManager();
                        $em->persist($post);
                        $em->flush();
                        $this->get('session')->getFlashBag()->add('succes', 'Ajout du topic effectué ');
                    }catch (\Exception $e){
                        $this->get('session')->getFlashBag()->add('error', 'Une erreur s\'est produite ');
                    }
                    finally{
                        return $this->redirect($this->generateUrl('sercom_members_forum_posts', array('forum' => $f->getForumid(), 'topic' => $topic->getTopicid(), 'pagination' => $pagination )));
                    }
                }
                return $this->render('SERCOMAppBundle:Forum:forumposts.html.twig', array('forum' => $f, 'topic' => $topic, 'posts' => $posts, 'form'=>$form->createView(), 'pagination' => $pagination));
            }
            else{
                return new AccessDeniedHttpException();
            }
        }
        else{
            throw new NotFoundHttpException();
        }


    }

    public function editpostAction($post, Request $request){
        $rep = $this->getDoctrine()->getManager()->getRepository('SERCOMAppBundle:ForumPost');
        $p = $rep->find($post);
        if ( !empty($p)){
            $person = $this->get('security.context')->getToken()->getUser();
            $groups =  $person->getRoles();
            if ( in_array("MODERATION_FORUM", $groups) || $p->getMember() == $person->getMember() ) {
                $form = $this->createForm(new ForumPost3Type(), $p);
                if ($request->isMethod('POST')) {
                    $form->handleRequest($request);
                    if ($form->isValid()) {
                        if ( $p->getMember() == $person->getMember() ){
                            $p->setPostcontent(str_replace("&nbsp;", "<br/>", $p->getPostContent()));
                        }
                        else{
                            $p->setModerated(true);
                            $p->setPostcontent(str_replace("&nbsp;", "<br/>", $p->getPostContent()));
                            $p->setPostcontent( $p->getPostContent() . " Modéré par ".$person->getLastname()." ".$person->getFirstname());
                        }
                        try{
                            $em = $this->getDoctrine()->getManager();
                            $em->persist($p);
                            $em->flush();
                            $this->get('session')->getFlashBag()->add('succes', 'Sauvegarde du post effectué ');
                        }
                        catch (\Exception $e){
                            $this->get('session')->getFlashBag()->add('error', 'Une erreur s\'est produite ');
                        }
                        finally{
                            return $this->redirect($this->generateUrl('sercom_members_forum_posts', array(  'forum' => $p->getForumTopic()->getForum()->getForumid(),
                                                                                                            'topic' => $p->getForumTopic()->getTopicid() )));
                        }
                    }
                }
                return $this->render('SERCOMAppBundle:Forum:forumeditpost.html.twig', array('form' => $form->createView(), 'post' => $p));
            }
            else{
                throw new AccessDeniedHttpException();
            }
        }
        else{
            throw new NotFoundHttpException();
        }
    }

    public function addtopicAction($forum, Request $request){

        $rep = $this->getDoctrine()->getManager()->getRepository('SERCOMAppBundle:Forum');
        $f = $rep->find($forum);

        if ( !empty($f)){
            $person = $this->get('security.context')->getToken()->getUser();
            $rep = $this->getDoctrine()->getManager()->getRepository('SERCOMAppBundle:Forum');
            $fs = $rep->getForums($person->getMember());
            if ( in_array($f, $fs)){

                $post = new ForumPost();
                $groups =  $person->getRoles();
                if ( in_array("MODERATION_FORUM", $groups)){
                    $form = $this->createForm(new ForumPostAddTopicType(), $post);
                }
                else{
                    $form = $this->createForm(new ForumPostAddTopic2Type(), $post);
                }
                $form->handleRequest($request);
                if ( $request->isMethod('POST')){
                    if ($form -> isValid() ){

                        $post->setMember($person->getMember());
                        $post->setModerated(false);
                        $post->setDatePost(new \DateTime());

                        $topic = $post->getForumtopic();
                        $topic->setDateTopic(new \DateTime());
                        $topic->setForum($f);
                        $topic->setMember($person->getMember());

                        if ( !in_array("MODERATION_FORUM", $groups) ){
                            $topic->setPriority(3);
                            $topic->setLocked(false);
                        }

                        $em = $this->getDoctrine()->getManager();
                        try{
                            $em->persist($topic);
                            $em->flush();
                            $em->persist($post);
                            $em->flush();
                            $this->get('session')->getFlashBag()->add('succes', 'Ajout du topic effectué ');
                        }
                        catch(\Exception $e){
                            $this->get('session')->getFlashBag()->add('error', 'Une erreur s\'est produite ');
                        }
                        finally{
                            return $this->redirect($this->generateUrl('sercom_members_forum_topics', array('forum' =>$f->getForumId())));
                        }
                    }
                }
                return $this->render('SERCOMAppBundle:Forum:addtopic.html.twig', array('form' => $form->createView(), 'forum' =>$f));
            }
            else{
                throw new AccessDeniedHttpException();
            }
        }
        else{
            throw new NotFoundHttpException();
        }
    }

    public function locksAction($topic){
        $rep = $rep = $this->getDoctrine()->getManager()->getRepository('SERCOMAppBundle:ForumTopic');
        $t = $rep->find($topic);
        if (!empty($t)){
            $person = $this->get('security.context')->getToken()->getUser();
            $groups =  $person->getRoles();
            if ( in_array("MODERATION_FORUM", $groups) ){
                if ( $t->getLocked()){
                    $t->setLocked(false);
                }
                else{
                    $t->setLocked(true);
                }
                try{
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($t);
                    $em->flush();
                    if ( $t->getLocked()){
                        $this->get('session')->getFlashBag()->add('succes', 'Sujet verouillé ');
                    }
                    else{
                        $this->get('session')->getFlashBag()->add('succes', 'Sujet déverouillé');
                    }

                }
                catch(\Exception $e){
                    $this->get('session')->getFlashBag()->add('error', 'Une erreur s\'est produite ');
                }
                finally{
                    return $this->redirect($this->generateUrl('sercom_members_forum_topics', array('forum' => $t->getForum()->getForumid())));
                }
            }
            else{
                throw new AccessDeniedHttpException();
            }
        }
        else{
            throw new NotFoundHttpException();
        }
    }

    public function deleteTopicAction($topic){
        $rep = $rep = $this->getDoctrine()->getManager()->getRepository('SERCOMAppBundle:ForumTopic');
        $t = $rep->find($topic);
        if (!empty($t)){
            $person = $this->get('security.context')->getToken()->getUser();
            $groups =  $person->getRoles();
            if ( in_array("MODERATION_FORUM", $groups) ){
                try{
                    $em = $this->getDoctrine()->getManager();
                    $posts = $t->getForumposts();
                    foreach( $posts as $post){
                        $em->remove($post);
                        $em->flush();
                    }
                    $em->remove($t);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('succes', 'Sujet supprimé');
                }
                catch(\Exception $e){
                    $this->get('session')->getFlashBag()->add('error', 'Une erreur s\'est produite ');
                }
                finally{
                    $f = $t->getForum();
                    return $this->redirect($this->generateUrl('sercom_members_forum_topics', array('forum' => $f->getForumid())));
                }
            }
            else{
                throw new AccessDeniedHttpException();
            }
        }
        else{
            throw new NotFoundHttpException();
        }
    }




} 