<?php
/**
 * Created by PhpStorm.
 * User: Lwaxana
 * Date: 03/05/2015
 * Time: 21:36
 */

namespace SERCOM\AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AdminArticleController extends Controller{

    public function indexAction(){
        $person = $this->get('security.context')->getToken()->getUser();
        if ( $person->isGranted('ROLE_PRESIDENT') or $person->isGranted('ADMIN_ARTICLES')){
            $rep = $this->getDoctrine()->getRepository('SERCOMAppBundle:SiteArticle');
            $articles_soumis = $rep->findBy(array('submit' => true, 'publish' => false));
            $articles_publie = $rep->findBy(array('publish' => true));
            return $this->render('@SERCOMApp/AdminArticle/index.html.twig', array('articles_soumis' => $articles_soumis, 'articles_publie' => $articles_publie));
        }
        else{
            throw new AccessDeniedException();
        }
    }

    public function publierAction($id){
        $person = $this->get('security.context')->getToken()->getUser();
        if ( $person->isGranted('ROLE_PRESIDENT') or $person->isGranted('ADMIN_ARTICLES')){
            $rep = $this->getDoctrine()->getRepository('SERCOMAppBundle:SiteArticle');
            $article = $rep->find($id);
            if ( !empty($article)){
                $rep = $this->getDoctrine()->getRepository('SERCOMAppBundle:ArticleDocument');
                $article->setPublish(true);
                $article->setPublishDate(new \DateTime());
                $doc = $rep->findOneBy(array('article' => $id, 'picture' => false));
                $text = file_get_contents(realpath($doc->getUrl()));
                $text_clean = substr(strip_tags($text), 0, 50);
                $article->setPreview($text_clean);
                $em = $this->getDoctrine()->getManager();
                $em->persist($article);
                $em->flush();
                $this->get('session')->getFlashBag()->add('succes', 'Article publié');
                return $this->redirect($this->generateUrl('sercom_admin_article_index'));
            }
            else{
                throw new NotFoundHttpException();
            }
        }
        else{
            throw new AccessDeniedException();
        }
    }

    public function refuserAction($id){
        $person = $this->get('security.context')->getToken()->getUser();
        if ( $person->isGranted('ROLE_PRESIDENT') or $person->isGranted('ADMIN_ARTICLES')){
            $rep = $this->getDoctrine()->getRepository('SERCOMAppBundle:SiteArticle');
            $article = $rep->find($id);
            if ( !empty($article)){
                $article->setSubmit(false);
                $article->setSubmitDate(NULL);
                $em = $this->getDoctrine()->getManager();
                $em->persist($article);
                $em->flush();
                $this->get('session')->getFlashBag()->add('succes', 'Article retourné');
                return $this->redirect($this->generateUrl('sercom_admin_article_index'));
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