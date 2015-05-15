<?php

namespace SERCOM\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * AsblFunction
 *
 * @ORM\Table(name="asbl_function")
 * @ORM\Entity(repositoryClass="SERCOM\AppBundle\Entity\Repository\AsblFunctionRepository")
 * @UniqueEntity("fonction")
 */
class AsblFunction
{
    /**
     * @var integer
     * @ORM\Column(name="asblfunction_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $asblfunction_id;
    /**
     * @var string
     * @Assert\Length(min = "5", max="50")
     * @Assert\NotBlank()
     * @ORM\Column(name="fonction", type="string", length=50, nullable=false, unique=true)
     */
    private $fonction;

    /**
     * @var
     * @ORM\ManyToMany(targetEntity="SERCOM\AppBundle\Entity\Member", mappedBy="asblfunctions", cascade={"all"})
     */
    private $members;





    /**
     * Constructor
     */
    public function __construct()
    {
        $this->members = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get asblfunction_id
     *
     * @return integer 
     */
    public function getAsblfunctionId()
    {
        return $this->asblfunction_id;
    }

    /**
     * Set fonction
     *
     * @param string $fonction
     * @return AsblFunction
     */
    public function setFonction($fonction)
    {
        $this->fonction = $fonction;

        return $this;
    }

    /**
     * Get fonction
     *
     * @return string 
     */
    public function getFonction()
    {
        return $this->fonction;
    }

    /**
     * Add members
     *
     * @param \SERCOM\AppBundle\Entity\Member $members
     * @return AsblFunction
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
