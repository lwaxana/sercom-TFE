<?php

namespace SERCOM\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="member")
 * @ORM\Entity(repositoryClass="SERCOM\AppBundle\Entity\Repository\MemberRepository")
 *
 */
class Member
{
    /**
     * @var Person
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="SERCOM\AppBundle\Entity\Person", inversedBy="member")
     * @ORM\JoinColumn(name="person_id", referencedColumnName="person_id", unique=true)
     */
    private $person;
    /**
     * @var string
     * @ORM\Column(name="actif", type="boolean", nullable=false)
     */
    private $actif;
    /**
     * @var
     * @Assert\Length(min="2")
     * @ORM\Column(name="profstatus", type="string", length=255, nullable=true)
     */
    private $profstatus;
    /**
     * @var
     * @ORM\ManyToMany(targetEntity="SERCOM\AppBundle\Entity\AsblFunction", inversedBy="members", cascade={"all"})
     * @ORM\JoinTable(name="member_asblfunction",
     * 	joinColumns={
     *		@ORM\JoinColumn(name="person_id", referencedColumnName="person_id")},
     *       inverseJoinColumns={
     *		@ORM\JoinColumn(name="asblfunction_id", referencedColumnName="asblfunction_id")})
     */
    private $asblfunctions;
    /**
     * @var
     * @ORM\OneToMany(targetEntity="SERCOM\AppBundle\Entity\ReceiveMessage", mappedBy="member")
     */
    private $privatemessagesreceive;
    /**
     * @var
     * @ORM\OneToMany(targetEntity="SERCOM\AppBundle\Entity\PrivateMessage", mappedBy="sender")
     */
    private $privatemessages;
    /**
     * @var
     * @ORM\ManyToMany(targetEntity="SERCOM\AppBundle\Entity\ForumGroup", inversedBy="members")
     * @ORM\JoinTable(name="member_forumgroup",
     * 	joinColumns={
     *		@ORM\JoinColumn(name="person_id", referencedColumnName="person_id")},
     *       inverseJoinColumns={
     *		@ORM\JoinColumn(name="forumgroup_id", referencedColumnName="forumgroup_id")})
     */
    private $forumgroups;

    /**
     * @var
     * @ORM\ManyToMany(targetEntity="SERCOM\AppBundle\Entity\EventDateProposal", inversedBy="member")
     * @ORM\JoinTable(name="member_dateprop",
     * 	joinColumns={
     *		@ORM\JoinColumn(name="person_id", referencedColumnName="person_id")},
     *       inverseJoinColumns={
     *		@ORM\JoinColumn(name="evt_id", referencedColumnName="evt_id")})
     */
    private $dateproposals;
    /**
     * @var
     * @ORM\OneToMany(targetEntity="SERCOM\AppBundle\Entity\ForumTopic", mappedBy="member")
     */
    private $forumtopics;

    /**
     * @var
     * @ORM\OneToMany(targetEntity="SERCOM\AppBundle\Entity\ForumPost", mappedBy="member")
     */
    private $forumposts;
    /**
     * @var
     * @ORM\OneToMany(targetEntity="SERCOM\AppBundle\Entity\SiteArticle", mappedBy="member")
     */
    private $sitearticles;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->asblfunctions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->privatemessagesreceive = new \Doctrine\Common\Collections\ArrayCollection();
        $this->privatemessages = new \Doctrine\Common\Collections\ArrayCollection();
        $this->forumgroups = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set actif
     *
     * @param boolean $actif
     * @return Member
     */
    public function setActif($actif)
    {
        $this->actif = $actif;

        return $this;
    }

    /**
     * Get actif
     *
     * @return boolean 
     */
    public function getActif()
    {
        return $this->actif;
    }

    /**
     * Set profstatus
     *
     * @param string $profstatus
     * @return Member
     */
    public function setProfstatus($profstatus)
    {
        $this->profstatus = $profstatus;

        return $this;
    }

    /**
     * Get profstatus
     *
     * @return string 
     */
    public function getProfstatus()
    {
        return $this->profstatus;
    }

    /**
     * Set person
     *
     * @param \SERCOM\AppBundle\Entity\Person $person
     * @return Member
     */
    public function setPerson(\SERCOM\AppBundle\Entity\Person $person)
    {
        $this->person = $person;

        return $this;
    }

