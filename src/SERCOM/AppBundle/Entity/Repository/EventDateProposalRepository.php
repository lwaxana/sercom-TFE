<?php

namespace SERCOM\AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use PDO;

/**
 * EventDateProposalRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EventDateProposalRepository extends EntityRepository{

    public function getDates($id){
             $sql = "SELECT datehour FROM event_date_proposal WHERE event_id = ? ORDER BY datehour ASC";
            $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
            $stmt->bindValue(1, $id, PDO::PARAM_INT );
            $stmt->execute();
            return $stmt->fetchAll();

    }


}
