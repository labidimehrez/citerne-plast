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
    
       public function contactAction()
    {
        return $this->render('MyAppFrontofficeBundle:client:contact.html.twig');
    }
    
    
          public function blogAction()
    {
        return $this->render('MyAppFrontofficeBundle:client:blog.html.twig');
    }
    
   
          public function faqAction()
    {
        return $this->render('MyAppFrontofficeBundle:client:faq.html.twig');
    }
    
          public function termsAction()
    {
        return $this->render('MyAppFrontofficeBundle:client:terms.html.twig');
    }
    
    
          public function checkoutAction()
    {
        return $this->render('MyAppFrontofficeBundle:client:checkout.html.twig');
    }
    
            public function cartAction()
    {
        return $this->render('MyAppFrontofficeBundle:client:cart.html.twig');
    }
    
}
