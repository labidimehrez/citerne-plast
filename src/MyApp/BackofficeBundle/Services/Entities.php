<?php

namespace MyApp\BackofficeBundle\Services;

use Doctrine\ORM\EntityManager;

class Entities
{
    private $em;
    private $repository;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->repository_message = $em->getRepository('MyAppFrontofficeBundle:Message');
    }

    public function AllMessages()
    {
        return $this->repository_message->findAll();
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
