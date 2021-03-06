<?php

namespace SERCOM\AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use SERCOM\AppBundle\Entity\SiteGroup;

/**
 * SiteGroupRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SiteGroupRepository extends EntityRepository
{

    public function getRolesUser($id){
        $query = $this->_em->createQuery('SELECT f FROM SERCOMAppBundle:Sitegroup f JOIN f.persons p WHERE p.person_id = ?1 AND f.name LIKE ?2 ')
            ->setParameters(array( 1 => $id, 2 => 'ROLE%'));
        return $query->getResult();
    }

    public function getRolesAdmin($id){
        $query = $this->_em->createQuery('SELECT f FROM SERCOMAppBundle:Sitegroup f JOIN f.persons p WHERE p.person_id = ?1 AND f.name LIKE ?2 ')
            ->setParameters(array( 1 => $id, 2 => 'ADMIN%'));
        $tab = $query->getResult();
        $tab2 = array();
        foreach ($tab as $t){
            array_push($tab2, $t->getName());
        }
        return $tab2;
    }

    public function getMembersRoles(){
        $query = $this->_em->createQuery('SELECT f from SERCOMAppBundle:Sitegroup f WHERE f.name LIKE ?1 or f.name LIKE ?2 or f.name LIKE ?3 or f.name LIKE ?4')
            ->setParameters(array(1 =>'ROLE_PRESIDENT', 2 => 'ROLE_ANIMATEUR', 3 => 'ROLE_COMITE', 4 => 'ROLE_MEMBRE'));
        return $query->getResult();
    }

    public function getAdminRoles(){
        $query = $this->_em->createQuery('SELECT f from SERCOMAppBundle:Sitegroup f WHERE f.name LIKE ?1 or f.name LIKE ?2')
            ->setParameters(array(1 =>'ADMIN_%', 2 => 'MODERATION_FORUM'));
        return $query->getResult();
    }


}
