<?php
/**
 * Created by PhpStorm.
 * User: Lwaxana
 * Date: 17/05/2015
 * Time: 17:53
 */

namespace SERCOM\AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TeachersController extends Controller{

    public function indexAction(){
        return $this->render('@SERCOMApp/Teachers/index.html.twig');
    }

} 