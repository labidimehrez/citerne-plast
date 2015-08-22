<?php

namespace MyApp\BackofficeBundle\Repository;

use Doctrine\ORM\EntityRepository;

class StateRepository extends EntityRepository
{

    /* public function getCommentaireBySujet($id)
     {
         return $this->getEntityManager()
             ->createQuery('  SELECT t   FROM MyAppForumBundle:commentaire t   WHERE (t.sujet=:id ) and ( t.commentaire IS NULL)  ORDER BY  t.datecreation DESC ')
             ->setParameter('id', $id)
             ->getResult();
     }*/
}
