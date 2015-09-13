<?php

namespace MyApp\BackofficeBundle\Controller;

use MyApp\BackofficeBundle\Entity\Category;
use MyApp\BackofficeBundle\Form\CategoryType;
use MyApp\BackofficeBundle\Entity\Produit;
use MyApp\BackofficeBundle\Form\ProduitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class manageController extends Controller {

    public function messageAction() {

        $manager_entities = $this->get('entities');
        /** equivalent de em manager * */
        $messages = $manager_entities->AllMessages();
        var_dump($messages);
        exit;
        // return $this->render('MyAppArticleBundle:style:show.html.twig', array(
        //    'messages' => $messages
        // ));
    }

    public function categoryaddAction() {
        $manager_category = $this->get('entities');
        /** equivalent de em manager * */
        $category = new Category();
        $form = $this->createForm(new CategoryType, $category);
        $request = $this->get('request_stack')->getCurrentRequest();

        if ($request->isXmlHttpRequest()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $category = $form->getData();
                $manager_category->persist($category);
                $categorys = $manager_category->AllCategorys();
                return $this->container->get('templating')->renderResponse('MyAppBackofficeBundle:manage/ajax_response:liste_category.html.twig', array(
                            'categorys' => $categorys
                ));
            }
        } elseif ($request->isMethod('Post')) {
            $form->bind($request);
            if ($form->isValid()) {
                $category = $form->getData();
                $manager_category->persist($category);
                $categorys = $manager_category->AllCategorys();
                return $this->redirect($this->generateUrl('my_app_backoffice_manage_category_add'));
            }
        } else {
            $categorys = $manager_category->AllCategorys();
            return $this->render('MyAppBackofficeBundle:manage:category.html.twig', array('form' => $form->createView(), 'categorys' => $categorys));
        }
    }

    public function categorydeleteAction($id, Request $request) {
        $manager_category = $this->get('entities');
        /** equivalent de em manager * */
        if (!$manager_category->OneCategory($id)) {
            throw $this->createNotFoundException('No Menu found for id ' . $id);
        } else {
            if ($request->isXmlHttpRequest()) {
                $manager_category->remove($manager_category->OneCategory($id));
                $categorys = $manager_category->AllCategorys();
                return $this->container->get('templating')->renderResponse('MyAppBackofficeBundle:manage/ajax_response:liste_category.html.twig', array(
                            'categorys' => $categorys
                ));
            }
        }

        $form = $this->createForm(new CategoryType);
        $categorys = $manager_category->AllCategorys();
        return $this->render('MyAppBackofficeBundle:manage:category.html.twig', array('form' => $form->createView(), 'categorys' => $categorys));
    }

    /* public function categoryeditAction($id, Request $request)
      {
      $manager_category = $this->get('entities');

      $form = $this->createFormBuilder($manager_category->OneCategory($id))
      ->add('nom', 'text', array('required' => TRUE))
      ->add('position', 'integer', array('required' => TRUE))
      ->getForm();

      if (!$manager_category->OneCategory($id)) {
      throw $this->createNotFoundException('No Menu found for id ' . $id);
      } elseif ($manager_category->OneCategory($id) != NULL) {


      if ($request->isXmlHttpRequest()) {
      $form->bind($request);
      if ($form->isValid()) {
      $manager_category->flush();
      $categorys = $manager_category->AllCategorys();
      return $this->container->get('templating')->renderResponse('MyAppBackofficeBundle:manage/ajax_response:liste_category.html.twig', array(
      'categorys' => $categorys
      ));
      }
      } elseif ($request->isMethod('Post')) {

      if ($form->isValid()) {
      $manager_category->flush();
      $categorys = $manager_category->AllCategorys();
      return $this->render('MyAppBackofficeBundle:manage:category.html.twig', array('form' => $form->createView(), 'categorys' => $categorys));
      }
      }
      }

      $categorys = $manager_category->AllCategorys();
      return $this->render('MyAppBackofficeBundle:manage:category.html.twig', array('form' => $form->createView(), 'categorys' => $categorys));

      } */

    public function produitaddAction() {
        $manager_produit = $this->get('entities');
        $produit = new Produit();
        $form = $this->createForm(new ProduitType, $produit);
        $request = $this->get('request_stack')->getCurrentRequest();

        if ($request->isXmlHttpRequest()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $produit = $form->getData();
                $manager_produit->persist($produit);
                $produits = $manager_produit->AllProduits();
                return $this->container->get('templating')->renderResponse('MyAppBackofficeBundle:manage/ajax_response:liste_product.html.twig', array(
                            'produits' => $produits
                ));
            }
        } elseif ($request->isMethod('Post')) {
            $form->bind($request);
            if ($form->isValid()) {
                $produit = $form->getData();
                $manager_produit->persist($produit);
                $produits = $manager_produit->AllProduits();
                return $this->redirect($this->generateUrl('my_app_backoffice_manage_produit_add'));
            }
        } else {
            $produits = $manager_produit->AllProduits();
            return $this->render('MyAppBackofficeBundle:manage:product.html.twig', array('form' => $form->createView(), 'produits' => $produits));
        }
    }

    public function produitdeleteAction($id, Request $request) {
        $manager_produit = $this->get('entities');
        if (!$manager_produit->OneProduit($id)) {
            throw $this->createNotFoundException('No Menu found for id ' . $id);
        } else {
            if ($request->isXmlHttpRequest()) {
                $manager_produit->remove($manager_produit->OneProduit($id));
                $produits = $manager_produit->AllProduits();
                return $this->container->get('templating')->renderResponse('MyAppBackofficeBundle:manage/ajax_response:liste_product.html.twig', array(
                            'produits' => $produits
                ));
            }
        }

        $form = $this->createForm(new ProduitType);
        $produits = $manager_produit->AllProduits();
        return $this->render('MyAppBackofficeBundle:manage:product.html.twig', array('form' => $form->createView(), 'produits' => $produits));
    }

}
