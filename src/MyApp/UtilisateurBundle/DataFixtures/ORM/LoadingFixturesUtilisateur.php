<?php

namespace MyApp\UtilisateurBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MyApp\UtilisateurBundle\Entity\Utilisateur;

class LoadingFixturesUtilisateur implements FixtureInterface
{

    public function load(ObjectManager $manager)
    {


        $sql = 'TRUNCATE TABLE utilisateur;';
        $connection = $manager->getConnection();
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        $stmt->closeCursor();


        $utilisateur1 = new Utilisateur();
        $utilisateur1->setLogin('labidimehrez');
        $utilisateur1->setEmail('mehrez.labidi@esprit.tn');
        $password1 = 'mehrez.labidi@esprit.tn';
        $utilisateur1->setPassword(sha1(md5($password1)));
        $utilisateur1->setPrivilege('ADMIN');
        $utilisateur1->setEnabled(TRUE);
        $utilisateur1->setDatelog(new \DateTime());

        $manager->persist($utilisateur1);


        $manager->flush();
    }

}

                                                        