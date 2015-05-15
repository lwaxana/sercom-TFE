<?php
/**
 * Created by PhpStorm.
 * User: Lwaxana
 * Date: 15/04/2015
 * Time: 12:45
 */

namespace SERCOM\AppBundle\Controller;


use SERCOM\AppBundle\Form\DocumentSearchType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


class DocumentController extends Controller {

    public function indexAction(Request $request){

        if ( $this->get('security.context')->isGranted('ROLE_PRESIDENT') or $this->get('security.context')->isGranted('ROLE_ANIMATEUR') or
            $this->get('security.context')->isGranted('ROLE_COMITE') or $this->get('security.context')->isGranted('ROLE_MEMBRE') ){

            $rep = $this->getDoctrine()->getManager()->getRepository('SERCOMAppBundle:Person');
            $role = $rep->getRoleUser($this->getUser()->getPersonId());

            $rep = $this->getDoctrine()->getManager()->getRepository('SERCOMAppBundle:AsblDocumentCat');
            $docs = $rep->getByRole($role[0]->getName());


            $form = $this->createForm(new DocumentSearchType());
            $form->handleRequest($request);
            if ( $form->isValid()){
                $data = $form->getData();
                $search = $data['search'];
                $docs = null;
                $rep = $this->getDoctrine()->getRepository('SERCOMAppBundle:AsblDocument');
                $docs = $rep->search($search, $role[0]->getName());
                $form = $this->createForm(new DocumentSearchType());
                return $this->render('SERCOMAppBundle:Document:searchresult.html.twig', array('docs' => $docs, 'form' => $form->createView()));
            }

            return $this->render('SERCOMAppBundle:Document:index.html.twig', array('docs' => $docs, 'form' => $form->createView()));
        }

        else{
            throw new AccessDeniedException();
        }


    }

    public function filesAction($id, $page){

        $rep = $this->getDoctrine()->getManager()->getRepository('SERCOMAppBundle:AsblDocumentSousCat');
        $souscat = $rep->find($id);

        if ( !empty($souscat)){
            if ( $this->get('security.context')->isGranted($souscat->getCategory()->getSitegroup()->getName()) ){
                $max_per_page = 10;
                $rep = $this->getDoctrine()->getManager()->getRepository('SERCOMAppBundle:AsblDocument');
                $docs = $rep->getBySouscat($id, $page, $max_per_page);

                $docs_count = $rep->countDocsSousCat($id);

                $pagination = array(
                    'page' => $page,
                    'route' => 'sercom_members_docs_files',
                    'pages_count' => ceil($docs_count / $max_per_page),
                    'route_params' => array('id' => $id, 'page' => $page)
                );

                return $this->render('SERCOMAppBundle:Document:files.html.twig', array('docs' => $docs, 'souscat' => $souscat, 'pagination' => $pagination));
            }
            else{
                throw new AccessDeniedHttpException();
            }
        }
        else{
            throw new NotFoundHttpException();
        }
    }

    public function catAction($id){
        $rep = $this->getDoctrine()->getManager()->getRepository('SERCOMAppBundle:AsblDocument');
        $docs = $rep->findBySouscat($id);
        $rep = $this->getDoctrine()->getManager()->getRepository('SERCOMAppBundle:AsblDocumentCat');
        $cat = $rep->find($id);
        if ( $this->get('security.context')->isGranted($cat->getSitegroup()->getName()) ){
            return $this->render('SERCOMAppBundle:Document:files.html.twig', array('docs' => $docs));
            }
            else{
                throw new AccessDeniedHttpException();
            }


    }

} 