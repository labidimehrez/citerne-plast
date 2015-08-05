<?php

namespace MyApp\BackofficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class adminController extends Controller
{
    public function indexAction()
    {
        return $this->render('MyAppBackofficeBundle:admin:index.html.twig');
    }
    
     public function formAction()
    {
        return $this->render('MyAppBackofficeBundle:admin:form.html.twig');
    }
    
     public function chartAction()
    {
        return $this->render('MyAppBackofficeBundle:admin:chart.html.twig');
    }
    
     public function loginAction()
    {
        return $this->render('MyAppBackofficeBundle:admin:login.html.twig');
    }
    
    public function registerAction()
    {
        return $this->render('MyAppBackofficeBundle:admin:register.html.twig');
    }
     
    
     public function tableAction()
    {
        return $this->render('MyAppBackofficeBundle:admin:table.html.twig');
    }
    
    
     public function uiAction()
    {
        return $this->render('MyAppBackofficeBundle:admin:ui.html.twig');
    }
    
     public function tabpanelAction()
    {
        return $this->render('MyAppBackofficeBundle:admin:tabpanel.html.twig');
    }
    
    
    
    
    
    
    
    
}
