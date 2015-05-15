<?php

namespace SERCOM\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tag
 *
 * @ORM\Table(name="tag")
 * @ORM\Entity(repositoryClass="SERCOM\AppBundle\Entity\Repository\TagRepository")
 */
class Tag
{
    /**
     * @var integer
     * @ORM\Column(name="tag_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $tag_id;
    /**
     * @var string
     * @ORM\Column(name="tagname", type="string", length=50, nullable=false, unique=true)
     */
    private $tagName;

    /**
     * @var
     * @ORM\ManyToMany(targetEntity="SERCOM\AppBundle\Entity\SiteArticle", mappedBy="tags", cascade={"all"})
     */
     private $articles;

    /**
     * @ORM\ManyToMany(targetEntity="SERCOM\AppBundle\Entity\AsblDocument", mappedBy="tags")
     *
     */
    private $asbldocs;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->articles = new \Doctrine\Common\Collections\ArrayCollection();
        $this->asbldocs = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get tag_id
     *
     * @return integer 
     */
    public function getTagId()
    {
        return $this->tag_id;
    }

    /**
     * Set tagName
     *
     * @param string $tagName
     * @return Tag
     */
    public function setTagName($tagName)
    {
        $this->tagName = $tagName;

        return $this;
    }

    /**
     * Get tagName
     *
     * @return string 
     */
    public function getTagName()
    {
        return $this->tagName;
    }

    /**
     * Add articles
     *
     * @param \SERCOM\AppBundle\Entity\SiteArticle $articles
     * @return Tag
     */
    public function addArticle(\SERCOM\AppBundle\Entity\SiteArticle $article)
    {
        /*
        $this->articles[] = $articles;
        return $this;*/
        if ($this->articles->contains($article)) {
            return;
        }

        $this->articles->add($article);
        $article->addTag($this);
    }

    /**
     * Remove articles
     *
     * @param \SERCOM\AppBundle\Entity\SiteArticle $articles
     */
    public function removeArticle(\SERCOM\AppBundle\Entity\SiteArticle $article)
    {
       // $this->articles->removeElement($articles);
        if (!$this->articles->contains($article)) {
            return;
        }

        $this->articles->removeElement($article);
        $article->removeTag($this);
    }

    /**
     * Get articles
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getArticles()
    {
        return $this->articles;
    }

    /**
     * Add asbldocs
     *
     * @param \SERCOM\AppBundle\Entity\AsblDocument $asbldocs
     * @return Tag
     */
    public function addAsbldoc(\SERCOM\AppBundle\Entity\AsblDocument $asbldocs)
    {
        if ($this->asbldocs->contains($asbldocs)) {
            return;
        }

        $this->asbldocs->add($asbldocs);
        $asbldocs->addTag($this);
    }

    /**
     * Remove asbldocs
     *
     * @param \SERCOM\AppBundle\Entity\AsblDocument $asbldocs
     */
    public function removeAsbldoc(\SERCOM\AppBundle\Entity\AsblDocument $asbldocs)
    {
        if (!$this->asbldocs->contains($asbldocs)) {
            return;
        }

        $this->asbldocs->removeElement($asbldocs);
        $asbldocs->removeTag($this);
    }

    /**
     * Get asbldocs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAsbldocs()
    {
        return $this->asbldocs;
    }
}
