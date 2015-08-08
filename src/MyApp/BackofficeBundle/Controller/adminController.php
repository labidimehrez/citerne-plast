<?php

namespace MyApp\BackofficeBundle\Controller;

use Symfony\Component\HttpFoundation\Session\Session;
use MyApp\UtilisateurBundle\Entity\Utilisateur;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class adminController extends Controller {

    public function indexAction(Request $request) {

        /*
          if ($this->getRequest()->getSession()->get('user') == NULL) {
          return $this->redirect($this->generateUrl('my_app_backoffice_login'));
          }

          if(!($this->getRequest()->getSession()->get('user')) && ($this->getRequest()->getSession()->get('user')->getPrivilege() = 'ADMIN'))
          {return $this->render('MyAppBackofficeBundle:admin:index.html.twig');}

          else {return $this->redirect($this->generateUrl('my_app_backoffice_login'));}
         */

        $userSession = $this->getRequest()->getSession()->get('user');
        if ((!empty($userSession))) {
            if ($userSession->getPrivilege() === 'ADMIN') {
                return $this->render('MyAppBackofficeBundle:admin:index.html.twig');
            }
            if ($userSession->getPrivilege() != 'ADMIN') {
                return $this->redirect($this->generateUrl('my_app_backoffice_login'));
            }
        } else {
            return $this->redirect($this->generateUrl('my_app_backoffice_login'));
        }
    }

    public function loginAction(Request $request) {

        $form = $this->createFormBuilder()
                ->add('identifiant', 'text')
                ->add('password', 'password')
                ->getForm();
        $identifiant = $this->getRequest()->get('identifiant');
        $password = $this->getRequest()->get('password');
      // var_dump($identifiant);var_dump($password);
        $manager = $this->get('collectify_security_manager');
        $authentifsucces = $manager->login($identifiant, $password);
        // $user = $manager->login($identifiant, $password);
        $user = $manager->getUserByPassword($password);
       // var_dump($authentifsucces);var_dump($user);exit;
        if (($authentifsucces == TRUE) && ($user != NULL)) {
//            var_dump($authentifsucces);var_dump($user);exit;
            $session = new Session();

            $session->start();

            $session->set('user', $user[0]);
            return $this->redirect($this->generateUrl('my_app_backoffice_homepage'));
        } else {
            return $this->render('MyAppBackofficeBundle:admin:login.html.twig', array(
                        'form' => $form->createView()
            ));
        }
    }

    public function logoutAction(Request $request) {
        $session = $this->getRequest()->getSession(); // Récupération du tableau de session
        $session->clear();
        //$this->getRequest()->getSession()->invalidate(); //    détruire la session ici
        // var_dump($this->getRequest()->getSession()->get('user'));  // null current user
        return $this->redirect($this->generateUrl('my_app_backoffice_login'));
    }

    public function registerAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $user = new Utilisateur();

        $form = $this->createFormBuilder()
                ->add('login', 'text')
                ->add('email', 'text')
                ->add('password', 'repeated', array(
                    'type' => 'password',
                    'invalid_message' => 'Les mots de passe doivent correspondre',
                    'options' => array('required' => true),
                    'first_options' => array('label' => 'Mot de passe'),
                    'second_options' => array('label' => 'Mot de passe (validation)'),)
                )
                ->getForm();

        $request = $this->getRequest();
        $form->bind($request);

        $login = $form["login"]->getData();
        $email = $form["email"]->getData();
        $password = $form["password"]->getData();
        $OK = false;
        if (($login != NULL) && ($email != NULL) && ($password != NULL)) {
            $user->setLogin($login);
            $user->setEmail($email);
            $user->setPassword($password); // $user->setPassword(sha1(md5($password)));      
            $user->setPrivilege('ADMIN');
            $user->setDatelog(new \DateTime());
            $em->persist($user);
            $em->flush();
            $OK = TRUE;
        }
        if ($OK === TRUE) {

            $session = new Session();
            $session->clear(); /// detruire la session avant de la start
            $session->start();
            $session->set('user', $user);

            return $this->redirect($this->generateUrl('my_app_backoffice_homepage'));
        } else {
            return $this->render('MyAppBackofficeBundle:admin:register.html.twig', array(
                        'form' => $form->createView()
            ));
        }
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

    public function formAction(Request $request) {
        return $this->render('MyAppBackofficeBundle:admin:form.html.twig');
    }

    public function chartAction(Request $request) {
        return $this->render('MyAppBackofficeBundle:admin:chart.html.twig');
    }

}
