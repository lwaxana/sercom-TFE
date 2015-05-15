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
 * AsblDocumentSousCat
 *
 * @ORM\Table(name="asbldoc_souscat")
 * @ORM\Entity(repositoryClass="SERCOM\AppBundle\Entity\Repository\AsblDocumentSousCatRepository")
 * @UniqueEntity("name")
 */
class AsblDocumentSousCat {

    /**
     * @var integer
     * @ORM\Column(name="souscat_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $souscat_id;
    /**
     * @var string
     * @Assert\Length(min = "5", max = "255")
     * @Assert\NotBlank()
     * @ORM\Column(name="name", type="string", length=255, nullable=false, unique=true)
     */
    private $name;
       /**
     * @var
     * @ORM\OneToMany(targetEntity="SERCOM\AppBundle\Entity\AsblDocument", mappedBy="souscat")
     */
    private $asbldocuments;
    /**
     * @var
     * @ORM\ManyToOne(targetEntity="SERCOM\AppBundle\Entity\AsblDocumentCat", inversedBy="souscats")
     * @ORM\JoinColumns({@ORM\JoinColumn(name="doccat_id", referencedColumnName="doccat_id", nullable=false)})
     */
    private $category;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->asbldocuments = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get souscat_id
     *
     * @return integer 
     */
    public function getSouscatId()
    {
        return $this->souscat_id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return AsblDocumentSousCat
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
     * Add asbldocuments
     *
     * @param \SERCOM\AppBundle\Entity\AsblDocument $asbldocuments
     * @return AsblDocumentSousCat
     */
    public function addAsbldocument(\SERCOM\AppBundle\Entity\AsblDocument $asbldocuments)
    {
        $this->asbldocuments[] = $asbldocuments;

        return $this;
    }

    /**
     * Remove asbldocuments
     *
     * @param \SERCOM\AppBundle\Entity\AsblDocument $asbldocuments
     */
    public function removeAsbldocument(\SERCOM\AppBundle\Entity\AsblDocument $asbldocuments)
    {
        $this->asbldocuments->removeElement($asbldocuments);
    }

    /**
     * Get asbldocuments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAsbldocuments()
    {
        return $this->asbldocuments;
    }

    /**
     * Set category
     *
     * @param \SERCOM\AppBundle\Entity\AsblDocCat $category
     * @return AsblDocumentSousCat
     */
    public function setCategory(\SERCOM\AppBundle\Entity\AsblDocumentCat $category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \SERCOM\AppBundle\Entity\AsblDocCat 
     */
    public function getCategory()
    {
        return $this->category;
    }
}
