<?php

namespace MyApp\BackofficeBundle\Controller;
use MyApp\UtilisateurBundle\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class adminController extends Controller {

    public function indexAction(Request $request) {
        $userSession = $this->getRequest()->getSession()->get('user');
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

        $form = $this->createFormBuilder()
                ->add('identifiant', 'text', array('required' => TRUE))
                ->add('password', 'password', array('required' => TRUE))
                ->getForm();
        $identifiant = $this->getRequest()->get('identifiant');
        $password = sha1(md5($this->getRequest()->get('password')));

        if (($identifiant == NULL) && ($password == NULL)) {

            return $this->render('MyAppBackofficeBundle:admin:login.html.twig', array(
                        'form' => $form->createView()
            ));
        }
        // var_dump($identifiant);var_dump($password);
        $manager = $this->get('collectify_security_manager');
        $authentifsucces = $manager->login($identifiant, $password);
        // $user = $manager->login($identifiant, $password);
        $user = $manager->getUserByPassword($password);
        // var_dump($authentifsucces);var_dump($user);exit;
        if (($authentifsucces == TRUE) && ($user != NULL)) {

            $session = new Session();
            $session->start();
            $session->set('user', $user[0]);

            return $this->redirect($this->generateUrl('my_app_backoffice_homepage'));
        } else {
            $session = $this->getRequest()->getSession();
            $session->clear();
            $this->get('session')->getFlashBag()->set('message', 'Invalid login/password combination');
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
        $manager = $this->get('collectify_security_manager');
        $managermail = $this->get('collectify_mail_manager');
        /* $em = $this->getDoctrine()->getManager();*/
        $user = new Utilisateur();
        $form = $this->createFormBuilder()
                ->add('login', 'text', array(
                    'required' => TRUE,
                    'attr' => array(
                        'placeholder' => 'What\'s your name?',
                        'pattern' => '.{5,}' //minlength
                    )
                ))
                ->add('email', 'email', array(
                    'required' => TRUE,
                    'attr' => array(
                        'placeholder' => 'So I can get back to you.'
                    )
                ))
                ->add('password', 'repeated', array(
                    'required' => TRUE,
                    'type' => 'password',
                    'invalid_message' => 'Les mots de passe doivent correspondre',
                    'options' => array('required' => true),
                    'first_options' => array('label' => 'Mot de passe'),
                    'second_options' => array('label' => 'Mot de passe (validation)'),)
                )
                ->getForm();
        $request = $this->getRequest();
        $form->bind($request);

        $OK = false;
        $Valid = $this->get('Valid');

        if ($Valid->validerInscri($form)) {

            $EXIST = $manager->donneruservalid($user);
            if ($EXIST!= TRUE) {

                $manager->persistUser($user, $form["login"]->getData(), $form["email"]->getData(), $form["password"]->getData());
                $OK = TRUE;
                $managermail->envoiMail($user);  /// renvoie de mail au membre
            } else {
                $this->get('session')->getFlashBag()->set('message', 'Existe déja');
            }
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

    public function changepasswordAction(Request $request) {
        $manager = $this->get('collectify_security_manager');
         $managermail = $this->get('collectify_mail_manager');
        $em = $this->getDoctrine()->getManager();
        $nouveaupassword = substr(sha1(md5(rand())), 0, 10);
        $form = $this->createFormBuilder()
                ->add('email', 'email', array('required' => TRUE))
                ->getForm();
        $email = $this->getRequest()->get('email');
        $users = $manager->getUserByMail($email);
        /*var_dump($email);var_dump($users[0]);exit;*/
        
        if ($email != NULL) {
            if (!$users) {
                $this->get('session')->getFlashBag()->set('message', 'Mail Invalide');
            } else {
                $users[0]->setPassword(sha1(md5($nouveaupassword)));
                $em->persist($users[0]);
                $em->flush();
                $managermail->nouveaupasswordparmail($users[0],$nouveaupassword);  
                return $this->redirect($this->generateUrl('my_app_backoffice_login'));
            }
        }
        return $this->render('MyAppBackofficeBundle:admin:changepassword.html.twig', array(
                    'form' => $form->createView()
        ));
    }

    public function tableAction(  ) {
        $security = $this->get('collectify_security_manager');
        $security->security();// verif security et filtre
        if($security->security() !=NULL) {return new RedirectResponse($security->security());}

        return $this->render('MyAppBackofficeBundle:admin:table.html.twig');
    }

    public function uiAction( ) {
        $security = $this->get('collectify_security_manager');
        $security->security();// verif security et filtre
        if($security->security() !=NULL) {return new RedirectResponse($security->security());}
            
        return $this->render('MyAppBackofficeBundle:admin:ui.html.twig');
    }

    public function tabpanelAction( ) {
        $security = $this->get('collectify_security_manager');
        $security->security();// verif security et filtre
        if($security->security() !=NULL) {return new RedirectResponse($security->security());}
        return $this->render('MyAppBackofficeBundle:admin:tabpanel.html.twig');
    }

    public function formAction( ) {
        $security = $this->get('collectify_security_manager');
        $security->security();// verif security et filtre
        if($security->security() !=NULL) {return new RedirectResponse($security->security());}
        return $this->render('MyAppBackofficeBundle:admin:form.html.twig');
    }

    public function chartAction( ) {
        $security = $this->get('collectify_security_manager');
        $security->security();// verif security et filtre
        if($security->security() !=NULL) {return new RedirectResponse($security->security());}
        return $this->render('MyAppBackofficeBundle:admin:chart.html.twig');
    }

}
