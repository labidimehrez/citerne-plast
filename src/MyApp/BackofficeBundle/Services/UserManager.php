<?php

namespace MyApp\UtilisateurBundle\Services;

use Doctrine\ORM\EntityManager;

class UserManager
{

    private $em;
    private $repository;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->repository = $em->getRepository('MyAppUtilisateurBundle:user');
    }

    public function getAll()
    {
        return $this->repository->findAll();
    }

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

}
