<?php

namespace MyApp\FrontofficeBundle\Controller;

use MyApp\FrontofficeBundle\Form\loginType;
use MyApp\FrontofficeBundle\Form\registrerType;
use MyApp\UtilisateurBundle\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class clientController extends Controller {

    public function indexAction() {
        $manager_produit = $this->get('entities');

        $produitStateFeatured = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('Featured products'));
        $produitStateOnSale = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('On-Sale Products'));
        $produitStateTopRated = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('Top Rated Products'));
        $allCategorys = $this->get('entities')->AllCategorys();

        $produitState_Featured = $manager_produit->ProduitByStateFourMax($manager_produit->OneStateByName('Featured'));
        $produitState_Newarrivals = $manager_produit->ProduitByStateFourMax($manager_produit->OneStateByName('New arrivals'));
        $produitState_TopSales = $manager_produit->ProduitByStateFourMax($manager_produit->OneStateByName('Top Sales'));

        return $this->render('MyAppFrontofficeBundle:client:index.html.twig', array(
                    'produitStateFeatured' => $produitStateFeatured, 'produitStateOnSale' => $produitStateOnSale, 'produitStateTopRated' => $produitStateTopRated,
                    'allCategorys' => $allCategorys,
                    'produitState_Featured' => $produitState_Featured, 'produitState_Newarrivals' => $produitState_Newarrivals, 'produitState_TopSales' => $produitState_TopSales
        ));
    }

    public function authentificationAction(Request $request) {

        $manager_produit = $this->get('entities');
        $produitStateFeatured = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('Featured products'));
        $produitStateOnSale = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('On-Sale Products'));
        $produitStateTopRated = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('Top Rated Products'));
        $allCategorys = $this->get('entities')->AllCategorys();

        $this->getRequest()->getSession()->clear(); //d�truire la session ici
        $manager = $this->get('collectify_security_manager');
        $managermail = $this->get('collectify_mail_manager');
        /* $form1 = $this->createFormBuilder()
          ->add('email', 'email', array('required' => TRUE, 'attr' => array('placeholder' => 'Put your mail here')))
          ->getForm(); */
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
        /*         * ****************************************************************************************************** */
        /* $form2 = $this->createFormBuilder()
          ->add('login', 'text',
          array('required' => TRUE, 'attr' => array('placeholder' => 'Put your login or mail  here')))
          ->add('password', 'password',
          array('required' => TRUE, 'attr' => array('placeholder' => 'Put your password  here')))
          ->getForm(); */
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
                    'forminscri' => $form1->createView(), 'formconnexion' => $form2->createView(),
                    'produitStateFeatured' => $produitStateFeatured, 'produitStateOnSale' => $produitStateOnSale, 'produitStateTopRated' => $produitStateTopRated,
                    'allCategorys' => $allCategorys
        ));
    }

    public function aboutAction() {

        $manager_produit = $this->get('entities');
        $produitStateFeatured = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('Featured products'));
        $produitStateOnSale = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('On-Sale Products'));
        $produitStateTopRated = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('Top Rated Products'));
        $allCategorys = $this->get('entities')->AllCategorys();

        return $this->render('MyAppFrontofficeBundle:client:about.html.twig', array(
                    'produitStateFeatured' => $produitStateFeatured, 'produitStateOnSale' => $produitStateOnSale, 'produitStateTopRated' => $produitStateTopRated,
                    'allCategorys' => $allCategorys
        ));
    }

    public function contactAction() {

        $manager_produit = $this->get('entities');
        $produitStateFeatured = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('Featured products'));
        $produitStateOnSale = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('On-Sale Products'));
        $produitStateTopRated = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('Top Rated Products'));
        $allCategorys = $this->get('entities')->AllCategorys();
        return $this->render('MyAppFrontofficeBundle:client:contact.html.twig', array(
                    'produitStateFeatured' => $produitStateFeatured, 'produitStateOnSale' => $produitStateOnSale, 'produitStateTopRated' => $produitStateTopRated,
                    'allCategorys' => $allCategorys
        ));
    }

    public function blogAction() {

        $manager_produit = $this->get('entities');
        $produitStateFeatured = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('Featured products'));
        $produitStateOnSale = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('On-Sale Products'));
        $produitStateTopRated = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('Top Rated Products'));
        $allCategorys = $this->get('entities')->AllCategorys();
        return $this->render('MyAppFrontofficeBundle:client:blog.html.twig', array(
                    'produitStateFeatured' => $produitStateFeatured, 'produitStateOnSale' => $produitStateOnSale, 'produitStateTopRated' => $produitStateTopRated,
                    'allCategorys' => $allCategorys
        ));
    }

    public function faqAction() {

        $manager_produit = $this->get('entities');
        $produitStateFeatured = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('Featured products'));
        $produitStateOnSale = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('On-Sale Products'));
        $produitStateTopRated = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('Top Rated Products'));
        $allCategorys = $this->get('entities')->AllCategorys();
        return $this->render('MyAppFrontofficeBundle:client:faq.html.twig', array(
                    'produitStateFeatured' => $produitStateFeatured, 'produitStateOnSale' => $produitStateOnSale, 'produitStateTopRated' => $produitStateTopRated,
                    'allCategorys' => $allCategorys
        ));
    }

    public function termsAction() {

        $manager_produit = $this->get('entities');
        $produitStateFeatured = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('Featured products'));
        $produitStateOnSale = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('On-Sale Products'));
        $produitStateTopRated = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('Top Rated Products'));
        $allCategorys = $this->get('entities')->AllCategorys();

        return $this->render('MyAppFrontofficeBundle:client:terms.html.twig', array(
                    'produitStateFeatured' => $produitStateFeatured, 'produitStateOnSale' => $produitStateOnSale, 'produitStateTopRated' => $produitStateTopRated,
                    'allCategorys' => $allCategorys
        ));
    }

    public function checkoutAction() {

        $manager_produit = $this->get('entities');
        $produitStateFeatured = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('Featured products'));
        $produitStateOnSale = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('On-Sale Products'));
        $produitStateTopRated = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('Top Rated Products'));
        $allCategorys = $this->get('entities')->AllCategorys();

        return $this->render('MyAppFrontofficeBundle:client:checkout.html.twig', array(
                    'produitStateFeatured' => $produitStateFeatured, 'produitStateOnSale' => $produitStateOnSale, 'produitStateTopRated' => $produitStateTopRated,
                    'allCategorys' => $allCategorys
        ));
    }

    public function cartAction(Request $request) {
        $manager_produit = $this->get('entities');
        $produitStateFeatured = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('Featured products'));
        $produitStateOnSale = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('On-Sale Products'));
        $produitStateTopRated = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('Top Rated Products'));
        $allCategorys = $this->get('entities')->AllCategorys();

        $panierSession = $this->get('panier');// objet de la classe service panier //

        $session = new Session();
        if ($session->isStarted() != FALSE) {
            $session->start();
        }
        if (count($panierSession) > 0) {
            $session->set('panierSession', $panierSession);
        }
        $panier = $this->getRequest()->getSession()->get('panierSession');
        // var_dump($session);
        var_dump($panier);
        exit;


        return $this->render('MyAppFrontofficeBundle:client:cart.html.twig', array(
                    'produitStateFeatured' => $produitStateFeatured, 'produitStateOnSale' => $produitStateOnSale, 'produitStateTopRated' => $produitStateTopRated,
                    'allCategorys' => $allCategorys
        ));
    }

    public function singleproductAction($id) {

        $manager_produit = $this->get('entities');
        $produitStateFeatured = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('Featured products'));
        $produitStateOnSale = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('On-Sale Products'));
        $produitStateTopRated = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('Top Rated Products'));
        $allCategorys = $this->get('entities')->AllCategorys();


        $singleproduct = $this->get('entities')->OneProduit($id);
        return $this->render('MyAppFrontofficeBundle:client:singleproduct.html.twig', array(
                    'produitStateFeatured' => $produitStateFeatured, 'produitStateOnSale' => $produitStateOnSale, 'produitStateTopRated' => $produitStateTopRated,
                    'allCategorys' => $allCategorys, 'singleproduct' => $singleproduct
        ));
    }

    public function singleproductsidebarAction() {

        $manager_produit = $this->get('entities');
        $produitStateFeatured = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('Featured products'));
        $produitStateOnSale = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('On-Sale Products'));
        $produitStateTopRated = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('Top Rated Products'));
        $allCategorys = $this->get('entities')->AllCategorys();

        return $this->render('MyAppFrontofficeBundle:client:singleproductsidebar.html.twig', array(
                    'produitStateFeatured' => $produitStateFeatured, 'produitStateOnSale' => $produitStateOnSale, 'produitStateTopRated' => $produitStateTopRated,
                    'allCategorys' => $allCategorys
        ));
    }

}
