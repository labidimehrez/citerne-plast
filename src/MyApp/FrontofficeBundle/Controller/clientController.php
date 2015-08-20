<?php

namespace MyApp\FrontofficeBundle\Controller;

use MyApp\FrontofficeBundle\Form\loginType;
use MyApp\FrontofficeBundle\Form\registrerType;
use MyApp\UtilisateurBundle\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class clientController extends Controller
{
    public function indexAction()
    {
        return $this->render('MyAppFrontofficeBundle:client:index.html.twig');
    }


    public function authentificationAction(Request $request)
    {
        $this->getRequest()->getSession()->clear();//détruire la session ici
        $manager = $this->get('collectify_security_manager');
        $managermail = $this->get('collectify_mail_manager');
        /* $form1 = $this->createFormBuilder()
             ->add('email', 'email', array('required' => TRUE, 'attr' => array('placeholder' => 'Put your mail here')))
             ->getForm();*/
        $form1 = $this->createForm(new loginType());
        $request = $this->getRequest();
        $form1->bind($request);
        if (($form1["email"]->getData() != NULL)) {
            $EXIST = $manager->getUserByMail($form1["email"]->getData());
            if (!empty($EXIST)) {
                $this->get('session')->getFlashBag()->set('messageinscrit', 'Existe deja');
                return $this->redirect($this->generateUrl('my_app_frontoffice_authentification_inscription'));
            }
            if (empty($EXIST)) {
                $user = new Utilisateur();
                $nouveaupassword = substr(sha1(md5(rand())), 0, 10);
                $manager->persistclient($user, $form1["email"]->getData(), $nouveaupassword);
                $managermail->nouveaupasswordparmail($user, $nouveaupassword);  /// renvoie de mail au membre
                $session = new Session();
                if ($session->isStarted() != FALSE) {
                    $session->start();
                }
                $session->set('user', $user);
                return $this->redirect($this->generateUrl('my_app_frontoffice_homepage'));
            }
        }
        /*********************************************************************************************************/
        /* $form2 = $this->createFormBuilder()
             ->add('login', 'text',
                 array('required' => TRUE, 'attr' => array('placeholder' => 'Put your login or mail  here')))
             ->add('password', 'password',
                 array('required' => TRUE, 'attr' => array('placeholder' => 'Put your password  here')))
             ->getForm();*/
        $form2 = $this->createForm(new registrerType());
        $form2->bind($request);
        if (($form2["login"]->getData() != NULL) && ($form2["password"]->getData() != NULL)) {
            $password = sha1(md5($form2["password"]->getData()));
            $user = $manager->getUserByPassword($password);
            if (!$user) {
                $this->get('session')->getFlashBag()->set('message', 'Compte Inexistant');
                return $this->redirect($this->generateUrl('my_app_frontoffice_authentification_inscription'));
            }
            if ($user) {
                $connected = $manager->login($form2["login"]->getData(), $password);
                if ($connected == FALSE) {
                    $this->get('session')->getFlashBag()->set('message', 'Donnees invalides');
                    return $this->redirect($this->generateUrl('my_app_frontoffice_authentification_inscription'));
                }
                if ($connected != FALSE) {
                    $session = new Session();
                    if ($session->isStarted() != FALSE) {
                        $session->start();
                    }
                    $session->set('user', $user);
                    return $this->redirect($this->generateUrl('my_app_frontoffice_homepage'));
                }
            }
        }
        return $this->render('MyAppFrontofficeBundle:client:authentification.html.twig', array(
            'forminscri' => $form1->createView(), 'formconnexion' => $form2->createView()));

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

    public function singleproductAction()
    {
        return $this->render('MyAppFrontofficeBundle:client:singleproduct.html.twig');
    }

    public function singleproductsidebarAction()
    {
        return $this->render('MyAppFrontofficeBundle:client:singleproductsidebar.html.twig');
    }

}
