<?php

namespace MyApp\BackofficeBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadingFixturesMessage implements FixtureInterface
{

    public function load(ObjectManager $manager)
    {


        $sql = 'TRUNCATE TABLE category;';
        $connection = $manager->getConnection();
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        $stmt->closeCursor();


        /* $utilisateur1 = new Utilisateur();
         $utilisateur1->setLogin('labidimehrez');
         $utilisateur1->setEmail('mehrez.labidi@esprit.tn');
         $password1 = 'mehrez.labidi@esprit.tn';
         $utilisateur1->setPassword(sha1(md5($password1)));
         $utilisateur1->setPrivilege('ADMIN');
         $utilisateur1->setEnabled(TRUE);
         $manager->persist($utilisateur1);
         $manager->flush();*/
    }

}

                                                        