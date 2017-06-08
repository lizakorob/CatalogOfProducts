<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\UserLoginType;
use AppBundle\Form\UserRegistrationType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        $user = new User();
        $loginForm = $this->createForm(UserLoginType::class, $user);
        $registerForm = $this->createForm(UserRegistrationType::class, $user);
//        $form->add('submit', SubmitType::class, array(
//                'label' => 'Create',
//                'attr'  => array('class' => 'btn btn-default pull-right')));
        return $this->render('default/index.html.twig', array(
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'loginForm' => $loginForm->createView(), 'registerForm' => $registerForm->createView(),
        ));
    }
}
