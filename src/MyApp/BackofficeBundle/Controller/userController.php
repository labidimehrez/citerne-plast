<?php

namespace MyApp\BackofficeBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MyApp\UtilisateurBundle\Form\UtilisateurType;
use MyApp\UtilisateurBundle\Entity\Utilisateur;


class userController extends Controller {

    public function showAction() {

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('MyAppUtilisateurBundle:Utilisateur')->findAll();
//        var_dump($user);exit;
        return $this->render('MyAppBackofficeBundle:usermanager:show.html.twig', array(
                    'users' => $user
        ));
    }
    public function deleteAction($id) {
        /*         * **** delete d'une user si il existe deja *** */
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('MyAppUtilisateurBundle:Utilisateur')->find($id);
        if (!$user) {
            throw $this->createNotFoundException('No user found for id ' . $id);
        }
        $em->remove($user);
        $em->flush();

        return $this->redirect($this->generateUrl('my_app_backoffice_user_show'));
    }
    
        public function editAction($id, Request $request) {

        $em = $this->getDoctrine()->getManager();
       $user = $em->getRepository('MyAppUtilisateurBundle:Utilisateur')->find($id);
        if (!$user) {
            throw $this->createNotFoundException('No user found for id ' . $id);
        }

        $form = $this->createFormBuilder($user)
                ->add('login', 'text')
              ->add('password', 'text')
                ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->flush();
            return $this->redirect($this->generateUrl('my_app_backoffice_user_show'));
        }

        return $this->render('MyAppBackofficeBundle:usermanager:edit.html.twig', array('form' => $form->createView()));
    }
    
       public function addAction() {
         $em = $this->getDoctrine()->getManager();
        $user = new Utilisateur();
        $form = $this->createForm(new UtilisateurType, $user);
        $request = $this->getRequest();
        if ($request->isMethod('Post')) {
            $form->bind($request);
            if ($form->isValid()) {
                $user = $form->getData();
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                return $this->redirect($this->generateUrl('my_app_backoffice_user_show'));
            }
        } 
        return $this->render('MyAppBackofficeBundle:usermanager:add.html.twig', array(
               'form' => $form->createView(),
        ));
    }

    
}
