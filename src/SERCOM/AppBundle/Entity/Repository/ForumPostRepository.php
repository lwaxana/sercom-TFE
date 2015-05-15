<?php

namespace SERCOM\AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * ForumPostRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ForumPostRepository extends EntityRepository{

    public function getOrdererPosts($topic, $page = 1, $maxperpage){
        $query = $this->createQueryBuilder('a')->where('a.forumtopic = ?1')->addOrderBy('a.datePost', 'ASC')->setParameter(1, $topic);
        $query->setFirstResult(($page-1) * $maxperpage)->setMaxResults($maxperpage);

        return new Paginator($query);

    }

    public function getLastPosts($forum, $prio){
        $query = $this->_em->createQuery('SELECT p FROM SERCOMAppBundle:ForumPost p JOIN p.forumtopic t WHERE t.forum = ?1 AND t.priority = ?2 GROUP BY t.topic_id ORDER BY p.datePost DESC ')
            ->setParameters(array( 1 => $forum, 2 => $prio));
        return $query->getResult();
    }

    public function getLastPostsPage($forum, $page = 1, $maxperpage = 10){

        //SELECT * FROM forum_post WHERE datepost IN ( SELECT max(datepost) FROM forum_post WHERE topic_id IN ( SELECT topic_id FROM forum_topic WHERE forum_id = 1 AND priority = 3) GROUP BY topic_id
        //ORDER BY max(datepost) DESC ) ORDER BY datepost DESC

        $subsubquery = $this->createQueryBuilder('a') ;
        $subsubquery->select('t')
                    ->from('SERCOMAppBundle:ForumTopic','t')
                    ->where('t.forum = ?1')
                    ->andWhere('t.priority = 3')
                    ->setParameter(1, $forum);

        $test = $subsubquery->getQuery()->getResult();
        $t = array_map('current', $test);

        $subquery = $this->createQueryBuilder('b');
        $subquery ->select('MAX(p.datePost) as dp')
                  ->from('SERCOMAppBundle:ForumPost','p')
                  ->add('where', $subquery->expr()->in('p.forumtopic', $t) )
                  ->addGroupBy('p.forumtopic')
                  ->addOrderBy('dp','DESC');

        $test2 = $subquery->getQuery()->getResult();
        $t2 = array_map('current', $test2);


        $query = $this->_em->createQueryBuilder();
        $query->select('z')
                ->from('SERCOMAppBundle:ForumPost','z')
              ->add('where', $query->expr()->in('z.datePost', $t2))
              ->addOrderBy('z.datePost','DESC');

        $query->setFirstResult(($page-1) * $maxperpage)->setMaxResults($maxperpage);
        return new Paginator($query);



    }

    public function countTopics($forum){
        $query = $this->_em->createQuery('SELECT COUNT(DISTINCT p.forumtopic ) as c FROM SERCOMAppBundle:ForumPost p JOIN p.forumtopic t WHERE t.forum = ?1 AND t.priority = 3 ')->setParameter(1, $forum)->getScalarResult();
        return array_map('current', $query);
    }

    public function countPosts($topic){
        $query = $this->_em->createQuery('SELECT COUNT(DISTINCT p ) as c FROM SERCOMAppBundle:ForumPost p WHERE p.forumtopic = ?1 ')->setParameter(1, $topic)->getScalarResult();
        return array_map('current', $query);
    }




}
