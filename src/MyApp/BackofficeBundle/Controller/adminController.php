<?php

namespace MyApp\BackofficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class adminController extends Controller
{
    public function indexAction()
    {
        return $this->render('MyAppBackofficeBundle:admin:index.html.twig');
    }
}
