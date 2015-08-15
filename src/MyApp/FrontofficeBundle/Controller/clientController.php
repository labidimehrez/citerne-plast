<?php

namespace MyApp\FrontofficeBundle\Controller;

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
        $user = new Utilisateur();
        $form1 = $this->createFormBuilder()
            ->add('email', 'email', array(
                'required' => TRUE,
                'attr' => array(
                    'placeholder' => 'Put your mail here'
                )
            ))
            ->getForm();
        $request = $this->getRequest();
        $form1->bind($request); //  var_dump($form1["email"]->getData());exit;
        $EXIST = $manager->getUserByMail($form1["email"]->getData());
        if (empty($EXIST)) {

            $nouveaupassword = substr(sha1(md5(rand())), 0, 10);

            $manager->persistclient($user, $form1["email"]->getData(), $nouveaupassword);
            $managermail->nouveaupasswordparmail($user, $nouveaupassword);  /// renvoie de mail au membre
            $session = new Session();

            if ($session->isStarted() != FALSE) {
                $session->start();
            }

            $session->set('user', $user[0]);
            return $this->redirect($this->generateUrl('my_app_frontoffice_homepage'));

        }

        return $this->render('MyAppFrontofficeBundle:client:authentification.html.twig', array(
            'form1' => $form1->createView()
        ));
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
