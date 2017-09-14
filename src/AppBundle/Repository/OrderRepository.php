<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class OrderRepository extends EntityRepository
{
    
    public function getOrdersPaginated($page, $pp, $sort, $sortOrder, $term, $count = false)
    {
        $qb = $this->createQueryBuilder('o');
        
        if($count) {
            $qb->select('COUNT(o.id)');
        }
        
        if($term) {
            $qb->where('o.name LIKE :term OR o.email LIKE :term OR o.total LIKE :term OR o.address LIKE :term')->setParameter('term', '%' . $term . '%');
        }
        
        switch ($sort) {
                case 'created':
                    $qb->orderBy('o.created', $sortOrder);
                    break;
                case 'name':
                    $qb->orderBy('o.name', $sortOrder);
                    break;
                case 'email':
                    $qb->orderBy('o.email', $sortOrder);
                    break;
                case 'total':
                    $qb->orderBy('o.total', $sortOrder);
                    break;
        }
        
        if (!$count) {
            return $qb
                    ->setFirstResult(($page - 1) * $pp)
                    ->setMaxResults($pp)
                    ->getQuery()
                    ->getResult();
        } else {
            return $qb->getQuery()->getSingleScalarResult();
        }
    }

}
