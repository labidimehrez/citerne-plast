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
/*********************************************************************************************************************/
    public function AllMessages() {
        return $this->repository_message->findAll();
    }

    public function OneMessage($id) {
        return $this->repository_message->findOneBy(array('id' => $id));
    }
/*********************************************************************************************************************/
    public function AllCategorys() {
        return $this->repository_category->findAll();
    }

    public function OneCategory($id) {
        return $this->repository_category->findOneBy(array('id' => $id));
    }
/*********************************************************************************************************************/
    public function AllProduits() {
        return $this->repository_produit->findAll();
    }
    
   public function ProduitByState($state) {
        $ProduitByState= $this->repository_produit->findBy(array('state' => $state));
        if(count($ProduitByState)>=3){
            return array( '0' => $ProduitByState[0],'1' =>  $ProduitByState[1],'2' =>  $ProduitByState[2]);          
        }
        else{return $ProduitByState;}
       // return $ProduitByState;
    }
    public function OneProduit($id) {
        return $this->repository_produit->findOneBy(array('id' => $id));
    }
/*********************************************************************************************************************/
    public function AllDepartements() {
        return $this->repository_departement->findAll();
    }

    public function OneDepartement($id) {
        return $this->repository_departement->findOneBy(array('id' => $id));
    }
/*********************************************************************************************************************/
    public function AllStates() {
        return $this->repository_state->findAll();
    }
  public function OneStateByName($name) {
        return $this->repository_state->findOneBy(array('nomState' => $name));
    }
    public function OneState($id) {
        return $this->repository_state->findOneBy(array('id' => $id));
    }
/*********************************************************************************************************************/
    public function AllUsers() {
        return $this->repository_user->findAll();
    }

    public function OneUser($id) {
        return $this->repository_user->findOneBy(array('id' => $id));
    }
/*********************************************************************************************************************/
    public function persist($x) {
        $this->doFlush($x);
    }

    public function doFlush($x) {
        $this->em->persist($x);
        $this->em->flush();

        return $x;
    }

    public function remove($x) {
        $this->em->remove($x);
        $this->em->flush();
    }

    public function flush() {
        $this->em->flush();
    }

}
