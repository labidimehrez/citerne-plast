<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Security
 *
 * @author Mehrez
 */

namespace MyApp\BackofficeBundle\Services;

use Doctrine\ORM\EntityManager;

//use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Security {

    //put your code here
    private $em;
    private $repository;

    public function __construct(EntityManager $em) {
        $this->em = $em;
        $this->repository = $em->getRepository('MyAppUtilisateurBundle:Utilisateur');
    }

    public function getAll() {
        return $this->repository->findAll();
    }

    public function login($identifiant, $password) {
        $users = $this->repository->findAll();

        foreach ($users as $user) {

            $userlog = $user->getLogin();
            $usermail = $user->getEmail();
            $userpassword = sha1(md5($user->getPassword()));
            
     //       var_dump($userpassword);
//     $user->setPassword(sha1(md5($password)));
            if ((($identifiant === $userlog) && ($userpassword === $password) ) || (($identifiant === $usermail) && ($userpassword === $password))) {
               // $authentifsucces = 
                $user->setDatelog(new \DateTime());
                $this->doFlush($user);
                return TRUE;
                // $em->persist($user);
                //$em->flush();
                // return $user;
            } else {
               return FALSE;
            }
        }
        
    }

    public function persist($user) {
        $this->doFlush($user);
    }

    public function remove($user) {
        $this->em->remove($user);
        $this->em->flush();
    }

    public function doFlush($user) {
        $this->em->persist($user);
        $this->em->flush();
        return $user;
    }

}
