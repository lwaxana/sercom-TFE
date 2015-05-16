<?php
/**
 * Created by PhpStorm.
 * User: Lwaxana
 * Date: 20/04/2015
 * Time: 14:14
 */

namespace SERCOM\AppBundle\Controller;


use SERCOM\AppBundle\Entity\ArticleDocument;
use SERCOM\AppBundle\Entity\SiteArticle;
use SERCOM\AppBundle\Form\SiteArticle2Type;
use SERCOM\AppBundle\Form\SiteArticle3Type;
use SERCOM\AppBundle\Utils\SpecialChar;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ArticleController extends Controller {

    public function indexAction(){
        $person = $this->get('security.context')->getToken()->getUser();
        $member = $person->getMember();
        $rep = $this->getDoctrine()->getRepository('SERCOMAppBundle:SiteArticle');
        $articles = $rep->findBy(array('member' => $member,'submit' => false));
        $articlesoumis = $rep->findBy(array('member' => $member,'submit' => true));
        return $this->render('SERCOMAppBundle:Article:index.html.twig', array('articles' => $articles, 'articlesoumis' => $articlesoumis));
    }

    public function creerArticleAction(Request $request){
        $article = new SiteArticle();
        $form = $this->createForm( new SiteArticle2Type(), $article);
        $form->handleRequest($request);

        if ( $form->isValid()){
            try{
                $person = $this->get('security.context')->getToken()->getUser();
                $member = $person->getMember();
                $article->setMember($member);
                $article->setPublish(false);
                $article->setSubmit(false);
                $docs = $article->getDocuments();
                $nom_article = SpecialChar::removeChar($article->getTitle());
                $nom_article = SpecialChar::removeSpace($nom_article);
                $pathpics = $this->get('kernel')->getRootDir() . '/../web/bundles/AppBundle/images/articles/'.$nom_article;
                if (!file_exists($pathpics)) mkdir($pathpics);
                if ( !empty($docs)){
                    foreach ($docs as $doc) {
                        if ( !empty($doc) && $doc != null ) {
                            $pathweb = 'images/articles/'.$nom_article;
                            $file = $doc->getFile();
                            $new_name = sha1(uniqid(mt_rand(), true)).'.'.$file->guessExtension();
                            $file->move($pathpics, $new_name);
                            $doc->setArticle($article);
                            $doc->setPicture(true);
                            $doc->setName($file->getClientOriginalName());
                            $doc->setUrl($pathweb ."/". $new_name);
                        }
                    }
                }

                $patharticle = $this->get('kernel')->getRootDir().'/../src/articles/';
                $data = $request->get($form->getName());
                $file_name = $patharticle.$nom_article.".html";
                $file = fopen( $file_name, 'a');
                fputs($file, $data['content']);
                fclose($file);

                $d = new ArticleDocument();
                $d->setArticle($article);
                $d->setName($nom_article);
                $d->setPicture(false);
                $d->setUrl($file_name);

                $article->addDocument($d);
                $em = $this->getDoctrine()->getManager();
                $em->persist($article);
                $em->flush();
                $this->get('session')->getFlashBag()->add('succes', 'Enregistrement effectué');
            }
            catch(\Exception $e){
                $this->get('session')->getFlashBag()->add('error', 'Une erreur est survenue');
            }
            finally{
                return $this->redirect($this->generateUrl('sercom_members_article'));
            }
        }
        return $this->render('SERCOMAppBundle:Article:creerarticle.html.twig', array('form' => $form->createView()));
    }

    public function modifierArticleAction($id, Request $request){
        $rep = $this->getDoctrine()->getRepository('SERCOMAppBundle:SiteArticle');
        $article = $rep->find($id);
        if ( !empty($article)){
            $person = $this->get('security.context')->getToken()->getUser();
            if ( $person->getMember() == $article->getMember()){
                $rep = $this->getDoctrine()->getRepository('SERCOMAppBundle:ArticleDocument');
                $doc = $rep->findOneBy(array('article' => $id, 'picture' => false));
                $docs = $rep->findBy((array('article' => $id, 'picture' => true)));
                $text = file_get_contents(realpath($doc->getUrl()));
                $form = $this->createForm(new SiteArticle3Type(), $article, array('attr' => array('texte' => $text)));
                $form->handleRequest($request);
                if ( $form->isValid()){
                    try{
                        $em = $this->getDoctrine()->getManager();
                        $nom_article = SpecialChar::removeChar($article->getTitle());
                        $new_nom_article = SpecialChar::removeSpace($nom_article);
                        $tab = explode("/", $doc->getUrl() );
                        $old_nom = $tab[8];
                        $old_nom = explode('.', $old_nom);
                        $old_nom = $old_nom[0];
                        if ( ($old_nom != $new_nom_article) ){
                            $new_path_article = $this->get('kernel')->getRootDir().'/../src/articles/'.$new_nom_article.'.html';
                            $old_path_article = $doc->getUrl();
                            rename( realpath($old_path_article), '../src/articles/'.$new_nom_article.'.html');
                            $doc->setUrl($new_path_article);
                            $doc->setName($new_nom_article);

                            $em->persist($doc);
                            $em->flush();
                            $new_path_pics = $this->get('kernel')->getRootDir() . '/../web/bundles/AppBundle/images/articles/'.$new_nom_article;
                            mkdir($new_path_pics);
                            $rootpath = $this->get('kernel')->getRootDir().'/../web/bundles/AppBundle/';
                            foreach( $docs as $d){
                                $old_pic_path = $d->getUrl();
                                $new_pic_path = str_replace($old_nom, $new_nom_article, $old_pic_path);
                                copy ( $rootpath.$old_pic_path, $rootpath.$new_pic_path );
                                unlink( $rootpath.$old_pic_path);
                                $d->setUrl($new_pic_path);
                                $em->persist($doc);
                                $em->flush();
                            }
                            $todelete = $rootpath.'images/articles/'.$old_nom;
                            rmdir($todelete);
                            $em->persist($article);
                            $em->flush();
                        }

                        $file = fopen($doc->getUrl(), 'w');
                        fputs($file, $form->get('content')->getData());
                        fclose($file);

                        $docs = $form->get('documents')->getData();
                        $pathpics = $this->get('kernel')->getRootDir() . '/../web/bundles/AppBundle/images/articles/'.$new_nom_article;
                        if (!file_exists($pathpics)) mkdir($pathpics);
                        if ( !empty($docs)){
                            foreach ($docs as $doc) {
                                if ( !empty($doc) && $doc != null ) {
                                    $pathweb = 'images/articles/'.$new_nom_article;
                                    $file = $doc->getFile();
                                    $new_name = sha1(uniqid(mt_rand(), true)).'.'.$file->guessExtension();
                                    $file->move($pathpics, $new_name);
                                    $doc->setArticle($article);
                                    $doc->setPicture(true);
                                    $doc->setName($file->getClientOriginalName());
                                    $doc->setUrl($pathweb ."/". $new_name);
                                    $em->persist($doc);
                                    $em->flush();
                                }
                            }
                        }

                        $em->persist($article);
                        $em->flush();
                        $this->get('session')->getFlashBag()->add('succes', 'Enregistrement effectué');
                    }
                    catch(\Exception $e){
                        $this->get('session')->getFlashBag()->add('error', 'Une erreur est survenue');
                    }
                    finally{
                        return $this->redirect($this->generateUrl('sercom_members_article'));
                    }

                }
                return $this->render('@SERCOMApp/Article/modifier.html.twig', array('article' => $article, 'form' => $form->createView(), 'docs' => $docs));
            }
            else{
                throw new AccessDeniedException();
            }
        }
        else{
            throw new NotFoundHttpException();
        }

    }


    public function deleteArticleAction($id){
        $rep = $this->getDoctrine()->getRepository('SERCOMAppBundle:SiteArticle');
        $article = $rep->find($id);
        if (!empty($article)){

            $person = $this->get('security.context')->getToken()->getUser();
            if ( $person->getMember() == $article->getMember()){
                try{
                    $em = $this->getDoctrine()->getManager();
                    $docs = $article->getDocuments();
                    $nom_article = SpecialChar::removeChar($article->getTitle());
                    $nom_article = SpecialChar::removeSpace($nom_article);
                    foreach ( $docs as $doc){
                        if ( $doc->getPicture()){
                            $pathpics = $this->get('kernel')->getRootDir() . '/../web/bundles/AppBundle/'. $doc->getUrl();
                            unlink(realpath($pathpics));
                        }
                        else{
                            unlink(realpath($doc->getUrl()));
                        }
                    }
                    rmdir(realpath($this->get('kernel')->getRootDir() . '/../web/bundles/AppBundle/images/articles/'.$nom_article));
                    $em->remove($article);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('succes', 'Article supprimé');
                }
                catch(\Exception $e){
                    $this->get('session')->getFlashBag()->add('error', 'Une erreur est survenue');
                }
                finally{
                    return $this->redirect($this->generateUrl('sercom_members_article'));
                }
            }
            else{
                throw new AccessDeniedException();
            }
        }
        else{
            throw new NotFoundHttpException();
        }
    }

    public function submitArticleAction($id){
        $rep = $this->getDoctrine()->getRepository('SERCOMAppBundle:SiteArticle');
        $article = $rep->find($id);
        if (!empty($article)){
            $person = $this->get('security.context')->getToken()->getUser();
            if ( $person->getMember() == $article->getMember()){
                try{
                    $article->setSubmit(true);
                    $article->setSubmitDate(new \DateTime());
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($article);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('succes', 'Article correctement soumis');
                }
                catch(\Exception $e){
                    $this->get('session')->getFlashBag()->add('error', 'Une erreur est survenue');
                }
                finally{
                    return $this->redirect($this->generateUrl('sercom_members_article'));
                }
            }
            else{
                throw new AccessDeniedException();
            }
        }
        else{
            throw new NotFoundHttpException();
        }
    }

    public function viewArticleAction($id){
        $rep = $this->getDoctrine()->getRepository('SERCOMAppBundle:SiteArticle');
        $article = $rep->find($id);
        if ( !empty($article)){
            $rep = $this->getDoctrine()->getRepository('SERCOMAppBundle:ArticleDocument');
            $doc = $rep->findOneBy(array('article' => $id, 'picture' => false));
            $docs = $rep->findBy((array('article' => $id, 'picture' => true)));
            $text = file_get_contents(realpath($doc->getUrl()));
            return $this->render('@SERCOMApp/Article/view.html.twig', array('article' => $article, 'text'=>$text, 'pics' => $docs));
        }

    }

    public function deletedocArticleAction($id){
        $rep = $this->getDoctrine()->getRepository('SERCOMAppBundle:ArticleDocument');
        $doc = $rep->find($id);
        if ( !empty($doc)){
            $person = $this->get('security.context')->getToken()->getUser();
            if ( $person->getMember() == $doc->getArticle()->getMember()){
                try{
                    $em = $this->getDoctrine()->getManager();
                    $article = $doc->getArticle();
                    if ( $doc->getPicture()){

                        $pathpics = $this->get('kernel')->getRootDir() . '/../web/bundles/AppBundle/'. $doc->getUrl();
                        unlink($pathpics);
                        $article->removeDocument($doc);
                        $em->remove($doc);
                        $em->flush();

                    }
                    else{
                        unlink(realpath($doc->getUrl()));
                        $article->removeDocument($doc);
                        $em->remove($doc);
                        $em->flush();
                    }
                    $this->get('session')->getFlashBag()->add('succes', 'Enregistrement effectué');
                }
                catch(\Exception $e){
                    $this->get('session')->getFlashBag()->add('error', 'Une erreur est survenue');
                }
                finally{
                    return $this->redirect($this->generateUrl('sercom_members_article_modify', array('id' => $article->getArticleId())));
                }
            }
            else{
                throw new AccessDeniedException();
            }
        }
        else{
            throw new NotFoundHttpException();
        }
    }

    public function tagsAction(Request $request){
        if($request->isXmlHttpRequest())
        {
            $tag = $request->query->get('tag');
            $em = $this->getDoctrine()->getManager();
            $datas = $em->getRepository('SERCOMAppBundle:Tag')->autocomplete($tag);
            return new JsonResponse($datas);
        }
    }

} 