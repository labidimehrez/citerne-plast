<?php

namespace MyApp\FrontofficeBundle\Controller;

use MyApp\FrontofficeBundle\Form\loginType;
use MyApp\FrontofficeBundle\Form\registrerType;
use MyApp\FrontofficeBundle\Services\Panier;
use MyApp\UtilisateurBundle\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class clientController extends Controller {

    public function indexAction() {

        $manager_produit = $this->get('entities');

        $allCategorys = $this->get('entities')->AllCategorys();
        $allproduit = $this->get('entities')->AllProduits();

        $produitStateFeatured = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('Featured products'));
        $produitStateOnSale = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('On-Sale Products'));
        $produitStateTopRated = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('Top Rated Products'));
        $produitState_Featured = $manager_produit->ProduitByStateFourMax($manager_produit->OneStateByName('Featured'));
        $produitState_Newarrivals = $manager_produit->ProduitByStateFourMax($manager_produit->OneStateByName('New arrivals'));
        $produitState_TopSales = $manager_produit->ProduitByStateFourMax($manager_produit->OneStateByName('Top Sales'));

        $cart_subtotal = $this->get('request_stack')->getCurrentRequest()->getSession()->get('carttotal');
        $panierSession = $this->get('request_stack')->getCurrentRequest()->getSession()->get('panierSession');


        if (!$panierSession instanceof Panier) {
            $panierSession = $this->get('panier');
        }


        return $this->render('MyAppFrontofficeBundle:client:index.html.twig', array(
                    'produitStateFeatured' => $produitStateFeatured, 'produitStateOnSale' => $produitStateOnSale, 'produitStateTopRated' => $produitStateTopRated,
                    'allCategorys' => $allCategorys,
                    'produitState_Featured' => $produitState_Featured, 'produitState_Newarrivals' => $produitState_Newarrivals, 'produitState_TopSales' => $produitState_TopSales,
                    'carttotal' => $cart_subtotal, 'panier' => $panierSession->viewcart(), 'allproduit' => $allproduit
        ));
    }

    public function authentificationAction(Request $request) {

        $manager_produit = $this->get('entities');
        $produitStateFeatured = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('Featured products'));
        $produitStateOnSale = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('On-Sale Products'));
        $produitStateTopRated = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('Top Rated Products'));
        $allCategorys = $this->get('entities')->AllCategorys();
        $allproduit = $this->get('entities')->AllProduits();

        $this->get('request_stack')->getCurrentRequest()->getSession()->clear(); //d�truire la session ici
        $cart_subtotal = $this->get('request_stack')->getCurrentRequest()->getSession()->get('carttotal');
        $panierSession = $this->get('request_stack')->getCurrentRequest()->getSession()->get('panierSession');
        if (!$panierSession instanceof Panier) {
            $panierSession = $this->get('panier');
        }
        $manager = $this->get('collectify_security_manager');
        $managermail = $this->get('collectify_mail_manager');
        /* $form1 = $this->createFormBuilder()
          ->add('email', 'email', array('required' => TRUE, 'attr' => array('placeholder' => 'Put your mail here')))
          ->getForm(); */
        $form1 = $this->createForm(new loginType());
        $request = $this->get('request_stack')->getCurrentRequest();
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
                    'allCategorys' => $allCategorys, 'carttotal' => $cart_subtotal, 'panier' => $panierSession->viewcart(), 'allproduit' => $allproduit
        ));
    }

    public function aboutAction(Request $request) {

        $manager_produit = $this->get('entities');
        $produitStateFeatured = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('Featured products'));
        $produitStateOnSale = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('On-Sale Products'));
        $produitStateTopRated = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('Top Rated Products'));
        $allCategorys = $this->get('entities')->AllCategorys();
        $allproduit = $this->get('entities')->AllProduits();


        $cart_subtotal = $this->get('request_stack')->getCurrentRequest()->getSession()->get('carttotal');
        $panierSession = $this->get('request_stack')->getCurrentRequest()->getSession()->get('panierSession');
        if (!$panierSession instanceof Panier) {
            $panierSession = $this->get('panier');
        }

        return $this->render('MyAppFrontofficeBundle:client:about.html.twig', array(
                    'produitStateFeatured' => $produitStateFeatured, 'produitStateOnSale' => $produitStateOnSale, 'produitStateTopRated' => $produitStateTopRated,
                    'allCategorys' => $allCategorys, 'carttotal' => $cart_subtotal, 'panier' => $panierSession->viewcart(), 'allproduit' => $allproduit
        ));
    }

    public function contactAction(Request $request) {

        $manager_produit = $this->get('entities');
        $produitStateFeatured = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('Featured products'));
        $produitStateOnSale = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('On-Sale Products'));
        $produitStateTopRated = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('Top Rated Products'));
        $allCategorys = $this->get('entities')->AllCategorys();
        $allproduit = $this->get('entities')->AllProduits();


        $cart_subtotal = $this->get('request_stack')->getCurrentRequest()->getSession()->get('carttotal');
        $panierSession = $this->get('request_stack')->getCurrentRequest()->getSession()->get('panierSession');
        if (!$panierSession instanceof Panier) {
            $panierSession = $this->get('panier');
        }

        return $this->render('MyAppFrontofficeBundle:client:contact.html.twig', array(
                    'produitStateFeatured' => $produitStateFeatured, 'produitStateOnSale' => $produitStateOnSale, 'produitStateTopRated' => $produitStateTopRated,
                    'allCategorys' => $allCategorys, 'carttotal' => $cart_subtotal, 'panier' => $panierSession->viewcart(), 'allproduit' => $allproduit
        ));
    }

    public function blogAction(Request $request) {

        $manager_produit = $this->get('entities');
        $produitStateFeatured = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('Featured products'));
        $produitStateOnSale = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('On-Sale Products'));
        $produitStateTopRated = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('Top Rated Products'));
        $allCategorys = $this->get('entities')->AllCategorys();
        $allproduit = $this->get('entities')->AllProduits();


        $cart_subtotal = $this->get('request_stack')->getCurrentRequest()->getSession()->get('carttotal');
        $panierSession = $this->get('request_stack')->getCurrentRequest()->getSession()->get('panierSession');
        if (!$panierSession instanceof Panier) {
            $panierSession = $this->get('panier');
        }

        return $this->render('MyAppFrontofficeBundle:client:blog.html.twig', array(
                    'produitStateFeatured' => $produitStateFeatured, 'produitStateOnSale' => $produitStateOnSale, 'produitStateTopRated' => $produitStateTopRated,
                    'allCategorys' => $allCategorys, 'carttotal' => $cart_subtotal, 'panier' => $panierSession->viewcart(), 'allproduit' => $allproduit
        ));
    }

    public function faqAction(Request $request) {

        $manager_produit = $this->get('entities');
        $produitStateFeatured = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('Featured products'));
        $produitStateOnSale = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('On-Sale Products'));
        $produitStateTopRated = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('Top Rated Products'));
        $allCategorys = $this->get('entities')->AllCategorys();
        $allproduit = $this->get('entities')->AllProduits();

        $cart_subtotal = $this->get('request_stack')->getCurrentRequest()->getSession()->get('carttotal');
        $panierSession = $this->get('request_stack')->getCurrentRequest()->getSession()->get('panierSession');
        if (!$panierSession instanceof Panier) {
            $panierSession = $this->get('panier');
        }

        return $this->render('MyAppFrontofficeBundle:client:faq.html.twig', array(
                    'produitStateFeatured' => $produitStateFeatured, 'produitStateOnSale' => $produitStateOnSale, 'produitStateTopRated' => $produitStateTopRated,
                    'allCategorys' => $allCategorys, 'carttotal' => $cart_subtotal, 'panier' => $panierSession->viewcart(), 'allproduit' => $allproduit
        ));
    }

    public function termsAction(Request $request) {

        $manager_produit = $this->get('entities');
        $produitStateFeatured = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('Featured products'));
        $produitStateOnSale = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('On-Sale Products'));
        $produitStateTopRated = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('Top Rated Products'));
        $allCategorys = $this->get('entities')->AllCategorys();
        $allproduit = $this->get('entities')->AllProduits();

        $cart_subtotal = $this->get('request_stack')->getCurrentRequest()->getSession()->get('carttotal');
        $panierSession = $this->get('request_stack')->getCurrentRequest()->getSession()->get('panierSession');
        if (!$panierSession instanceof Panier) {
            $panierSession = $this->get('panier');
        }

        return $this->render('MyAppFrontofficeBundle:client:terms.html.twig', array(
                    'produitStateFeatured' => $produitStateFeatured, 'produitStateOnSale' => $produitStateOnSale, 'produitStateTopRated' => $produitStateTopRated,
                    'allCategorys' => $allCategorys, 'carttotal' => $cart_subtotal, 'panier' => $panierSession->viewcart(), 'allproduit' => $allproduit
        ));
    }

    public function checkoutAction(Request $request) {

        $manager_produit = $this->get('entities');
        $produitStateFeatured = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('Featured products'));
        $produitStateOnSale = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('On-Sale Products'));
        $produitStateTopRated = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('Top Rated Products'));
        $allCategorys = $this->get('entities')->AllCategorys();
        $allproduit = $this->get('entities')->AllProduits();

        $cart_subtotal = $this->get('request_stack')->getCurrentRequest()->getSession()->get('carttotal');
        $panierSession = $this->get('request_stack')->getCurrentRequest()->getSession()->get('panierSession');
        if (!$panierSession instanceof Panier) {
            $panierSession = $this->get('panier');
        }

        return $this->render('MyAppFrontofficeBundle:client:checkout.html.twig', array(
                    'produitStateFeatured' => $produitStateFeatured, 'produitStateOnSale' => $produitStateOnSale, 'produitStateTopRated' => $produitStateTopRated,
                    'allCategorys' => $allCategorys, 'carttotal' => $cart_subtotal, 'panier' => $panierSession->viewcart(), 'allproduit' => $allproduit
        ));
    }

    public function cartAction($slug, Request $request) {

        $quantity = intval($this->get('request_stack')->getCurrentRequest()->get('quantity'));
        $manager_produit = $this->get('entities');
        $id = $manager_produit->ProduitIdBySlug($slug);
        $allproduit = $this->get('entities')->AllProduits();
        $produitStateFeatured = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('Featured products'));
        $produitStateOnSale = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('On-Sale Products'));
        $produitStateTopRated = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('Top Rated Products'));
        $allCategorys = $this->get('entities')->AllCategorys();


        $panier = $this->get('panier'); // objet de la classe service panier //
        $panierSession = $this->get('request_stack')->getCurrentRequest()->getSession()->get('panierSession');
        if (!$panierSession instanceof Panier) {
            $session = new Session();
            if ($session->isStarted() != FALSE) {
                $session->start();
            }
            $id_Ajouté_panier = array();
            $cart_subtotal = 0;
            $i = 0;
            while ($i < $quantity) {
                if ($id != NULL) {
                    $panier->additem($id);
                    array_push($id_Ajouté_panier, $id);
                }
                $i++;
            }
            foreach ($panier->viewcart() as $id => $qty) {
                $cart_subtotal = $cart_subtotal + ($this->get('entities')->PriceByProduit($id) * $qty); // float total cart //
            }
            $session->set('idajoutépanier', $id_Ajouté_panier);
            $session->set('panierSession', $panier);
            $session->set('carttotal', $cart_subtotal);
        } else {
            $session = $this->get('request_stack')->getCurrentRequest()->getSession();
            if ($quantity > 0) {
                $cart_subtotal = 0;
            } else {
                $cart_subtotal = $this->get('request_stack')->getCurrentRequest()->getSession()->get('carttotal');
            }
            $id_Ajouté_panier = $this->get('request_stack')->getCurrentRequest()->getSession()->get('idajoutépanier');
            $panierSession = $this->get('request_stack')->getCurrentRequest()->getSession()->get('panierSession');
            $panier = $panierSession;
            if ((!in_array($id, $id_Ajouté_panier)) && ($id != NULL)) {
                $i = 0;
                while ($i < $quantity) {
                    $panierSession->additem($id);
                    array_push($id_Ajouté_panier, $id);
                    $i++;
                }
            }
            if ($quantity > 0) {
                foreach ($panierSession->viewcart() as $id => $qty) {
                    $cart_subtotal = $cart_subtotal + ($this->get('entities')->PriceByProduit($id) * $qty); // float total cart //
                }
            }
            $session->set('idajoutépanier', $id_Ajouté_panier);
            $session->set('panierSession', $panier);
            $session->set('carttotal', $cart_subtotal);
        }


        return $this->render('MyAppFrontofficeBundle:client:cart.html.twig', array(
                    'produitStateFeatured' => $produitStateFeatured, 'produitStateOnSale' => $produitStateOnSale, 'produitStateTopRated' => $produitStateTopRated,
                    'allCategorys' => $allCategorys, 'panier' => $panier->viewcart(), 'allproduit' => $allproduit, 'carttotal' => $cart_subtotal
        ));
    }

    public function singleproductsidebarAction() {

        $manager_produit = $this->get('entities');
        $allCategorys = $this->get('entities')->AllCategorys();
        $allproduit = $this->get('entities')->AllProduits();

        $produitStateFeatured = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('Featured products'));
        $produitStateOnSale = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('On-Sale Products'));
        $produitStateTopRated = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('Top Rated Products'));


        $cart_subtotal = $this->get('request_stack')->getCurrentRequest()->getSession()->get('carttotal');
        $panierSession = $this->get('request_stack')->getCurrentRequest()->getSession()->get('panierSession');
        if (!$panierSession instanceof Panier) {
            $panierSession = $this->get('panier');
        }

        return $this->render('MyAppFrontofficeBundle:client:singleproductsidebar.html.twig', array(
                    'produitStateFeatured' => $produitStateFeatured, 'produitStateOnSale' => $produitStateOnSale, 'produitStateTopRated' => $produitStateTopRated,
                    'allCategorys' => $allCategorys, 'carttotal' => $cart_subtotal, 'panier' => $panierSession->viewcart(), 'allproduit' => $allproduit
        ));
    }

    public function singleproductAction($slug, Request $request) {

        $manager_produit = $this->get('entities');
        $id = $manager_produit->ProduitIdBySlug($slug);
        $allCategorys = $this->get('entities')->AllCategorys();
        $allproduit = $this->get('entities')->AllProduits();

        $produitStateFeatured = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('Featured products'));
        $produitStateOnSale = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('On-Sale Products'));
        $produitStateTopRated = $manager_produit->ProduitByStateThreeMax($manager_produit->OneStateByName('Top Rated Products'));

        $singleproduct = $this->get('entities')->OneProduit($id);
        $cart_subtotal = $this->get('request_stack')->getCurrentRequest()->getSession()->get('carttotal');
        if ($cart_subtotal == NULL) {
            $cart_subtotal = 0;
        }

        $panierSession = $this->get('request_stack')->getCurrentRequest()->getSession()->get('panierSession');
        if ($panierSession == NULL) {
            $panierSession = $this->get('panier');
        }

        $form = $this->createFormBuilder()
                ->add('quantity', 'text')
                ->getForm();

        return $this->render('MyAppFrontofficeBundle:client:singleproduct.html.twig', array(
                    'produitStateFeatured' => $produitStateFeatured, 'produitStateOnSale' => $produitStateOnSale, 'produitStateTopRated' => $produitStateTopRated,
                    'allCategorys' => $allCategorys, 'singleproduct' => $singleproduct, 'carttotal' => $cart_subtotal,
                    'form' => $form->createView(), 'panier' => $panierSession->viewcart(), 'allproduit' => $allproduit
        ));
    }

    public function supprimerdepanierAction($id, Request $request) {

        $panierSession = $this->get('request_stack')->getCurrentRequest()->getSession()->get('panierSession');
        $session = $this->get('request_stack')->getCurrentRequest()->getSession();
        $id = intval($id);
        if ($request->isXmlHttpRequest()) {
            $panierSession->delmoreitem($id);
            $session->set('panierSession', $panierSession);
            $cart_subtotal = 0;
            foreach ($panierSession->viewcart() as $id => $qty) {
                $cart_subtotal = $cart_subtotal + ($this->get('entities')->PriceByProduit($id) * $qty); // float total cart //
            }

            $session->set('carttotal', $cart_subtotal);
            return $this->container->get('templating')->renderResponse('MyAppFrontofficeBundle:client/cart:cartajax.html.twig', array(
                        'carttotal' => $cart_subtotal, 'panier' => $panierSession->viewcart()
            ));
        }
    }

    public function supprimerdeshoppingcartAction($id, Request $request) {

        $panierSession = $this->get('request_stack')->getCurrentRequest()->getSession()->get('panierSession');
        $session = $this->get('request_stack')->getCurrentRequest()->getSession();
        $id = intval($id);
        if ($request->isXmlHttpRequest()) {
            $panierSession->delmoreitem($id);
            $session->set('panierSession', $panierSession);
            $cart_subtotal = 0;
            foreach ($panierSession->viewcart() as $id => $qty) {
                $cart_subtotal = $cart_subtotal + ($this->get('entities')->PriceByProduit($id) * $qty); // float total cart //
            }

            $session->set('carttotal', $cart_subtotal);
            return $this->container->get('templating')->renderResponse('MyAppFrontofficeBundle:client/cart:shoppingcart.html.twig', array(
                        'carttotal' => $cart_subtotal, 'panier' => $panierSession->viewcart()
            ));
        }
    }

}
