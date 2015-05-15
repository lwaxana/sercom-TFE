<?php

namespace SERCOM\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ForumGroup
 *
 * @ORM\Table(name="forum_group")
 * @ORM\Entity(repositoryClass="SERCOM\AppBundle\Entity\Repository\ForumGroupRepository")
 * @UniqueEntity("name")
 */
class ForumGroup
{
    /**
     * @var integer
     * @ORM\Column(name="forumgroup_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $forumgroup_id;
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min="5", max="50")
     * @ORM\Column(name="name", type="string", length=50, nullable=false, unique=true)
     */
    private $name;
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min="5", max="255")
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     */
    private $description;
    /**
     * @var
     * @ORM\ManyToMany(targetEntity="SERCOM\AppBundle\Entity\Forum", mappedBy="forumgroups")
     */
    private $forums;
    /**
     * @var
     * @ORM\ManyToMany(targetEntity="SERCOM\AppBundle\Entity\Member", mappedBy="forumgroups")
     */
    private $members;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->forums = new \Doctrine\Common\Collections\ArrayCollection();
        $this->members = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get forumgroup_id
     *
     * @return integer 
     */
    public function getForumgroupId()
    {
        return $this->forumgroup_id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return ForumGroup
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
     * @return ForumGroup
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
     * Add forums
     *
     * @param \SERCOM\AppBundle\Entity\Forum $forums
     * @return ForumGroup
     */
    public function addForum(\SERCOM\AppBundle\Entity\Forum $forums)
    {
        $this->forums[] = $forums;

        return $this;
    }

    /**
     * Remove forums
     *
     * @param \SERCOM\AppBundle\Entity\Forum $forums
     */
    public function removeForum(\SERCOM\AppBundle\Entity\Forum $forums)
    {
        $this->forums->removeElement($forums);
    }

    /**
     * Get forums
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getForums()
    {
        return $this->forums;
    }

    /**
     * Add members
     *
     * @param \SERCOM\AppBundle\Entity\Member $members
     * @return ForumGroup
     */
    public function addMember(\SERCOM\AppBundle\Entity\Member $members)
    {
        $this->members[] = $members;

        return $this;
    }

    /**
     * Remove members
     *
     * @param \SERCOM\AppBundle\Entity\Member $members
     */
    public function removeMember(\SERCOM\AppBundle\Entity\Member $members)
    {
        $this->members->removeElement($members);
    }

    /**
     * Get members
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMembers()
    {
        return $this->members;
    }
}
