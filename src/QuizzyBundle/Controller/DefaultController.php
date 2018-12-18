<?php

namespace QuizzyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/index")
     */
    public function indexAction()
    {
    	var_dump("ok"); exit;
        return $this->render('QuizzyBundle:Default:index.html.twig');
    }
}
