<?php

namespace SERCOM\AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Event
 *
 * @ORM\Table(name="event")
 * @ORM\Entity(repositoryClass="SERCOM\AppBundle\Entity\Repository\EventRepository")
 */
class Event
{
    /**
     * @var integer
     * @ORM\Column(name="event_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $event_id;
    /**
     * @var string
     * @Assert\Length(min = "5", max="100")
     * @Assert\NotBlank()
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name;
    /**
     * @var string
     * @ORM\Column(name="desription", type="string", length=255, nullable=true)
     */
    private $description;
    /**
     * @var string
     * @ORM\Column(name="validate", type="boolean", nullable=false)
     */
    private $validate;
    /**
     * @var string
     * @ORM\Column(name="datehourevent", type="datetime", nullable=false)
     */
    private $dateHourEvent;
    /**
     * @ORM\ManyToOne(targetEntity="SERCOM\AppBundle\Entity\Member")
     * @ORM\JoinColumns({@ORM\JoinColumn(name="person_id", referencedColumnName="person_id", nullable=false)})
     */
    private $member;
    /**
     * @ORM\OneToMany(targetEntity="SERCOM\AppBundle\Entity\EventDateProposal", mappedBy="events", cascade={"all"})
     */
    private $date_proposals;



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->date_proposals = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get event_id
     *
     * @return integer 
     */
    public function getEventId()
    {
        return $this->event_id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Event
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
     * @return Event
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
     * Set validate
     *
     * @param boolean $validate
     * @return Event
     */
    public function setValidate($validate)
    {
        $this->validate = $validate;

        return $this;
    }

    /**
     * Get validate
     *
     * @return boolean 
     */
    public function getValidate()
    {
        return $this->validate;
    }

    /**
     * Set dateHourEvent
     *
     * @param \DateTime $dateHourEvent
     * @return Event
     */
    public function setDateHourEvent($dateHourEvent)
    {
        $this->dateHourEvent = $dateHourEvent;

        return $this;
    }

    /**
     * Get dateHourEvent
     *
     * @return \DateTime 
     */
    public function getDateHourEvent()
    {
        return $this->dateHourEvent;
    }

    /**
     * Set member
     *
     * @param \SERCOM\AppBundle\Entity\Member $member
     * @return Event
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
     * Add date_proposals
     *
     * @param \SERCOM\AppBundle\Entity\EventDateProposal $dateProposals
     * @return Event
     */
    public function addDateProposal(\SERCOM\AppBundle\Entity\EventDateProposal $dateProposals)
    {
        $this->date_proposals[] = $dateProposals;

        return $this;
    }

    /**
     * Remove date_proposals
     *
     * @param \SERCOM\AppBundle\Entity\EventDateProposal $dateProposals
     */
    public function removeDateProposal(\SERCOM\AppBundle\Entity\EventDateProposal $dateProposals)
    {
        $this->date_proposals->removeElement($dateProposals);
    }

    /**
     * Get date_proposals
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDateProposals()
    {
        return $this->date_proposals;
    }

    public function setDateProposals(ArrayCollection $dateproposals){
        foreach( $dateproposals as $dateproposal){
            $dateproposal->setEvents($this);
        }
        return $this->date_proposals = $dateproposals;
    }
}
