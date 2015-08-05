<?php

namespace MyApp\BackofficeBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class adminController extends Controller {

    public function indexAction(Request $request) {
        return $this->render('MyAppBackofficeBundle:admin:index.html.twig');
    }

    public function formAction(Request $request) {
        return $this->render('MyAppBackofficeBundle:admin:form.html.twig');
    }

    public function chartAction(Request $request) {
        return $this->render('MyAppBackofficeBundle:admin:chart.html.twig');
    }

    public function loginAction(Request $request) {

        $form = $this->createFormBuilder()
                ->add('identifiant', 'text')
                ->add('password', 'password')
                ->getForm();
        return $this->render('MyAppBackofficeBundle:admin:login.html.twig', array(
                    'form' => $form->createView(),
        ));
    }

    public function logoutAction(Request $request) {
        //    détruire la session ici
        return $this->redirect($this->generateUrl('my_app_backoffice_login'));
    }

    public function registerAction(Request $request) {

        $form = $this->createFormBuilder()
                ->add('identifiant', 'text')
                ->add('password', 'password')
                ->getForm();
        return $this->render('MyAppBackofficeBundle:admin:register.html.twig', array(
                    'form' => $form->createView(),
        ));
    }

    public function tableAction(Request $request) {
        return $this->render('MyAppBackofficeBundle:admin:table.html.twig');
    }

    public function uiAction(Request $request) {
        return $this->render('MyAppBackofficeBundle:admin:ui.html.twig');
    }

    public function tabpanelAction(Request $request) {
        return $this->render('MyAppBackofficeBundle:admin:tabpanel.html.twig');
    }

}
