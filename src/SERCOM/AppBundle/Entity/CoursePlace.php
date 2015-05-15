<?php

namespace SERCOM\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CoursePlace
 *
 * @ORM\Table(name="course_place")
 * @ORM\Entity(repositoryClass="SERCOM\AppBundle\Entity\Repository\CoursePlaceRepository")
 */
class CoursePlace
{
    /**
     * @var integer
     * @ORM\Column(name="place_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $place_id;
    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=50, nullable=false)
     */
    private $name;

    /**
     * @var
     * @ORM\ManyToOne(targetEntity="SERCOM\AppBundle\Entity\Address", inversedBy="courseplaces", cascade={"all"})
     * @ORM\JoinColumns({
     * 	@ORM\JoinColumn(name="address_id", referencedColumnName="address_id", nullable=false)})
     */
    private $address;
    /**
     * @var
     * @ORM\OneToMany(targetEntity="SERCOM\AppBundle\Entity\CoursePlanning", mappedBy="courseplace")
     */
    private $courseplannings;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->courseplannings = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get place_id
     *
     * @return integer 
     */
    public function getPlaceId()
    {
        return $this->place_id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return CoursePlace
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
     * Set address
     *
     * @param \SERCOM\AppBundle\Entity\Address $address
     * @return CoursePlace
     */
    public function setAddress(\SERCOM\AppBundle\Entity\Address $address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return \SERCOM\AppBundle\Entity\Address 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Add courseplannings
     *
     * @param \SERCOM\AppBundle\Entity\CoursePlanning $courseplannings
     * @return CoursePlace
     */
    public function addCourseplanning(\SERCOM\AppBundle\Entity\CoursePlanning $courseplannings)
    {
        $this->courseplannings[] = $courseplannings;

        return $this;
    }

    /**
     * Remove courseplannings
     *
     * @param \SERCOM\AppBundle\Entity\CoursePlanning $courseplannings
     */
    public function removeCourseplanning(\SERCOM\AppBundle\Entity\CoursePlanning $courseplannings)
    {
        $this->courseplannings->removeElement($courseplannings);
    }

    /**
     * Get courseplannings
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCourseplannings()
    {
        return $this->courseplannings;
    }
}
