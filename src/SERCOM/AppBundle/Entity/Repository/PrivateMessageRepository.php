<?php

namespace SERCOM\AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * PrivateMessageRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PrivateMessageRepository extends EntityRepository{
    public function getMessages($id){
        $query = $this->_em->createQuery('SELECT r FROM SERCOMAppBundle:PrivateMessage r  WHERE r.sender = ?1 AND r.message_delete = 0 ORDER BY r.dateMessage DESC')
            ->setParameter(1, $id);
        return $query->getResult();
    }


}