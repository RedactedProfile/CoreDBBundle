<?php

namespace KH\CoreDBBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('KHCoreDBBundle:Default:index.html.twig', array('name' => $name));
    }
}
