<?php

namespace MyApp\FrontofficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('MyAppFrontofficeBundle:client:index.html.twig');
    }
    
    
     public function authentificationAction()
    {
        return $this->render('MyAppFrontofficeBundle:client:authentification.html.twig');
    }
    
    
     public function aboutAction()
    {
        return $this->render('MyAppFrontofficeBundle:client:about.html.twig');
    }
    
}
