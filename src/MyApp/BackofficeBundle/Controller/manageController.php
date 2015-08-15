<?php

namespace MyApp\BackofficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class manageController extends Controller
{


    public function messageAction()
    {

        $manager_entities = $this->get('entities');
        /** equivalent de em manager * */
        $messages = $manager_entities->AllMessages();
        var_dump($messages);
        exit;
        // return $this->render('MyAppArticleBundle:style:show.html.twig', array(
        //    'messages' => $messages
        // ));
    }


}
