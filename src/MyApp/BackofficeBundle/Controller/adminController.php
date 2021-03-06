<?php

namespace MyApp\BackofficeBundle\Controller;

use MyApp\BackofficeBundle\Form\loginType;
use MyApp\BackofficeBundle\Form\registrerType;
use MyApp\UtilisateurBundle\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;



class adminController extends Controller {


    public function indexAction(Request $request) {
        $userSession = $this->get('request_stack')->getCurrentRequest()->getSession()->get('user');
        if ((!empty($userSession))) {
            if ($userSession->getPrivilege() === 'ADMIN') {
                return $this->render('MyAppBackofficeBundle:admin:index.html.twig');
            }
            if ($userSession->getPrivilege() != 'ADMIN') {
                $this->get('session')->getFlashBag()->set('message', 'Not Authorized');
                return $this->redirect($this->generateUrl('my_app_backoffice_login'));
            }
        } else {
            return $this->redirect($this->generateUrl('my_app_backoffice_login'));
        }
    }

    public function loginAction(Request $request) {
        $this->get('request_stack')->getCurrentRequest()->getSession()->clear(); //détruire la session ici
        $form = $this->createForm(new loginType());
        $identifiant = $this->get('request_stack')->getCurrentRequest()->get('identifiant');
        $password = sha1(md5($this->get('request_stack')->getCurrentRequest()->get('password')));
        $manager = $this->get('collectify_security_manager');
        $authentifsucces = $manager->login($identifiant, $password);
        $user = $manager->getUserByPassword($password);
        if (($identifiant != NULL) && ($password != NULL)) {
            if ($authentifsucces == 'LOCKED') {
                $this->get('request_stack')->getCurrentRequest()->getSession()->clear(); //détruire la session ici
                $this->get('session')->getFlashBag()->set('message', 'Not Activated Count');
            }
            if (($authentifsucces == 'TRUE') && ($user != NULL)) {
                $session = new Session();
                if ($session->isStarted() != FALSE) {
                    $session->start();
                }
                $session->set('user', $user);
                return $this->redirect($this->generateUrl('my_app_backoffice_homepage'));
            }
            if ($authentifsucces == 'FALSE') {
                $this->get('request_stack')->getCurrentRequest()->getSession()->clear(); //détruire la session ici
                $this->get('session')->getFlashBag()->set('message', 'Invalid login/password combination');
                return $this->render('MyAppBackofficeBundle:admin:login.html.twig', array(
                            'form' => $form->createView()
                ));
            }
        }
        return $this->render('MyAppBackofficeBundle:admin:login.html.twig', array(
                    'form' => $form->createView()
        ));
    }

    public function logoutAction(Request $request) {
        $this->get('request_stack')->getCurrentRequest()->getSession()->clear(); //détruire la session ici
        return $this->redirect($this->generateUrl('my_app_backoffice_login'));
    }

    public function registerAction(Request $request) {

        $this->get('request_stack')->getCurrentRequest()->getSession()->clear(); //détruire la session ici
        $manager = $this->get('collectify_security_manager');
        $managermail = $this->get('collectify_mail_manager');

        $user = new Utilisateur();
        $form = $this->createForm(new registrerType());
        $request = $this->get('request_stack')->getCurrentRequest();
        $form->bind($request);
        $OK = FALSE;
        $Valid = $this->get('Valid');
        if ($Valid->validerInscri($form)) {
            $EXIST = $manager->donneruservalid($user);
            if ($EXIST != TRUE) {
                $manager->persistUser($user, $form["login"]->getData(), $form["email"]->getData(), $form["password"]->getData());
                $OK = TRUE;
                $managermail->envoiMail($user);  /// renvoie de mail au membre
            } else {
                $this->get('session')->getFlashBag()->set('message', 'Existe deja');
            }
        }
        if ($OK === TRUE) {
            $session = new Session();
            $session->clear(); /// detruire la session avant de la start
            /*
              if ($session->isStarted() != FALSE) {
              $session->start();
              }
              $session->set('user', $user);
             */
            return $this->redirect($this->generateUrl('my_app_backoffice_homepage'));
        } else {
            return $this->render('MyAppBackofficeBundle:admin:register.html.twig', array(
                        'form' => $form->createView()
            ));
        }
    }

    public function changepasswordAction(Request $request) {
        $this->get('request_stack')->getCurrentRequest()->getSession()->clear(); //détruire la session ici
        $manager = $this->get('collectify_security_manager');
        $managermail = $this->get('collectify_mail_manager');
        $em = $this->getDoctrine()->getManager();
        $nouveaupassword = substr(sha1(md5(rand())), 0, 10);
        $form = $this->createFormBuilder()
                ->add('email', 'email', array('required' => TRUE))
                ->getForm();
        $email = $this->get('request_stack')->getCurrentRequest()->get('email');
        $users = $manager->getUserByMail($email);
        /* var_dump($email);var_dump($users[0]);exit; */
        if ($email != NULL) {
            if (!$users) {
                $this->get('session')->getFlashBag()->set('message', 'Mail Invalide');
            } else {
                $users->setPassword(sha1(md5($nouveaupassword)));
                $em->persist($users);
                $em->flush();
                $managermail->nouveaupasswordparmail($users, $nouveaupassword);
                return $this->redirect($this->generateUrl('my_app_backoffice_login'));
            }
        }
        return $this->render('MyAppBackofficeBundle:admin:changepassword.html.twig', array(
                    'form' => $form->createView()
        ));
    }

    public function tableAction() {
        $security = $this->get('collectify_security_manager');
        $security->securityadmin(); // verif security et filtre
        if ($security->securityadmin() != NULL) {
            return new RedirectResponse($security->securityadmin());
        }
        return $this->render('MyAppBackofficeBundle:admin:table.html.twig');
    }

    public function uiAction() {

        $security = $this->get('collectify_security_manager');
        $security->securityadmin(); // verif security et filtre
        if ($security->securityadmin() != NULL) {
            return new RedirectResponse($security->securityadmin());
        }

        return $this->render('MyAppBackofficeBundle:admin:ui.html.twig');
    }

    public function tabpanelAction() {
        $security = $this->get('collectify_security_manager');
        $security->securityadmin(); // verif security et filtre
        if ($security->securityadmin() != NULL) {
            return new RedirectResponse($security->securityadmin());
        }
        return $this->render('MyAppBackofficeBundle:admin:tabpanel.html.twig');
    }

    public function formAction() {
        $security = $this->get('collectify_security_manager');
        $security->securityadmin(); // verif security et filtre
        if ($security->securityadmin() != NULL) {
            return new RedirectResponse($security->securityadmin());
        }
        return $this->render('MyAppBackofficeBundle:admin:form.html.twig');
    }

    public function chartAction() {
        $security = $this->get('collectify_security_manager');
        $security->securityadmin(); // verif security et filtre
        if ($security->securityadmin() != NULL) {
            return new RedirectResponse($security->securityadmin());
        }
        return $this->render('MyAppBackofficeBundle:admin:chart.html.twig');
    }

    public function activerCompteAdminAction($idtoken) {
        $security = $this->get('collectify_security_manager');
        $security->activerCompte($idtoken);
        $users = $security->getUserByIdtoken($idtoken);
        $session = new Session();
        if ($session->isStarted() != FALSE) {
            $session->start();
        }
        if (count($users) > 0) {
            $session->set('user', $users);
        }
        return $this->redirect($this->generateUrl('my_app_frontoffice_homepage'));
    }

}
