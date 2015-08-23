<?php

namespace MyApp\BackofficeBundle\Controller;
use MyApp\BackofficeBundle\Entity\Category;
use MyApp\BackofficeBundle\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class manageController extends Controller {

    public function messageAction() {

        $manager_entities = $this->get('entities');/** equivalent de em manager * */
        $messages = $manager_entities->AllMessages();
        var_dump($messages);
        exit;
        // return $this->render('MyAppArticleBundle:style:show.html.twig', array(
        //    'messages' => $messages
        // ));
    }

    public function rubriqueAction() {
        
    }


    public function categoryAction() {
      //$manager_category = $this->get('entities');/** equivalent de em manager * */
      // $categorys = $manager_category->AllCategorys();
        $category = new Category();
        $form = $this->createForm(new CategoryType, $category);
        $request = $this->getRequest();
        if ($request->isMethod('Post')) {
            $form->bind($request);
            if ($form->isValid()) {
                $category = $form->getData();
                $em = $this->getDoctrine()->getManager();
                $em->persist($category);         
                $em->flush();
                return $this->redirect($this->generateUrl('my_app_backoffice_manage_category'));
            } 
        }
        
//        if ($request->isXmlHttpRequest()) {
//            $form->handleRequest($request);
//            if ($form->isValid()) {
//                $category = $form->getData();
//                $em->persist($category);
//                $em->flush();
//          
//                return $this->container->get('templating')->renderResponse('MyAppForumBundle:sujet/Commentaire:affichercommentaireajax.html.twig', array(
//                            'category' => $category
//                ));
//        }}
        
        
        
        return $this->render('MyAppBackofficeBundle:manage:category.html.twig', array('form' => $form->createView()));
    }

    public function produitAction()
    {

    }
}
