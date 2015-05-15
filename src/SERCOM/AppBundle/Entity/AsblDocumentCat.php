<?php
/**
 * Created by PhpStorm.
 * User: Lwaxana
 * Date: 15/04/2015
 * Time: 17:42
 */

namespace SERCOM\AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * AsblDocumentCategory
 *
 * @ORM\Table(name="asbldoc_cat")
 * @ORM\Entity(repositoryClass="SERCOM\AppBundle\Entity\Repository\AsblDocumentCatRepository")
 * @UniqueEntity("name")
 */
class AsblDocumentCat {

    /**
     * @var integer
     * @ORM\Column(name="doccat_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $doccat_id;
    /**
     * @var string
     * @Assert\Length(min = "5", max = "255")
     * @Assert\NotBlank()
     * @ORM\Column(name="name", type="string", length=255, nullable=false, unique=true)
     */
    private $name;
    /**
     * @var string
     * @Assert\Length(max = "255")
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var
     * @ORM\OneToMany(targetEntity="SERCOM\AppBundle\Entity\AsblDocumentSousCat", mappedBy="category", cascade={"remove"})
     */
    private $souscats;
    /**
     * @var
     * @ORM\ManyToOne(targetEntity="SERCOM\AppBundle\Entity\SiteGroup", inversedBy="asbldoc_cats")
     * @ORM\JoinColumns({@ORM\JoinColumn(name="sitegroup_id", referencedColumnName="sitegroup_id", nullable=false)})
     */
    private $sitegroup;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->souscats = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get doccat_id
     *
     * @return integer 
     */
    public function getDoccatId()
    {
        return $this->doccat_id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return AsblDocumentCat
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
     * @return AsblDocumentCat
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
     * Add souscats
     *
     * @param \SERCOM\AppBundle\Entity\AsblDocumentSousCat $souscats
     * @return AsblDocumentCat
     */
    public function addSouscat(\SERCOM\AppBundle\Entity\AsblDocumentSousCat $souscats)
    {
        $this->souscats[] = $souscats;

        return $this;
    }

    /**
     * Remove souscats
     *
     * @param \SERCOM\AppBundle\Entity\AsblDocumentSousCat $souscats
     */
    public function removeSouscat(\SERCOM\AppBundle\Entity\AsblDocumentSousCat $souscats)
    {
        $this->souscats->removeElement($souscats);
    }

    /**
     * Get souscats
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSouscats()
    {
        return $this->souscats;
    }

    /**
     * Set sitegroup
     *
     * @param \SERCOM\AppBundle\Entity\SiteGroup $sitegroup
     * @return AsblDocumentCat
     */
    public function setSitegroup(\SERCOM\AppBundle\Entity\SiteGroup $sitegroup)
    {
        $this->sitegroup = $sitegroup;

        return $this;
    }

    /**
     * Get sitegroup
     *
     * @return \SERCOM\AppBundle\Entity\SiteGroup 
     */
    public function getSitegroup()
    {
        return $this->sitegroup;
    }
}
