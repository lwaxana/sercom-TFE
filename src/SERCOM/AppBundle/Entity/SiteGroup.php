<?php

namespace SERCOM\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Role\RoleInterface;

/**
 * SiteGroup
 *
 * @ORM\Table(name="site_group")
 * @ORM\Entity(repositoryClass="SERCOM\AppBundle\Entity\Repository\SiteGroupRepository")
 */
class SiteGroup implements RoleInterface{

    /**
     * @var integer
     * @ORM\Column(name="sitegroup_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $sitegroup_id;
    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=100, nullable=false, unique=true)
     */
    private $name;
    /**
     * @var string
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     */
    private $description;


    /**
     * @var
     * @ORM\ManyToMany(targetEntity="SERCOM\AppBundle\Entity\Person", mappedBy="sitegroups")
     */
    private $persons;

    /**
     * @var
     * @ORM\OneToMany(targetEntity="SERCOM\AppBundle\Entity\AsblDocumentCat", mappedBy="sitegroup")
     */
    private $asbldoc_cats;




    /**
     * Returns the role.
     *
     * This method returns a string representation whenever possible.
     *
     * When the role cannot be represented with sufficient precision by a
     * string, it should return null.
     *
     * @return string|null A string representation of the role, or null
     */
    public function getRole()
    {
        // TODO: Implement getRole() method.
        return $this->name;
    }


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->siteperms = new \Doctrine\Common\Collections\ArrayCollection();
        $this->persons = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get sitegroup_id
     *
     * @return integer 
     */
    public function getSitegroupId()
    {
        return $this->sitegroup_id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return SiteGroup
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
     * @return SiteGroup
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
     * Add persons
     *
     * @param \SERCOM\AppBundle\Entity\Person $persons
     * @return SiteGroup
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
     * Add asbldoc_cats
     *
     * @param \SERCOM\AppBundle\Entity\AsblDocumentCat $asbldocCats
     * @return SiteGroup
     */
    public function addAsbldocCat(\SERCOM\AppBundle\Entity\AsblDocumentCat $asbldocCats)
    {
        $this->asbldoc_cats[] = $asbldocCats;

        return $this;
    }

    /**
     * Remove asbldoc_cats
     *
     * @param \SERCOM\AppBundle\Entity\AsblDocumentCat $asbldocCats
     */
    public function removeAsbldocCat(\SERCOM\AppBundle\Entity\AsblDocumentCat $asbldocCats)
    {
        $this->asbldoc_cats->removeElement($asbldocCats);
    }

    /**
     * Get asbldoc_cats
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAsbldocCats()
    {
        return $this->asbldoc_cats;
    }
}
