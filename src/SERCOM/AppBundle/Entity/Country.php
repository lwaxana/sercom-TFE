<?php

namespace SERCOM\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Country
 *
 * @ORM\Table(name="country")
 * @ORM\Entity(repositoryClass="SERCOM\AppBundle\Entity\Repository\CountryRepository")
 */
class Country
{
    /**
     * @var integer
     * @ORM\Column(name="country_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $country_id;
    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=50, nullable=false)
     */
    private $name;
    /**
     * @var string
     * @ORM\Column(name="isocode", type="string", length=2, nullable=false)
     */
    private $isocode;

    /**
     * @ORM\ManyToMany(targetEntity="SERCOM\AppBundle\Entity\Person", mappedBy="countries")
     */
    private $persons;
    /**
     * @var
     * @ORM\OneToMany(targetEntity="SERCOM\AppBundle\Entity\Address", mappedBy="country")
     */
    private $addresses;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->persons = new \Doctrine\Common\Collections\ArrayCollection();
        $this->adresses = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get country_id
     *
     * @return integer 
     */
    public function getCountryId()
    {
        return $this->country_id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Country
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
     * Set isocode
     *
     * @param string $isocode
     * @return Country
     */
    public function setIsocode($isocode)
    {
        $this->isocode = $isocode;

        return $this;
    }

    /**
     * Get isocode
     *
     * @return string 
     */
    public function getIsocode()
    {
        return $this->isocode;
    }

    /**
     * Add persons
     *
     * @param \SERCOM\AppBundle\Entity\Person $persons
     * @return Country
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
     * Add adresses
     *
     * @param \SERCOM\AppBundle\Entity\Address $adresses
     * @return Country
     */
    public function addAdress(\SERCOM\AppBundle\Entity\Address $adresses)
    {
        $this->adresses[] = $adresses;

        return $this;
    }

    /**
     * Remove adresses
     *
     * @param \SERCOM\AppBundle\Entity\Address $adresses
     */
    public function removeAdress(\SERCOM\AppBundle\Entity\Address $adresses)
    {
        $this->adresses->removeElement($adresses);
    }

    /**
     * Get adresses
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAdresses()
    {
        return $this->adresses;
    }

    /**
     * Add addresses
     *
     * @param \SERCOM\AppBundle\Entity\Address $addresses
     * @return Country
     */
    public function addAddress(\SERCOM\AppBundle\Entity\Address $addresses)
    {
        $this->addresses[] = $addresses;

        return $this;
    }

    /**
     * Remove addresses
     *
     * @param \SERCOM\AppBundle\Entity\Address $addresses
     */
    public function removeAddress(\SERCOM\AppBundle\Entity\Address $addresses)
    {
        $this->addresses->removeElement($addresses);
    }

    /**
     * Get addresses
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAddresses()
    {
        return $this->addresses;
    }
}
