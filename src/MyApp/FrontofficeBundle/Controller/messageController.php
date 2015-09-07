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
            'form' => $form->createView(),
        ));
    }

}
