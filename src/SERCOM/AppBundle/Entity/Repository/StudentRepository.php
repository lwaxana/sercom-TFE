<?php

namespace SERCOM\AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * StudentRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class StudentRepository extends EntityRepository{

    public function findStudents($page = 1, $maxperpage = 10){
        $query = $this->_em->createQueryBuilder()->select('m')->from('SERCOMAppBundle:Student','m')->join('m.person','p')->where('p.validate = 1 AND m.actif = 1');

        $query->setFirstResult(($page-1) * $maxperpage)
            ->setMaxResults($maxperpage);

        return new Paginator($query);
    }

    public function countStudents(){
        $query = $this->_em->createQuery('SELECT COUNT(m) FROM SERCOMAppBundle:Student m JOIN m.person p WHERE p.validate = 1 and m.actif = 1');
        return $query->getSingleScalarResult();
    }

    public function search($search){

        $query = $this->_em->createQueryBuilder()->select('m')  ->from('SERCOMAppBundle:Student','m')
                                                                ->join('m.person','p')
                                                                ->join('m.classes','c')
                                                                ->where('p.lastname LIKE ?1 OR p.firstname LIKE ?2 OR c.name LIKE ?3 AND m.actif = 1')
                                                                ->groupBy('m')
                                                                ->setParameters(array(1 => '%'.$search.'%' , 2 => '%'.$search.'%', 3 => '%'.$search.'%'));

       return $query->getQuery()->getResult();
    }


}
