<?php
/**
 * Created by PhpStorm.
 * User: Lwaxana
 * Date: 20/03/2015
 * Time: 13:52
 */

namespace SERCOM\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Receive_Message
 *
 * @ORM\Table(name="receive_message")
 * @ORM\Entity(repositoryClass="SERCOM\AppBundle\Entity\Repository\ReceiveMessageRepository")
 */
class ReceiveMessage {

    /**
     * @var integer
     * @ORM\Column(name="receive_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $receive_id;

    /**
     * @var string
     * @ORM\Column(name="read_message", type="boolean", nullable=false)
     */
    private $read_message;

    /**
     * @var string
     * @ORM\Column(name="msg_delete", type="boolean", nullable=false)
     */
    private $message_delete;
    /**
     * @var
     * @ORM\ManyToOne(targetEntity="SERCOM\AppBundle\Entity\PrivateMessage", inversedBy="receivers")
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="mp_id", referencedColumnName="mp_id", nullable=false)})
     */
    private $privatemessages;
    /**
     * @var
     * @ORM\ManyToOne(targetEntity="SERCOM\AppBundle\Entity\Member", inversedBy="privatemessagesreceive")
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="person_id", referencedColumnName="person_id", nullable=false)})
     */
    private $member;

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
     * @return string
     */
    public function getReadMessage()
    {
        return $this->read_message;
    }

    /**
     * @param string $read_message
     */
    public function setReadMessage($read_message)
    {
        $this->read_message = $read_message;
    }




    /**
     * Set privatemessages
     *
     * @param \SERCOM\AppBundle\Entity\PrivateMessage $privatemessages
     * @return ReceiveMessage
     */
    public function setPrivatemessages(\SERCOM\AppBundle\Entity\PrivateMessage $privatemessages)
    {
        $this->privatemessages = $privatemessages;

        return $this;
    }

    /**
     * Get privatemessages
     *
     * @return \SERCOM\AppBundle\Entity\PrivateMessage 
     */
    public function getPrivatemessages()
    {
        return $this->privatemessages;
    }

    /**
     * Set member
     *
     * @param \SERCOM\AppBundle\Entity\Member $member
     * @return ReceiveMessage
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
     * Get receive_id
     *
     * @return integer 
     */
    public function getReceiveId()
    {
        return $this->receive_id;
    }
}
