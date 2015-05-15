<?php

namespace SERCOM\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Address
 *
 * @ORM\Table(name="address")
 * @ORM\Entity(repositoryClass="SERCOM\AppBundle\Entity\Repository\AddressRepository")
 */
class Address
{
    /**
     * @var integer
     * @ORM\Column(name="address_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $address_id;
    /**
     * @Assert\Length(min = "2", max = "100")
     * @Assert\NotBlank()
     * @ORM\Column(name="street", type="string", length=100, nullable=false)
     */
    private $street;
    /**
     * @var string
     * @Assert\Length(min = "1", max = "10")
     * @Assert\NotBlank()
     * @ORM\Column(name="number", type="string", length=10, nullable=false)
     */
    private $number;
    /**
     * @var string
     * @Assert\Length(min = "3", max = "30")
     * @Assert\NotBlank()
     * @ORM\Column(name="city", type="string", length=30, nullable=false)
     */
    private $city;
    /**
     * @var string
     * @Assert\Length(min = "1", max = "10")
     * @Assert\NotBlank()
     * @ORM\Column(name="postcode", type="string", length=10, nullable=false)
     */
    private $postcode;
    /**
     * @var
     * @ORM\ManyToOne(targetEntity="SERCOM\AppBundle\Entity\Country", inversedBy="addresses", cascade={"all"})
     * @ORM\JoinColumns({@ORM\JoinColumn(name="country_id", referencedColumnName="country_id", nullable=false)})
     */
    private $country;
    /**
     * @var
     * @ORM\OneToMany(targetEntity="SERCOM\AppBundle\Entity\Person", mappedBy="address")
     */
    private $persons;
    /**
     * @var
     * @ORM\OneToMany(targetEntity="SERCOM\AppBundle\Entity\CoursePlace", mappedBy="address")
     */
    private $courseplaces;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->persons = new \Doctrine\Common\Collections\ArrayCollection();
        $this->courseplaces = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get address_id
     *
     * @return integer 
     */
    public function getAddressId()
    {
        return $this->address_id;
    }

    /**
     * Set street
     *
     * @param string $street
     * @return Address
     */
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Get street
     *
     * @return string 
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set number
     *
     * @param string $number
     * @return Address
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return string 
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return Address
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set postcode
     *
     * @param string $postcode
     * @return Address
     */
    public function setPostcode($postcode)
    {
        $this->postcode = $postcode;

        return $this;
    }

    /**
     * Get postcode
     *
     * @return string 
     */
    public function getPostcode()
    {
        return $this->postcode;
    }

    /**
     * Set country
     *
     * @param \SERCOM\AppBundle\Entity\Country $country
     * @return Address
     */
    public function setCountry(\SERCOM\AppBundle\Entity\Country $country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return \SERCOM\AppBundle\Entity\Country 
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Add persons
     *
     * @param \SERCOM\AppBundle\Entity\Person $persons
     * @return Address
     */
    public function addPerson(\SERCOM\AppBundle\Entity\Person $persons)
    {
        $this->persons[] = $persons;

        return $this;
    }

    /**
     * Remove persons
     *
     * @param \SERCOM\AppBundle\Entity\Person $persons
     */
    public function removePerson(\SERCOM\AppBundle\Entity\Person $persons)
    {
        $this->persons->removeElement($persons);
    }

    /**
     * Get persons
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPersons()
    {
        return $this->persons;
    }

    /**
     * Add courseplaces
     *
     * @param \SERCOM\AppBundle\Entity\CoursePlace $courseplaces
     * @return Address
     */
    public function addCourseplace(\SERCOM\AppBundle\Entity\CoursePlace $courseplaces)
    {
        $this->courseplaces[] = $courseplaces;

        return $this;
    }

    /**
     * Remove courseplaces
     *
     * @param \SERCOM\AppBundle\Entity\CoursePlace $courseplaces
     */
    public function removeCourseplace(\SERCOM\AppBundle\Entity\CoursePlace $courseplaces)
    {
        $this->courseplaces->removeElement($courseplaces);
    }

    /**
     * Get courseplaces
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCourseplaces()
    {
        return $this->courseplaces;
    }
}
