<?php

namespace MyApp\FrontofficeBundle\Controller;

use MyApp\FrontofficeBundle\Entity\Message;
use MyApp\FrontofficeBundle\Form\MessageType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class  messageController extends Controller
{

    public function addAction()
    {
        $em = $this->getDoctrine()->getManager();
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


        $message = new Message();
        $form = $this->createForm(new MessageType, $message);
        $request = $this->get('request_stack')->getCurrentRequest();
        if ($request->isMethod('Post')) {
            $form->bind($request);

            if ($form->isValid()) {
                $message = $form->getData();
                $em = $this->getDoctrine()->getManager();
                $message->setDateCreation(new \DateTime());
                $message->setLu(FALSE);
                $em->persist($message);
                $em->flush();
                return $this->redirect($this->generateUrl('my_app_frontoffice_contact'));
            }
        }


        return $this->render('MyAppFrontofficeBundle:client:contact.html.twig', array(
            'form' => $form->createView(),'allCategorys' => $allCategorys, 'carttotal' => $cart_subtotal, 'panier' => $panierSession->viewcart(),
			'produitStateFeatured' => $produitStateFeatured, 'produitStateOnSale' => $produitStateOnSale, 'produitStateTopRated' => $produitStateTopRated,
            'allproduit' => $allproduit
        ));
    }

}
