<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
   /* public function indexAction()
    {
        return new Response('Main page');
    }*/

    /**
     * @Route("/register", name="register")
     */
    public function registerAction()
    {
        return new Response('Register page');
    }
}
