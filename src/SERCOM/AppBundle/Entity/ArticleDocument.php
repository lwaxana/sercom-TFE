<?php

namespace SERCOM\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ArticleDocument
 *
 * @ORM\Table(name="article_document")
 * @ORM\Entity(repositoryClass="SERCOM\AppBundle\Entity\Repository\ArticleDocumentRepository")
 */
class ArticleDocument
{
    /**
     * @var integer
     * @ORM\Column(name="articledoc_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $articledoc_id;
    /**
     * @var string
     * @Assert\Length(min = "10", max = "100")
     * @Assert\NotBlank()
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name;
    /**
     * @var string
     * @ORM\Column(name="url", type="string", length=255, nullable=false)
     */
    private $url;

    /**
     * @var string
     * @ORM\Column(name="picture", type="boolean", nullable=false)
     */
    private $picture;

    /**
     * @var
     * @ORM\ManyToOne(targetEntity="SERCOM\AppBundle\Entity\SiteArticle", inversedBy="documents")
     * @ORM\JoinColumns({
     * 	@ORM\JoinColumn(name="article_id", referencedColumnName="article_id", nullable=false)})
     */
    private $article;

    private $file;

    /**
     * Get articledoc_id
     *
     * @return integer 
     */
    public function getArticledocId()
    {
        return $this->articledoc_id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return ArticleDocument
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
     * @return ArticleDocument
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



    /**
     * Set article
     *
     * @param \SERCOM\AppBundle\Entity\SiteArticle $article
     * @return ArticleDocument
     */
    public function setArticle(\SERCOM\AppBundle\Entity\SiteArticle $article)
    {
        $this->article = $article;

        return $this;
    }

    /**
     * Get article
     *
     * @return \SERCOM\AppBundle\Entity\SiteArticle 
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * @return string
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * @param string $picture
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }




}
