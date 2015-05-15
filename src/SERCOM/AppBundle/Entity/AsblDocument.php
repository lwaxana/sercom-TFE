<?php

namespace SERCOM\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * AsblDocument
 *
 * @ORM\Table(name="asbl_document")
 * @ORM\Entity(repositoryClass="SERCOM\AppBundle\Entity\Repository\AsblDocumentRepository")
 */
class AsblDocument
{
    /**
     * @var integer
     * @ORM\Column(name="asbldoc_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $asbldoc_id;
    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name;
    /**
     * @var string
     * @ORM\Column(name="url", type="string", length=255, nullable=false)
     */
    private $url;

    /**
     * @var
     * @ORM\ManyToMany(targetEntity="SERCOM\AppBundle\Entity\Tag", inversedBy="asbldocs")
     * @ORM\JoinTable(name="asbldoc_tag",
     * 	joinColumns={
     *		@ORM\JoinColumn(name="asbldoc_id", referencedColumnName="asbldoc_id")},
     *       inverseJoinColumns={
     *		@ORM\JoinColumn(name="tag_id", referencedColumnName="tag_id")})
     */
    private $tags;

    /**
     * @Assert\File()
     */
    private $file;

    /**
     * @var
     * @ORM\ManyToOne(targetEntity="SERCOM\AppBundle\Entity\AsblDocumentSousCat", inversedBy="asbldocuments")
     * @ORM\JoinColumns({@ORM\JoinColumn(name="souscat_id", referencedColumnName="souscat_id", nullable=false)})
     */
    private $souscat;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @param int $asbldoc_id
     */
    public function setAsbldocId($asbldoc_id)
    {
        $this->asbldoc_id = $asbldoc_id;
    }

    /**
     * @param mixed $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @param mixed $tags
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    }



    /**
     * Get asbldoc_id
     *
     * @return integer 
     */
    public function getAsbldocId()
    {
        return $this->asbldoc_id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return AsblDocument
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
     * Set url
     *
     * @param string $url
     * @return AsblDocument
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


    public function addTag(\SERCOM\AppBundle\Entity\Tag $tag)
    {
        /*
        $this->tags[] = $tags;
        return $this;*/
        if ($this->tags->contains($tag)) {
            return;
        }

        $this->tags->add($tag);
        $tag->addAsbldoc($this);
    }


    public function removeTag(\SERCOM\AppBundle\Entity\Tag $tag)
    {
        //$this->tags->removeElement($tags);
        if (!$this->tags->contains($tag)) {
            return;
        }

        $this->tags->removeElement($tag);
        $tag->removeAsbldoc($this);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }



    /**
     * Set souscat
     *
     * @param \SERCOM\AppBundle\Entity\AsblDocSousCat $souscat
     * @return AsblDocument
     */
    public function setSouscat(\SERCOM\AppBundle\Entity\AsblDocumentSousCat $souscat)
    {
        $this->souscat = $souscat;

        return $this;
    }

    /**
     * Get souscat
     *
     * @return \SERCOM\AppBundle\Entity\AsblDocSousCat 
     */
    public function getSouscat()
    {
        return $this->souscat;
    }
}
