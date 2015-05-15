<?php

namespace SERCOM\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * SiteArticle
 *
 * @ORM\Table(name="site_article")
 * @ORM\Entity(repositoryClass="SERCOM\AppBundle\Entity\Repository\SiteArticleRepository")
 * @UniqueEntity("title")
 */
class SiteArticle
{
    /**
     * @var integer
     * @ORM\Column(name="article_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $article_id;
    /**
     * @var string
     * @ORM\Column(name="submitdate", type="datetime", nullable=false)
     */
    private $submitDate;
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min="10", max="255")
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    private $title;
    /**
     * @var string
     * @ORM\Column(name="publishdate", type="datetime", nullable=false)
     */
    private $publishDate;
    /**
     * @var string
     * @ORM\Column(name="publish", type="boolean", nullable=false)
     */
    private $publish;
    /**
     * @var string
     * @ORM\Column(name="submit", type="boolean", nullable=false)
     */
    private $submit;
    /**
     * @var text
     * @ORM\Column(name="preview", type="string", length=200, nullable=false)
     */
    private $preview;
    /**
     * @var
     * @ORM\ManyToOne(targetEntity="SERCOM\AppBundle\Entity\Member", inversedBy="sitearticles")
     * @ORM\JoinColumns({
     * 	@ORM\JoinColumn(name="person_id", referencedColumnName="person_id", nullable=false)})
     */
    private $member;
    /**
     * @var
     * @ORM\ManyToOne(targetEntity="SERCOM\AppBundle\Entity\SiteSection", inversedBy="sitearticles")
     * @ORM\JoinColumns({
     * 	@ORM\JoinColumn(name="sitesection_id", referencedColumnName="sitesection_id", nullable=false)})
     */
    private $sitesection;
    /**
     * @var
     * @ORM\ManyToMany(targetEntity="SERCOM\AppBundle\Entity\Tag", inversedBy="articles", cascade={"all"})
     * @ORM\JoinTable(name="article_tag",
     * 	joinColumns={
     *		@ORM\JoinColumn(name="article_id", referencedColumnName="article_id")},
     *       inverseJoinColumns={
     *		@ORM\JoinColumn(name="tag_id", referencedColumnName="tag_id")})
     */
    private $tags;
    /**
     * @var
     * @ORM\OneToMany(targetEntity="SERCOM\AppBundle\Entity\ArticleDocument", mappedBy="article", cascade={"all"})
     */
    private $documents;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
        $this->documents = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get article_id
     *
     * @return integer 
     */
    public function getArticleId()
    {
        return $this->article_id;
    }

    /**
     * Set submitDate
     *
     * @param \DateTime $submitDate
     * @return SiteArticle
     */
    public function setSubmitDate($submitDate)
    {
        $this->submitDate = $submitDate;

        return $this;
    }

    /**
     * Get submitDate
     *
     * @return \DateTime 
     */
    public function getSubmitDate()
    {
        return $this->submitDate;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return SiteArticle
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set publishDate
     *
     * @param \DateTime $publishDate
     * @return SiteArticle
     */
    public function setPublishDate($publishDate)
    {
        $this->publishDate = $publishDate;

        return $this;
    }

    /**
     * Get publishDate
     *
     * @return \DateTime 
     */
    public function getPublishDate()
    {
        return $this->publishDate;
    }

    /**
     * Set publish
     *
     * @param boolean $publish
     * @return SiteArticle
     */
    public function setPublish($publish)
    {
        $this->publish = $publish;

        return $this;
    }

    /**
     * Get publish
     *
     * @return boolean 
     */
    public function getPublish()
    {
        return $this->publish;
    }

    /**
     * Set submit
     *
     * @param boolean $submit
     * @return SiteArticle
     */
    public function setSubmit($submit)
    {
        $this->submit = $submit;

        return $this;
    }

    /**
     * Get submit
     *
     * @return boolean 
     */
    public function getSubmit()
    {
        return $this->submit;
    }

    /**
     * Set preview
     *
     * @param string $preview
     * @return SiteArticle
     */
    public function setPreview($preview)
    {
        $this->preview = $preview;

        return $this;
    }

    /**
     * Get preview
     *
     * @return string 
     */
    public function getPreview()
    {
        return $this->preview;
    }

    /**
     * Set member
     *
     * @param \SERCOM\AppBundle\Entity\Member $member
     * @return SiteArticle
     */
    public function setMember(\SERCOM\AppBundle\Entity\Member $member)
    {
        $this->member = $member;

        return $this;
    }

    /**
     * Get member
     *
     * @return \SERCOM\AppBundle\Entity\Member 
     */
    public function getMember()
    {
        return $this->member;
    }

    /**
     * Set sitesection
     *
     * @param \SERCOM\AppBundle\Entity\SiteSection $sitesection
     * @return SiteArticle
     */
    public function setSitesection(\SERCOM\AppBundle\Entity\SiteSection $sitesection)
    {
        $this->sitesection = $sitesection;

        return $this;
    }

    /**
     * Get sitesection
     *
     * @return \SERCOM\AppBundle\Entity\SiteSection 
     */
    public function getSitesection()
    {
        return $this->sitesection;
    }

    /**
     * Add tags
     *
     * @param \SERCOM\AppBundle\Entity\Tag $tags
     * @return SiteArticle
     */
    public function addTag(\SERCOM\AppBundle\Entity\Tag $tag)
    {
        /*
        $this->tags[] = $tags;
        return $this;*/
        if ($this->tags->contains($tag)) {
            return;
        }

        $this->tags->add($tag);
        $tag->addArticle($this);
    }

    /**
     * Remove tags
     *
     * @param \SERCOM\AppBundle\Entity\Tag $tags
     */
    public function removeTag(\SERCOM\AppBundle\Entity\Tag $tag)
    {
        //$this->tags->removeElement($tags);
        if (!$this->tags->contains($tag)) {
            return;
        }

        $this->tags->removeElement($tag);
        $tag->removeArticle($this);
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
     * Add documents
     *
     * @param \SERCOM\AppBundle\Entity\ArticleDocument $documents
     * @return SiteArticle
     */
    public function addDocument(\SERCOM\AppBundle\Entity\ArticleDocument $documents)
    {
        $this->documents[] = $documents;

        return $this;
    }

    /**
     * Remove documents
     *
     * @param \SERCOM\AppBundle\Entity\ArticleDocument $documents
     */
    public function removeDocument(\SERCOM\AppBundle\Entity\ArticleDocument $documents)
    {
        $this->documents->removeElement($documents);
    }

    /**
     * Get documents
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDocuments()
    {
        return $this->documents;
    }

    public function setTags($tags){
        $this->tags = $tags;
        return $this;

    }
}
