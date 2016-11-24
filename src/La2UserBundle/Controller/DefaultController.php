<?php

namespace La2UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('La2UserBundle:Default:index.html.twig', array('name' => $name));
    }
}
