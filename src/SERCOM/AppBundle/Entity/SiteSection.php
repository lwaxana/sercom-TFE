<?php

namespace SERCOM\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * SiteSection
 *
 * @ORM\Table(name="site_section")
 * @ORM\Entity(repositoryClass="SERCOM\AppBundle\Entity\Repository\SiteSectionRepository")
 * @UniqueEntity("name")
 */
class SiteSection
{
    /**
     * @var integer
     * @ORM\Column(name="sitesection_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $sitesection_id;
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min = "5" , max = "50")
     * @ORM\Column(name="name", type="string", length=50, nullable=false, unique=true)
     */
    private $name;
    /**
     * @var
     * @ORM\OneToMany(targetEntity="SERCOM\AppBundle\Entity\SiteArticle", mappedBy="sitesection")
     */
    private $sitearticles;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->sitearticles = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get sitesection_id
     *
     * @return integer 
     */
    public function getSitesectionId()
    {
        return $this->sitesection_id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return SiteSection
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
     * Add sitearticles
     *
     * @param \SERCOM\AppBundle\Entity\SiteArticle $sitearticles
     * @return SiteSection
     */
    public function addSitearticle(\SERCOM\AppBundle\Entity\SiteArticle $sitearticles)
    {
        $this->sitearticles[] = $sitearticles;

        return $this;
    }

    /**
     * Remove sitearticles
     *
     * @param \SERCOM\AppBundle\Entity\SiteArticle $sitearticles
     */
    public function removeSitearticle(\SERCOM\AppBundle\Entity\SiteArticle $sitearticles)
    {
        $this->sitearticles->removeElement($sitearticles);
    }

    /**
     * Get sitearticles
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSitearticles()
    {
        return $this->sitearticles;
    }
}
