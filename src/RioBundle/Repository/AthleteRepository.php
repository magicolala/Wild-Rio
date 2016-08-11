<?php

namespace RioBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * AthleteRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AthleteRepository extends EntityRepository
{
    public function FindAllByEpreuve($idEpreuve)
    {
       $qb = $this->createQueryBuilder('a');

       $qb->select('a.nom')
        ->where('a.epreuve = ?1')
        ->setParameters(array( 1 => $idEpreuve));

       return $qb->getQuery()->getResult();
    }
}