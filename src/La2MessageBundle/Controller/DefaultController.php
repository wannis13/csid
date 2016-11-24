<?php

namespace La2MessageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('La2MessageBundle:Default:index.html.twig', array('name' => $name));
    }
}
