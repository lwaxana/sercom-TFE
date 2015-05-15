<?php

namespace SERCOM\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * PrivateMessage
 *
 * @ORM\Table(name="private_message")
 * @ORM\Entity(repositoryClass="SERCOM\AppBundle\Entity\Repository\PrivateMessageRepository")
 */
class PrivateMessage
{
    /**
     * @var integer
     * @ORM\Column(name="mp_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $mp_id;
    /**
     * @var string
     * @Assert\Length(min = "2", max = "100")
     * @Assert\NotBlank()
     * @ORM\Column(name="subject", type="string", length=100, nullable=false)
     */
    private $subject;
    /**
     * @var string
     * @ORM\Column(name="datemessage", type="datetime", nullable=false)
     */
    private $dateMessage;
    /**
     * @var string
     * @Assert\Length(min = "2", max = "1000")
     * @Assert\NotBlank()
     * @ORM\Column(name="messagecontent", type="text", length=1000, nullable=false)
     */
    private $messagecontent;

    /**
     * @var string
     * @ORM\Column(name="msg_delete", type="boolean", nullable=false)
     */
    private $message_delete;


    /**
     * @var
     * @ORM\OneToMany(targetEntity="SERCOM\AppBundle\Entity\Attachement", mappedBy="privatemessage", cascade={"all"})
     */
    private $attachements;
    /**
     * @var
     * @ORM\ManyToOne(targetEntity="SERCOM\AppBundle\Entity\Member", inversedBy="privatemessages")
     * @ORM\JoinColumns({
     * 	@ORM\JoinColumn(name="person_id", referencedColumnName="person_id", nullable=false)})
     */
    private $sender;
    /**
     * @var
     * @ORM\OneToMany(targetEntity="SERCOM\AppBundle\Entity\ReceiveMessage", mappedBy="privatemessages")
     */
    private $receivers;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->attachements = new \Doctrine\Common\Collections\ArrayCollection();
        $this->receivers = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @return string
     */
    public function getMessageDelete()
    {
        return $this->message_delete;
    }

    /**
     * @param string $message_delete
     */
    public function setMessageDelete($message_delete)
    {
        $this->message_delete = $message_delete;
    }



    /**
     * Get privatemessage_id
     *
     * @return integer 
     */
    public function getPrivatemessageId()
    {
        return $this->privatemessage_id;
    }

    /**
     * Set subject
     *
     * @param string $subject
     * @return PrivateMessage
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return string 
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set dateMessage
     *
     * @param \DateTime $dateMessage
     * @return PrivateMessage
     */
    public function setDateMessage($dateMessage)
    {
        $this->dateMessage = $dateMessage;

        return $this;
    }

    /**
     * Get dateMessage
     *
     * @return \DateTime 
     */
    public function getDateMessage()
    {
        return $this->dateMessage;
    }

    /**
     * Set messagecontent
     *
     * @param string $messagecontent
     * @return PrivateMessage
     */
    public function setMessagecontent($messagecontent)
    {
        $this->messagecontent = $messagecontent;

        return $this;
    }

    /**
     * Get messagecontent
     *
     * @return string 
     */
    public function getMessagecontent()
    {
        return $this->messagecontent;
    }

    /**
     * Add attachements
     *
     * @param \SERCOM\AppBundle\Entity\Attachement $attachements
     * @return PrivateMessage
     */
    public function addAttachement(\SERCOM\AppBundle\Entity\Attachement $attachements)
    {
        if ($this->attachements->contains($attachements)) {
            return;
        }

        $this->attachements->add($attachements);
        $attachements->addPrivatemessage($this);
    }

    /**
     * Remove attachements
     *
     * @param \SERCOM\AppBundle\Entity\Attachement $attachements
     */
    public function removeAttachement(\SERCOM\AppBundle\Entity\Attachement $attachements)
    {
        if (!$this->attachements->contains($attachements)) {
            return;
        }

        $this->attachements->removeElement($attachements);
        $attachements->removePrivatemessage($this);
    }

    /**
     * Get attachements
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAttachements()
    {
        return $this->attachements;
    }

    /**
     * Set sender
     *
     * @param \SERCOM\AppBundle\Entity\Member $sender
     * @return PrivateMessage
     */
    public function setSender(\SERCOM\AppBundle\Entity\Member $sender)
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * Get sender
     *
     * @return \SERCOM\AppBundle\Entity\Member 
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * Add receivers
     *
     * @param \SERCOM\AppBundle\Entity\ReceiveMessage $receivers
     * @return PrivateMessage
     */
    public function addReceiver(\SERCOM\AppBundle\Entity\ReceiveMessage $receivers)
    {
        $this->receivers[] = $receivers;

        return $this;
    }

    /**
     * Remove receivers
     *
     * @param \SERCOM\AppBundle\Entity\ReceiveMessage $receivers
     */
    public function removeReceiver(\SERCOM\AppBundle\Entity\ReceiveMessage $receivers)
    {
        $this->receivers->removeElement($receivers);
    }

    /**
     * Get receivers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getReceivers()
    {
        return $this->receivers;
    }

    /**
     * Get mp_id
     *
     * @return integer 
     */
    public function getMpId()
    {
        return $this->mp_id;
    }
}
