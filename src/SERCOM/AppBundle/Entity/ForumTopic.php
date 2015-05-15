<?php

namespace SERCOM\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ForumTopic
 *
 * @ORM\Table(name="forum_topic")
 * @ORM\Entity(repositoryClass="SERCOM\AppBundle\Entity\Repository\ForumTopicRepository")
 */
class ForumTopic
{
    /**
     * @var integer
     * @ORM\Column(name="topic_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $topic_id;
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min ="10", max="255")
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    private $title;
    /**
     * @var string
     * @ORM\Column(name="datetopic", type="datetime", nullable=false)
     */
    private $dateTopic;
    /**
     * @var string
     * @ORM\Column(name="locked", type="boolean",nullable=false)
     */
    private $locked;
    /**
     * @var string
     * @ORM\Column(name="priority", type="integer", nullable=false)
     */
    private $priority;
    /**
     * @var
     * @ORM\ManyToOne(targetEntity="SERCOM\AppBundle\Entity\Member", inversedBy="forumtopics")
     * @ORM\JoinColumns({
     * 	@ORM\JoinColumn(name="person_id", referencedColumnName="person_id", nullable=false)})
     */
    private $member;
    /**
     * @var
     * @ORM\ManyToOne(targetEntity="SERCOM\AppBundle\Entity\Forum", inversedBy="forumtopics")
     * @ORM\JoinColumns({
     * 	@ORM\JoinColumn(name="forum_id", referencedColumnName="forum_id", nullable=false)})
     */
    private $forum;
    /**
     * @var
     * @ORM\OneToMany(targetEntity="SERCOM\AppBundle\Entity\ForumPost", mappedBy="forumtopic", cascade={"remove"})
     */
    private $forumposts;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->forumposts = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get topic_id
     *
     * @return integer 
     */
    public function getTopicId()
    {
        return $this->topic_id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return ForumTopic
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set dateTopic
     *
     * @param \DateTime $dateTopic
     * @return ForumTopic
     */
    public function setDateTopic($dateTopic)
    {
        $this->dateTopic = $dateTopic;

        return $this;
    }

    /**
     * Get dateTopic
     *
     * @return \DateTime 
     */
    public function getDateTopic()
    {
        return $this->dateTopic;
    }

    /**
     * Set locked
     *
     * @param boolean $locked
     * @return ForumTopic
     */
    public function setLocked($locked)
    {
        $this->locked = $locked;

        return $this;
    }

    /**
     * Get locked
     *
     * @return boolean 
     */
    public function getLocked()
    {
        return $this->locked;
    }

    /**
     * Set priority
     *
     * @param integer $priority
     * @return ForumTopic
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority
     *
     * @return integer 
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set member
     *
     * @param \SERCOM\AppBundle\Entity\Member $member
     * @return ForumTopic
     */
    public function setMember(\SERCOM\AppBundle\Entity\Member $member)
    {
        $this->member = $member;

        return $this;
    }

    /**
     * Get member
     *
     * @return \SERCOM\AppBundle\Entity\Member 
     */
    public function getMember()
    {
        return $this->member;
    }

    /**
     * Set forum
     *
     * @param \SERCOM\AppBundle\Entity\Forum $forum
     * @return ForumTopic
     */
    public function setForum(\SERCOM\AppBundle\Entity\Forum $forum)
    {
        $this->forum = $forum;

        return $this;
    }

    /**
     * Get forum
     *
     * @return \SERCOM\AppBundle\Entity\Forum 
     */
    public function getForum()
    {
        return $this->forum;
    }

    /**
     * Add forumposts
     *
     * @param \SERCOM\AppBundle\Entity\ForumPost $forumposts
     * @return ForumTopic
     */
    public function addForumpost(\SERCOM\AppBundle\Entity\ForumPost $forumposts)
    {
        $this->forumposts[] = $forumposts;

        return $this;
    }

    /**
     * Remove forumposts
     *
     * @param \SERCOM\AppBundle\Entity\ForumPost $forumposts
     */
    public function removeForumpost(\SERCOM\AppBundle\Entity\ForumPost $forumposts)
    {
        $this->forumposts->removeElement($forumposts);
    }

    /**
     * Get forumposts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getForumposts()
    {
        return $this->forumposts;
    }
}
