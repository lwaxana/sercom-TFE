<?php

namespace SERCOM\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Attachement
 *
 * @ORM\Table(name="attachement")
 * @ORM\Entity(repositoryClass="SERCOM\AppBundle\Entity\Repository\AttachementRepository")
 */
class Attachement
{
    /**
     * @var integer
     * @ORM\Column(name="attach_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $attachement_id;
    /**
     * @var string
     * @ORM\Column(name="url", type="string", length=255, nullable=false)
     */
    private $url;
    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;
    /**
     * @var
     * @ORM\ManyToOne(targetEntity="SERCOM\AppBundle\Entity\PrivateMessage", inversedBy="attachements", cascade={"all"})
     * @ORM\JoinColumns({
     * 	@ORM\JoinColumn(name="mp_id", referencedColumnName="mp_id", nullable=false)})
     */
    private $privatemessage;

    /**
     * @Assert\File(maxSize="5M")
     */
    private $file;



    /**
     * Get attachement_id
     *
     * @return integer 
     */
    public function getAttachementId()
    {
        return $this->attachement_id;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Attachement
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Attachement
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
     * Set privatemessage
     *
     * @param \SERCOM\AppBundle\Entity\PrivateMessage $privatemessage
     * @return Attachement
     */
    public function setPrivatemessage(\SERCOM\AppBundle\Entity\PrivateMessage $privatemessage)
    {
        $this->privatemessage = $privatemessage;

        return $this;
    }

    /**
     * Get privatemessage
     *
     * @return \SERCOM\AppBundle\Entity\PrivateMessage 
     */
    public function getPrivatemessage()
    {
        return $this->privatemessage;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }


}
