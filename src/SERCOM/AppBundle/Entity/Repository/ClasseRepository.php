<?php

namespace SERCOM\AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * ClasseRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ClasseRepository extends EntityRepository{

    public function findClasses($page = 1, $maxperpage = 10){
        $query = $this->_em->createQueryBuilder()->select('m')->from('SERCOMAppBundle:Classe','m');

        $query->setFirstResult(($page-1) * $maxperpage)
            ->setMaxResults($maxperpage);

        return new Paginator($query);
    }

    public function countClasses(){
        $query = $this->_em->createQuery('SELECT COUNT(m) FROM SERCOMAppBundle:Classe m');
        return $query->getSingleScalarResult();
    }


    public function search($search){

        $query = $this->_em->createQueryBuilder()->select('c')
            ->from('SERCOMAppBundle:Classe','c')
            ->join('c.teacher','t')
            ->join('t.person','p')
            ->where('p.lastname LIKE ?1 OR p.firstname LIKE ?2 AND t.actif = 1 OR c.name LIKE ?3')
            ->groupBy('p')
            ->setParameters(array(1 => '%'.$search.'%' , 2 => '%'.$search.'%',  3 => '%'.$search.'%' ));

        return $query->getQuery()->getResult();
    }
}
