<?php

use Symfony\Component\DependencyInjection\ContainerInterface as Container;

namespace MyApp\BackofficeBundle\Services;

class Mail {

protected $container;
 
    public function __construct($container) 
    {
        $this->container = $container;
    }

    public function envoiMail($user) {
        $mailer = $this->container->get('mailer');
        $transport = \Swift_SmtpTransport::newInstance('smtp.gmail.com',465,'ssl')
            ->setUsername('mehrez.labidi@esprit.tn')
            ->setPassword('ak47ak47ak47');
        $mailer = \Swift_Mailer::newInstance($transport);
        $message = \Swift_Message::newInstance('Test')
                ->setSubject('Confirmation de l\'inscription')
                ->setFrom('mehrez.labidi@esprit.tn')
                ->setTo($user->getEmail())
                ->setBody('Pour activer votre compte veuiller cliquer sur le lien ');
      //  $this->get('mailer')->send($message);
        $result = $mailer->send($message);
        return $result;
        /**      mail de confirmation    * */
    }

}
