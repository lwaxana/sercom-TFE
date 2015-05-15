<?php

namespace SERCOM\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RelativeType
 *
 * @ORM\Table(name="relative_type")
 * @ORM\Entity(repositoryClass="SERCOM\AppBundle\Entity\Repository\RelativeTypeRepository")
 */
class RelativeType
{
    /**
     * @var integer
     * @ORM\Column(name="relativetype_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $relativetype_id;
    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=50, nullable=false)
     */
    private $name;
    /**
     * @ORM\OneToMany(targetEntity="SERCOM\AppBundle\Entity\Relative", mappedBy="relativetype")
     */
    private $relatives;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->relatives = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get relativetype_id
     *
     * @return integer 
     */
    public function getRelativetypeId()
    {
        return $this->relativetype_id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return RelativeType
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
     * Add relatives
     *
     * @param \SERCOM\AppBundle\Entity\Relative $relatives
     * @return RelativeType
     */
    public function addRelative(\SERCOM\AppBundle\Entity\Relative $relatives)
    {
        $this->relatives[] = $relatives;

        return $this;
    }

    /**
     * Remove relatives
     *
     * @param \SERCOM\AppBundle\Entity\Relative $relatives
     */
    public function removeRelative(\SERCOM\AppBundle\Entity\Relative $relatives)
    {
        $this->relatives->removeElement($relatives);
    }

    /**
     * Get relatives
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRelatives()
    {
        return $this->relatives;
    }
}
