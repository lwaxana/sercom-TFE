<?php

namespace SERCOM\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Student
 * @ORM\Table(name="student")
 * @ORM\Entity(repositoryClass="SERCOM\AppBundle\Entity\Repository\StudentRepository")
 */
class Student
{

    /**
     * @var Person
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="SERCOM\AppBundle\Entity\Person", inversedBy="student")
     * @ORM\JoinColumn(name="person_id", referencedColumnName="person_id", unique=true)
     */
    private $person;
    /**
     * @var string
     * @ORM\Column(name="actif", type="boolean", nullable=false)
     */
    private $actif;

    /**
     * @var
     * @ORM\ManyToMany(targetEntity="SERCOM\AppBundle\Entity\Classe", mappedBy="students")
     */
    private $classes;
    /**
     * @var
     * @ORM\ManyToMany(targetEntity="SERCOM\AppBundle\Entity\Relative", inversedBy="students", cascade={"all"})
     * @ORM\JoinTable(name="student_relative",
     * 	joinColumns={
     *		@ORM\JoinColumn(name="person_id", referencedColumnName="person_id")},
     *       inverseJoinColumns={
     *		@ORM\JoinColumn(name="relative_id", referencedColumnName="relative_id")})
     */
    private $relatives;
    /**
     * @var
     * @ORM\ManyToOne(targetEntity="SERCOM\AppBundle\Entity\StudyYear", inversedBy="students")
     * @ORM\JoinColumns({@ORM\JoinColumn(name="year_id", referencedColumnName="year_id", nullable=true)})
     */
    private $year;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->courseplannings = new \Doctrine\Common\Collections\ArrayCollection();
        $this->classes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->relatives = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Set person
     *
     * @param \SERCOM\AppBundle\Entity\Person $person
     * @return Student
     */
    public function setPerson(\SERCOM\AppBundle\Entity\Person $person = null)
    {
        $this->person = $person;

        return $this;
    }



    /**
     * Get person
     *
     * @return \SERCOM\AppBundle\Entity\Person 
     */
    public function getPerson()
    {
        return $this->person;
    }


    /**
     * Add classes
     *
     * @param \SERCOM\AppBundle\Entity\Classe $classes
     * @return Student
     */
    public function addClass(\SERCOM\AppBundle\Entity\Classe $classes)
    {
        $this->classes[] = $classes;

        return $this;
    }

    /**
     * Remove classes
     *
     * @param \SERCOM\AppBundle\Entity\Classe $classes
     */
    public function removeClass(\SERCOM\AppBundle\Entity\Classe $classes)
    {
        $this->classes->removeElement($classes);
    }

    /**
     * Get classes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getClasses()
    {
        return $this->classes;
    }

    /**
     * Add relatives
     *
     * @param \SERCOM\AppBundle\Entity\Relative $relatives
     * @return Student
     */
    public function addRelative(\SERCOM\AppBundle\Entity\Relative $relatives)
    {
        if ($this->relatives->contains($relatives)) {
            return;
        }

        $this->relatives->add($relatives);
        $relatives->addStudent($this);
    }

    /**
     * Remove relatives
     *
     * @param \SERCOM\AppBundle\Entity\Relative $relatives
     */
    public function removeRelative(\SERCOM\AppBundle\Entity\Relative $relatives)
    {
        if (!$this->relatives->contains($relatives)) {
            return;
        }

        $this->relatives->removeElement($relatives);
        $relatives->removeStudent($this);
    }

    /**
     * Get relatives
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRelatives()
    {
        return $this->relatives;
    }

    /**
     * Set year
     *
     * @param \SERCOM\AppBundle\Entity\StudyYear $year
     * @return Student
     */
    public function setYear(\SERCOM\AppBundle\Entity\StudyYear $year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return \SERCOM\AppBundle\Entity\StudyYear 
     */
    public function getYear()
    {
        return $this->year;
    }

    function __toString()
    {
        return $this->person->getLastname(). " ". $this->person->getFirstname();
    }

    /**
     * @return string
     */
    public function getActif()
    {
        return $this->actif;
    }

    /**
     * @param string $actif
     */
    public function setActif($actif)
    {
        $this->actif = $actif;
    }




}
