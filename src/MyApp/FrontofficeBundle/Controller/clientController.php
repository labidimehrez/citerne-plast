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

        $cart_subtotal = $this->getRequest()->getSession()->get('carttotal');

        return $this->render('MyAppFrontofficeBundle:client:index.html.twig', array(
                    'produitStateFeatured' => $produitStateFeatured, 'produitStateOnSale' => $produitStateOnSale, 'produitStateTopRated' => $produitStateTopRated,
                    'allCategorys' => $allCategorys,
                    'produitState_Featured' => $produitState_Featured, 'produitState_Newarrivals' => $produitState_Newarrivals, 'produitState_TopSales' => $produitState_TopSales,
                    'carttotal' => $cart_subtotal
        ));
    }

    public function authentificationAction(Request $request) {

        $manager_produit = $this->get('entities');
        $produitStateFeatured = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('Featured products'));
        $produitStateOnSale = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('On-Sale Products'));
        $produitStateTopRated = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('Top Rated Products'));
        $allCategorys = $this->get('entities')->AllCategorys();

        $this->getRequest()->getSession()->clear(); //d�truire la session ici
        $cart_subtotal = $this->getRequest()->getSession()->get('carttotal');

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
                    'allCategorys' => $allCategorys, 'carttotal' => $cart_subtotal
        ));
    }

    public function aboutAction() {

        $manager_produit = $this->get('entities');
        $produitStateFeatured = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('Featured products'));
        $produitStateOnSale = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('On-Sale Products'));
        $produitStateTopRated = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('Top Rated Products'));
        $allCategorys = $this->get('entities')->AllCategorys();
        $cart_subtotal = $this->getRequest()->getSession()->get('carttotal');
        return $this->render('MyAppFrontofficeBundle:client:about.html.twig', array(
                    'produitStateFeatured' => $produitStateFeatured, 'produitStateOnSale' => $produitStateOnSale, 'produitStateTopRated' => $produitStateTopRated,
                    'allCategorys' => $allCategorys, 'carttotal' => $cart_subtotal
        ));
    }

    public function contactAction() {

        $manager_produit = $this->get('entities');
        $produitStateFeatured = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('Featured products'));
        $produitStateOnSale = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('On-Sale Products'));
        $produitStateTopRated = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('Top Rated Products'));
        $allCategorys = $this->get('entities')->AllCategorys();
        $cart_subtotal = $this->getRequest()->getSession()->get('carttotal');
        return $this->render('MyAppFrontofficeBundle:client:contact.html.twig', array(
                    'produitStateFeatured' => $produitStateFeatured, 'produitStateOnSale' => $produitStateOnSale, 'produitStateTopRated' => $produitStateTopRated,
                    'allCategorys' => $allCategorys, 'carttotal' => $cart_subtotal
        ));
    }

    public function blogAction() {

        $manager_produit = $this->get('entities');
        $produitStateFeatured = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('Featured products'));
        $produitStateOnSale = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('On-Sale Products'));
        $produitStateTopRated = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('Top Rated Products'));
        $allCategorys = $this->get('entities')->AllCategorys();
        $cart_subtotal = $this->getRequest()->getSession()->get('carttotal');
        return $this->render('MyAppFrontofficeBundle:client:blog.html.twig', array(
                    'produitStateFeatured' => $produitStateFeatured, 'produitStateOnSale' => $produitStateOnSale, 'produitStateTopRated' => $produitStateTopRated,
                    'allCategorys' => $allCategorys, 'carttotal' => $cart_subtotal
        ));
    }

    public function faqAction() {

        $manager_produit = $this->get('entities');
        $produitStateFeatured = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('Featured products'));
        $produitStateOnSale = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('On-Sale Products'));
        $produitStateTopRated = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('Top Rated Products'));
        $allCategorys = $this->get('entities')->AllCategorys();
        $cart_subtotal = $this->getRequest()->getSession()->get('carttotal');
        return $this->render('MyAppFrontofficeBundle:client:faq.html.twig', array(
                    'produitStateFeatured' => $produitStateFeatured, 'produitStateOnSale' => $produitStateOnSale, 'produitStateTopRated' => $produitStateTopRated,
                    'allCategorys' => $allCategorys, 'carttotal' => $cart_subtotal
        ));
    }

    public function termsAction() {

        $manager_produit = $this->get('entities');
        $produitStateFeatured = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('Featured products'));
        $produitStateOnSale = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('On-Sale Products'));
        $produitStateTopRated = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('Top Rated Products'));
        $allCategorys = $this->get('entities')->AllCategorys();
        $cart_subtotal = $this->getRequest()->getSession()->get('carttotal');
        return $this->render('MyAppFrontofficeBundle:client:terms.html.twig', array(
                    'produitStateFeatured' => $produitStateFeatured, 'produitStateOnSale' => $produitStateOnSale, 'produitStateTopRated' => $produitStateTopRated,
                    'allCategorys' => $allCategorys, 'carttotal' => $cart_subtotal
        ));
    }

    public function checkoutAction() {

        $manager_produit = $this->get('entities');
        $produitStateFeatured = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('Featured products'));
        $produitStateOnSale = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('On-Sale Products'));
        $produitStateTopRated = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('Top Rated Products'));
        $allCategorys = $this->get('entities')->AllCategorys();
        $cart_subtotal = $this->getRequest()->getSession()->get('carttotal');
        return $this->render('MyAppFrontofficeBundle:client:checkout.html.twig', array(
                    'produitStateFeatured' => $produitStateFeatured, 'produitStateOnSale' => $produitStateOnSale, 'produitStateTopRated' => $produitStateTopRated,
                    'allCategorys' => $allCategorys, 'carttotal' => $cart_subtotal
        ));
    }

    public function cartAction($id) {


        $manager_produit = $this->get('entities');
        $allproduit = $this->get('entities')->AllProduits();
        $produitStateFeatured = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('Featured products'));
        $produitStateOnSale = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('On-Sale Products'));
        $produitStateTopRated = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('Top Rated Products'));
        $allCategorys = $this->get('entities')->AllCategorys();

        $session = new Session();
        if ($session->isStarted() != FALSE) {
            $session->start();
        }

        $panierSession = $this->get('panier'); // objet de la classe service panier //
        if (count($panierSession) > 0) {
            $session->set('panierSession', $panierSession);
        }

        $panier = $this->getRequest()->getSession()->get('panierSession');
        if ($id != NULL) {
            $panierSession->additem($id);
        }

        // var_dump($panier->viewcart());exit;
        $cart_subtotal = 0;
        foreach ($panier->viewcart() as $id => $qty) {
            $cart_subtotal = $cart_subtotal + ($this->get('entities')->PriceByProduit($id) * $qty); // float total cart //
        }

        if (count($panierSession) > 0) {
            $session->set('carttotal', $cart_subtotal);
        }

        return $this->render('MyAppFrontofficeBundle:client:cart.html.twig', array(
                    'produitStateFeatured' => $produitStateFeatured, 'produitStateOnSale' => $produitStateOnSale, 'produitStateTopRated' => $produitStateTopRated,
                    'allCategorys' => $allCategorys, 'panier' => $panier->viewcart(), 'allproduit' => $allproduit, 'carttotal' => $cart_subtotal
        ));
    }

    public function singleproductAction($id) {

        $manager_produit = $this->get('entities');
        $produitStateFeatured = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('Featured products'));
        $produitStateOnSale = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('On-Sale Products'));
        $produitStateTopRated = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('Top Rated Products'));
        $allCategorys = $this->get('entities')->AllCategorys();
        $cart_subtotal = $this->getRequest()->getSession()->get('carttotal');

        $singleproduct = $this->get('entities')->OneProduit($id);
        return $this->render('MyAppFrontofficeBundle:client:singleproduct.html.twig', array(
                    'produitStateFeatured' => $produitStateFeatured, 'produitStateOnSale' => $produitStateOnSale, 'produitStateTopRated' => $produitStateTopRated,
                    'allCategorys' => $allCategorys, 'singleproduct' => $singleproduct, 'carttotal' => $cart_subtotal
        ));
    }

    public function singleproductsidebarAction() {

        $manager_produit = $this->get('entities');
        $produitStateFeatured = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('Featured products'));
        $produitStateOnSale = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('On-Sale Products'));
        $produitStateTopRated = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('Top Rated Products'));
        $allCategorys = $this->get('entities')->AllCategorys();
        $cart_subtotal = $this->getRequest()->getSession()->get('carttotal');
        return $this->render('MyAppFrontofficeBundle:client:singleproductsidebar.html.twig', array(
                    'produitStateFeatured' => $produitStateFeatured, 'produitStateOnSale' => $produitStateOnSale, 'produitStateTopRated' => $produitStateTopRated,
                    'allCategorys' => $allCategorys, 'carttotal' => $cart_subtotal
        ));
    }

}