    /**
     * Get person
     *
     * @return \SERCOM\AppBundle\Entity\Person 
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * Add asblfunctions
     *
     * @param \SERCOM\AppBundle\Entity\AsblFunction $asblfunctions
     * @return Member
     */
    public function addAsblfunction(\SERCOM\AppBundle\Entity\AsblFunction $asblfunctions)
    {
        $this->asblfunctions[] = $asblfunctions;

        return $this;
    }

    /**
     * Remove asblfunctions
     *
     * @param \SERCOM\AppBundle\Entity\AsblFunction $asblfunctions
     */
    public function removeAsblfunction(\SERCOM\AppBundle\Entity\AsblFunction $asblfunctions)
    {
        $this->asblfunctions->removeElement($asblfunctions);
    }

    /**
     * Get asblfunctions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAsblfunctions()
    {
        return $this->asblfunctions;
    }

    /**
     * Add privatemessagesreceive
     *
     * @param \SERCOM\AppBundle\Entity\ReceiveMessage $privatemessagesreceive
     * @return Member
     */
    public function addPrivatemessagesreceive(\SERCOM\AppBundle\Entity\ReceiveMessage $privatemessagesreceive)
    {
        $this->privatemessagesreceive[] = $privatemessagesreceive;

        return $this;
    }

    /**
     * Remove privatemessagesreceive
     *
     * @param \SERCOM\AppBundle\Entity\ReceiveMessage $privatemessagesreceive
     */
    public function removePrivatemessagesreceive(\SERCOM\AppBundle\Entity\ReceiveMessage $privatemessagesreceive)
    {
        $this->privatemessagesreceive->removeElement($privatemessagesreceive);
    }

    /**
     * Get privatemessagesreceive
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPrivatemessagesreceive()
    {
        return $this->privatemessagesreceive;
    }

    /**
     * Add privatemessages
     *
     * @param \SERCOM\AppBundle\Entity\PrivateMessage $privatemessages
     * @return Member
     */
    public function addPrivatemessage(\SERCOM\AppBundle\Entity\PrivateMessage $privatemessages)
    {
        $this->privatemessages[] = $privatemessages;

        return $this;
    }

    /**
     * Remove privatemessages
     *
     * @param \SERCOM\AppBundle\Entity\PrivateMessage $privatemessages
     */
    public function removePrivatemessage(\SERCOM\AppBundle\Entity\PrivateMessage $privatemessages)
    {
        $this->privatemessages->removeElement($privatemessages);
    }

    /**
     * Get privatemessages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPrivatemessages()
    {
        return $this->privatemessages;
    }

    /**
     * Add forumgroups
     *
     * @param \SERCOM\AppBundle\Entity\ForumGroup $forumgroups
     * @return Member
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
     * Add dateproposals
     *
     * @param \SERCOM\AppBundle\Entity\EventDateProposal $dateproposals
     * @return Member
     */
    public function addDateproposal(\SERCOM\AppBundle\Entity\EventDateProposal $dateproposals)
    {
        $this->dateproposals[] = $dateproposals;

        return $this;
    }

    /**
     * Remove dateproposals
     *
     * @param \SERCOM\AppBundle\Entity\EventDateProposal $dateproposals
     */
    public function removeDateproposal(\SERCOM\AppBundle\Entity\EventDateProposal $dateproposals)
    {
        $this->dateproposals->removeElement($dateproposals);
    }

    /**
     * Get dateproposals
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDateproposals()
    {
        return $this->dateproposals;
    }

    public function getPersonId(){
        $str = strval($this->person->getPersonId());
        return $str;
    }

    /**
     * Add forumtopics
     *
     * @param \SERCOM\AppBundle\Entity\ForumTopic $forumtopics
     * @return Member
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

    /**
     * Add forumposts
     *
     * @param \SERCOM\AppBundle\Entity\ForumPost $forumposts
     * @return Member
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

    /**
     * Add sitearticles
     *
     * @param \SERCOM\AppBundle\Entity\SiteArticle $sitearticles
     * @return Member
     */
    public function addSitearticle(\SERCOM\AppBundle\Entity\SiteArticle $sitearticles)
    {
        $this->sitearticles[] = $sitearticles;

        return $this;
    }

    /**
     * Remove sitearticles
     *
     * @param \SERCOM\AppBundle\Entity\SiteArticle $sitearticles
     */
    public function removeSitearticle(\SERCOM\AppBundle\Entity\SiteArticle $sitearticles)
    {
        $this->sitearticles->removeElement($sitearticles);
    }

    /**
     * Get sitearticles
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSitearticles()
    {
        return $this->sitearticles;
    }
}
