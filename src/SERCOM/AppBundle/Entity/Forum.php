<?php

namespace SERCOM\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * Forum
 *
 * @ORM\Table(name="forum")
 * @ORM\Entity(repositoryClass="SERCOM\AppBundle\Entity\Repository\ForumRepository")
 * @UniqueEntity("name");
 */
class Forum
{
    /**
     * @var integer
     * @ORM\Column(name="forum_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $forum_id;
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min = "5", max="50")
     * @ORM\Column(name="name", type="string", length=50, nullable=false, unique=true)
     */
    private $name;
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min = "5", max="255")
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     */
    private $description;
    /**
     * @var
     * @ORM\ManyToMany(targetEntity="SERCOM\AppBundle\Entity\ForumGroup", inversedBy="forums")
     * @ORM\JoinTable(name="forum_forumgroup",
     * 	joinColumns={
     *		@ORM\JoinColumn(name="forum_id", referencedColumnName="forum_id")},
     *       inverseJoinColumns={
     *		@ORM\JoinColumn(name="forumgroup_id", referencedColumnName="forumgroup_id")})
     */
    private $forumgroups;
    /**
     * @var
     * @ORM\OneToMany(targetEntity="SERCOM\AppBundle\Entity\ForumTopic", mappedBy="forum", cascade={"remove"})
     */
    private $forumtopics;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->forumgroups = new \Doctrine\Common\Collections\ArrayCollection();
        $this->forumtopics = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get forum_id
     *
     * @return integer 
     */
    public function getForumId()
    {
        return $this->forum_id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Forum
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Forum
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Add forumgroups
     *
     * @param \SERCOM\AppBundle\Entity\ForumGroup $forumgroups
     * @return Forum
     */
    public function addForumgroup(\SERCOM\AppBundle\Entity\ForumGroup $forumgroups)
    {
        $this->forumgroups[] = $forumgroups;

        return $this;
    }

    /**
     * Remove forumgroups
     *
     * @param \SERCOM\AppBundle\Entity\ForumGroup $forumgroups
     */
    public function removeForumgroup(\SERCOM\AppBundle\Entity\ForumGroup $forumgroups)
    {
        $this->forumgroups->removeElement($forumgroups);
    }

    /**
     * Get forumgroups
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getForumgroups()
    {
        return $this->forumgroups;
    }

    /**
     * Add forumtopics
     *
     * @param \SERCOM\AppBundle\Entity\ForumTopic $forumtopics
     * @return Forum
     */
    public function addForumtopic(\SERCOM\AppBundle\Entity\ForumTopic $forumtopics)
    {
        $this->forumtopics[] = $forumtopics;

        return $this;
    }

    /**
     * Remove forumtopics
     *
     * @param \SERCOM\AppBundle\Entity\ForumTopic $forumtopics
     */
    public function removeForumtopic(\SERCOM\AppBundle\Entity\ForumTopic $forumtopics)
    {
        $this->forumtopics->removeElement($forumtopics);
    }

    /**
     * Get forumtopics
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getForumtopics()
    {
        return $this->forumtopics;
    }




}
