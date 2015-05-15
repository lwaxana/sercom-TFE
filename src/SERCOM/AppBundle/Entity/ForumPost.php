<?php

namespace SERCOM\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * ForumPost
 *
 * @ORM\Table(name="forum_post")
 * @ORM\Entity(repositoryClass="SERCOM\AppBundle\Entity\Repository\ForumPostRepository")
 */
class ForumPost
{
    /**
     * @var integer
     * @ORM\Column(name="post_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $post_id;
    /**
     * @var string
     * @ORM\Column(name="datepost", type="datetime", nullable=false)
     */
    private $datePost;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min="2", max="5000")
     * @ORM\Column(name="postcontent", type="text", length=5000, nullable=false)
     */
    private $post_content;
    /**
     * @var string
     * @ORM\Column(name="moderated", type="boolean", nullable=false)
     */
    private $moderated;
    /**
     * @var
     * @ORM\ManyToOne(targetEntity="SERCOM\AppBundle\Entity\Member", inversedBy="forumposts")
     * @ORM\JoinColumns({
     * 	@ORM\JoinColumn(name="person_id", referencedColumnName="person_id", nullable=false)})
     */
    private $member;
    /**
     * @var
     * @ORM\ManyToOne(targetEntity="SERCOM\AppBundle\Entity\ForumTopic", inversedBy="forumposts")
     * @ORM\JoinColumns({
     * 	@ORM\JoinColumn(name="topic_id", referencedColumnName="topic_id", nullable=false)})
     */
    private $forumtopic;



    /**
     * Get post_id
     *
     * @return integer 
     */
    public function getPostId()
    {
        return $this->post_id;
    }

    /**
     * Set datePost
     *
     * @param \DateTime $datePost
     * @return ForumPost
     */
    public function setDatePost($datePost)
    {
        $this->datePost = $datePost;

        return $this;
    }

    /**
     * Get datePost
     *
     * @return \DateTime 
     */
    public function getDatePost()
    {
        return $this->datePost;
    }

    /**
     * Set post_content
     *
     * @param string $postContent
     * @return ForumPost
     */
    public function setPostContent($postContent)
    {
        $this->post_content = $postContent;

        return $this;
    }

    /**
     * Get post_content
     *
     * @return string 
     */
    public function getPostContent()
    {
        return $this->post_content;
    }

    /**
     * Set moderated
     *
     * @param boolean $moderated
     * @return ForumPost
     */
    public function setModerated($moderated)
    {
        $this->moderated = $moderated;

        return $this;
    }

    /**
     * Get moderated
     *
     * @return boolean 
     */
    public function getModerated()
    {
        return $this->moderated;
    }

    /**
     * Set member
     *
     * @param \SERCOM\AppBundle\Entity\Member $member
     * @return ForumPost
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
     * Set forumtopic
     *
     * @param \SERCOM\AppBundle\Entity\ForumTopic $forumtopic
     * @return ForumPost
     */
    public function setForumtopic(\SERCOM\AppBundle\Entity\ForumTopic $forumtopic)
    {
        $this->forumtopic = $forumtopic;

        return $this;
    }

    /**
     * Get forumtopic
     *
     * @return \SERCOM\AppBundle\Entity\ForumTopic 
     */
    public function getForumtopic()
    {
        return $this->forumtopic;
    }
}
