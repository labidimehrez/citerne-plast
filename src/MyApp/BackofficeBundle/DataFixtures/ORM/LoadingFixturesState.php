<?php

namespace MyApp\BackofficeBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MyApp\BackofficeBundle\Entity\State;

class LoadingFixturesMessage implements FixtureInterface {

    public function load(ObjectManager $manager) {


        $sql = 'TRUNCATE TABLE state;';
        $connection = $manager->getConnection();
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        $stmt->closeCursor();


        $State1 = new State();
        $State1->setNomState('Featured products');
        $manager->persist($State1);

        $State2 = new State();
        $State2->setNomState('On-Sale Products');
        $manager->persist($State2);

        $State3 = new State();
        $State3->setNomState('Top Rated Products');
        $manager->persist($State3);

        $manager->flush();
        /* $utilisateur1 = new Utilisateur();
          $utilisateur1->setLogin('labidimehrez');
          $utilisateur1->setEmail('mehrez.labidi@esprit.tn');
          $password1 = 'mehrez.labidi@esprit.tn';
          $utilisateur1->setPassword(sha1(md5($password1)));
          $utilisateur1->setPrivilege('ADMIN');
          $utilisateur1->setEnabled(TRUE);
          $manager->persist($utilisateur1);
          $manager->flush(); */
    }

}
