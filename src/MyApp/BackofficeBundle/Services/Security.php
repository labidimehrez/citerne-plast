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

    public function getUserByPassword($password) {
        return $this->repository->findBy(array('password' => $password));
    }

    public function getUserByMail($email) {
        return $this->repository->findBy(array('email' => $email));
    }

    public function login($identifiant, $password) {
        $users = $this->repository->findAll();
        $access = FALSE;
        foreach ($users as $user) {
            $userlog = $user->getLogin();
            $usermail = $user->getEmail();
            $userpassword = $user->getPassword(); //

            if ((($identifiant === $userlog) && ($password === $userpassword ) ) || (($identifiant === $usermail) && ( $password === $userpassword ))) {
                $user->setDatelog(new \DateTime());
                $this->doFlush($user);
                $access = TRUE;
            }
        }
        return $access;
    }

    public function donneruservalid($user) {
        $users = $this->repository->findAll();
        $EXISTE = FALSE;
        foreach ($users as $u) {
            $userlog = $u->getLogin();
            $usermail = $u->getEmail();
            $userpassword = $u->getPassword(); //sha1(md5($user->getPassword()));
            if (($user->getLogin() === $userlog) || ($user->getPassword() === $userpassword ) || (($user->getEmail() === $usermail) )) {
                $EXISTE = TRUE;
            }
        }
        return $EXISTE;
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
