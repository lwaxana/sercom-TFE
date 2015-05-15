<?php
/**
 * Created by PhpStorm.
 * User: Lwaxana
 * Date: 25/03/2015
 * Time: 12:00
 */

namespace SERCOM\AppBundle\Forum;
use SERCOM\AppBundle\Entity as Entity;


class IndexForum {

    private $forum;
    private $nbreposts;
    private $nbretopics;
    private $lastpost;
    private $topic;
    private $member;

    public function __construct(Entity\Forum $forum, Entity\ForumTopic $topic , Entity\ForumPost $post, Entity\Member $member, $nbretopics, $nbrepost){
        $this->forum = $forum;
        $this->lastpost = $post;
        $this->nbreposts = $nbrepost;
        $this->nbretopics = $nbretopics;
        $this->topic = $topic;
        $this->member = $member;
    }


    /**
     * @return mixed
     */
    public function getForum()
    {
        return $this->forum;
    }

    /**
     * @param mixed $forum
     */
    public function setForum($forum)
    {
        $this->forum = $forum;
    }

    /**
     * @return mixed
     */
    public function getNbreposts()
    {
        return $this->nbreposts;
    }

    /**
     * @param mixed $nbreposts
     */
    public function setNbreposts($nbreposts)
    {
        $this->nbreposts = $nbreposts;
    }

    /**
     * @return mixed
     */
    public function getLastpost()
    {
        return $this->lastpost;
    }

    /**
     * @param mixed $lastpost
     */
    public function setLastpost($lastpost)
    {
        $this->lastpost = $lastpost;
    }

    /**
     * @return mixed
     */
    public function getNbretopics()
    {
        return $this->nbretopics;
    }

    /**
     * @param mixed $nbretopics
     */
    public function setNbretopics($nbretopics)
    {
        $this->nbretopics = $nbretopics;
    }

    /**
     * @return Entity\ForumTopic
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * @param Entity\ForumTopic $topic
     */
    public function setTopic($topic)
    {
        $this->topic = $topic;
    }

    /**
     * @return Entity\Member
     */
    public function getMember()
    {
        return $this->member;
    }

    /**
     * @param Entity\Member $member
     */
    public function setMember($member)
    {
        $this->member = $member;
    }






} 