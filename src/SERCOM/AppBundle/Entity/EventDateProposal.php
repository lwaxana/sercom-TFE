<?php

namespace SERCOM\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * EventDateProposal
 *
 * @ORM\Table(name="event_date_proposal")
 * @ORM\Entity(repositoryClass="SERCOM\AppBundle\Entity\Repository\EventDateProposalRepository")
 */
class EventDateProposal
{
    /**
     * @var integer
     * @ORM\Column(name="evt_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $evt_id;
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\DateTime()
     * @ORM\Column(name="datehour", type="datetime", nullable=false)
     */
    private $datehour;

    /**
     * @var
     * @ORM\ManyToOne(targetEntity="SERCOM\AppBundle\Entity\Event", inversedBy="date_proposals", cascade={"all"})
     * @ORM\JoinColumns({@ORM\JoinColumn(name="event_id", referencedColumnName="event_id", nullable=false)})
     */
    private $events;
    /**
     * @var
     * @ORM\ManyToMany(targetEntity="SERCOM\AppBundle\Entity\Member", mappedBy="dateproposals")
     */
    private $member;



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->member = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get evt_id
     *
     * @return integer 
     */
    public function getEvtId()
    {
        return $this->evt_id;
    }

    /**
     * Set datehour
     *
     * @param \DateTime $datehour
     * @return EventDateProposal
     */
    public function setDatehour($datehour)
    {
        $this->datehour = $datehour;

        return $this;
    }

    /**
     * Get datehour
     *
     * @return \DateTime 
     */
    public function getDatehour()
    {
        return $this->datehour;
    }

    /**
     * Set events
     *
     * @param \SERCOM\AppBundle\Entity\Event $events
     * @return EventDateProposal
     */
    public function setEvents(\SERCOM\AppBundle\Entity\Event $events)
    {
        $this->events = $events;

        return $this;
    }

    /**
     * Get events
     *
     * @return \SERCOM\AppBundle\Entity\Event 
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * Add member
     *
     * @param \SERCOM\AppBundle\Entity\Member $member
     * @return EventDateProposal
     */
    public function addMember(\SERCOM\AppBundle\Entity\Member $member)
    {
        $this->member[] = $member;

        return $this;
    }

    /**
     * Remove member
     *
     * @param \SERCOM\AppBundle\Entity\Member $member
     */
    public function removeMember(\SERCOM\AppBundle\Entity\Member $member)
    {
        $this->member->removeElement($member);
    }

    /**
     * Get member
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMember()
    {
        return $this->member;
    }

    public function addEvent(Event $event){
        if (!$this->events->contains($event)){
            $this->events->add($event);
        }
    }

}
