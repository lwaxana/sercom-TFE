<?php

namespace SERCOM\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Relative
 *
 * @ORM\Table(name="relative")
 * @ORM\Entity(repositoryClass="SERCOM\AppBundle\Entity\Repository\RelativeRepository")
 */
class Relative
{
    /**
     * @var integer
     * @ORM\Column(name="relative_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $relative_id;
    /**
     * @var string
     * @ORM\Column(name="lastname", type="string", length=50, nullable=false)
     */
    private $lastname;
    /**
     * @var string
     * @ORM\Column(name="firstname", type="string", length=50, nullable=false)
     */
    private $firstname;
    /**
     * @var string
     * @ORM\Column(name="tel", type="string", length=25, nullable=false)
     */
    private $tel;
    /**
     * @var string
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     */
    private $email;
    /**
     * @var
     * @ORM\ManyToOne(targetEntity="SERCOM\AppBundle\Entity\RelativeType", inversedBy="relatives", cascade={"persist"})
     * @ORM\JoinColumns({
     * 	@ORM\JoinColumn(name="relativetype_id", referencedColumnName="relativetype_id", nullable=false)})
     */
    private $relativetype;
    /**
     * @var
     * @ORM\ManyToMany(targetEntity="SERCOM\AppBundle\Entity\Student", mappedBy="relatives")
     */
    private $students;



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->students = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get relative_id
     *
     * @return integer 
     */
    public function getRelativeId()
    {
        return $this->relative_id;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     * @return Relative
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string 
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     * @return Relative
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string 
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set tel
     *
     * @param string $tel
     * @return Relative
     */
    public function setTel($tel)
    {
        $this->tel = $tel;

        return $this;
    }

    /**
     * Get tel
     *
     * @return string 
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Relative
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set relativetype
     *
     * @param \SERCOM\AppBundle\Entity\RelativeType $relativetype
     * @return Relative
     */
    public function setRelativetype(\SERCOM\AppBundle\Entity\RelativeType $relativetype)
    {
        $this->relativetype = $relativetype;

        return $this;
    }

    /**
     * Get relativetype
     *
     * @return \SERCOM\AppBundle\Entity\RelativeType 
     */
    public function getRelativetype()
    {
        return $this->relativetype;
    }

    /**
     * Add students
     *
     * @param \SERCOM\AppBundle\Entity\Student $students
     * @return Relative
     */
    public function addStudent(\SERCOM\AppBundle\Entity\Student $students)
    {
        if ($this->students->contains($students)) {
            return;
        }

        $this->students->add($students);
        $students->addRelative($this);
    }

    /**
     * Remove students
     *
     * @param \SERCOM\AppBundle\Entity\Student $students
     */
    public function removeStudent(\SERCOM\AppBundle\Entity\Student $students)
    {
        if (!$this->students->contains($students)) {
            return;
        }

        $this->students->removeElement($students);
        $students->removeRelative($this);
    }

    /**
     * Get students
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getStudents()
    {
        return $this->students;
    }
}
