<?php

namespace MyApp\FrontofficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('MyAppFrontofficeBundle:client:index.html.twig');
    }
}
