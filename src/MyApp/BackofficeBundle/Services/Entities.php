<?php

namespace MyApp\BackofficeBundle\Services;

use Doctrine\ORM\EntityManager;

class Entities {

    private $em;
    private $repository_message;
    private $repository_user;
   private $repository_category;
    private $repository_produit;
    private $repository_state;
    private $repository_departement;
    
    public function __construct(EntityManager $em) {
        $this->em = $em;
        $this->repository_message = $em->getRepository('MyAppFrontofficeBundle:Message');
        $this->repository_user = $em->getRepository('MyAppUtilisateurBundle:Utilisateur');
        
         $this->repository_category = $em->getRepository('MyAppBackofficeBundle:Category');
          $this->repository_produit = $em->getRepository('MyAppBackofficeBundle:Produit');
           $this->repository_state = $em->getRepository('MyAppBackofficeBundle:State');
          $this->repository_departement = $em->getRepository('MyAppBackofficeBundle:Departement');
    }

    public function AllMessages() {
        return $this->repository_message->findAll();
    }
      public function AllCategorys() {
        return $this->repository_category->findAll();
    }
    public function AllProduits() {
        return $this->repository_produit->findAll();
    }
       public function AllDepartements() {
        return $this->repository_departement->findAll();
    }
    public function AllStates() {
        return $this->repository_state->findAll();
    }
    
    
    
    
    public function AllUsers()
    {
        return $this->repository_user->findAll();
    }

    /*
      public function getOne($id)
      {
      return $this->repository->find($id);
      }


      public function persist($user)
      {
      $this->doFlush($user);
      }

      public function doFlush($user)
      {
      $this->em->persist($user);
      $this->em->flush();

      return $user;
      }

      public function remove($user)
      {
      $this->em->remove($user);
      $this->em->flush();
      }
     */
}
