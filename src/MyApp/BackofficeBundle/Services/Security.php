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
use Symfony\Component\DependencyInjection\ContainerInterface;

//use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Security {

    //put your code here
    protected $router;
    private $em;
    private $repository;
    private $container;

    public function __construct(EntityManager $em,ContainerInterface $container, $router ) {
        $this->em = $em;
        $this->repository = $em->getRepository('MyAppUtilisateurBundle:Utilisateur');
        $this->container = $container;
        $this->router = $router;
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
                $user->setDatelastlog($user->getDatecurrentlog());
                $user->setDatecurrentlog(new \DateTime());
                $this->doFlush($user);
                $access = TRUE;
            }
        }
        return $access;
    }

    public function doFlush($user)
    {
        $this->em->persist($user);
        $this->em->flush();
        return $user;
    }

    public function persistUser($user, $login, $email, $password)
    {


        $user->setLogin($login);
        $user->setEmail($email);
        $user->setPassword(sha1(md5($password)));
        $user->setPrivilege('ADMIN');
        $user->setEnabled(TRUE);
        $user->setDatelastlog($user->getDatecurrentlog());
        $user->setDatecurrentlog(new \DateTime());
        $this->doFlush($user);
    }

    public function persistclient($user, $email, $password)
    {


        $user->setEmail($email);
        $user->setPassword(sha1(md5($password)));
        $user->setPrivilege('USER');
        $user->setEnabled(TRUE);
        $user->setDatelastlog($user->getDatecurrentlog());
        $user->setDatecurrentlog(new \DateTime());
        $this->doFlush($user);
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

    public function securityadmin()
    {
        $userSession = $this->container->get('request_stack')->getCurrentRequest()->getSession()->get('user');
        $url = $this->router->generate('my_app_backoffice_login');
        if ((empty($userSession))) {
           // return $this->redirect($this->generateUrl('my_app_backoffice_login'));
            return $url;

        }
        if ((!empty($userSession))&&($userSession->getPrivilege() != 'ADMIN')) {
            // return $this->redirect($this->generateUrl('my_app_backoffice_login'));
            return $url;
        }
    }
}
