<?php

namespace SERCOM\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Teacher
 *
 * @ORM\Table(name="teacher")
 * @ORM\Entity(repositoryClass="SERCOM\AppBundle\Entity\Repository\TeacherRepository")
 */
class Teacher
{
    /**
     * @var Person
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="SERCOM\AppBundle\Entity\Person", inversedBy="teacher")
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
     * @ORM\ManyToMany(targetEntity="SERCOM\AppBundle\Entity\Subject", mappedBy="teachers")
     */
    private $subjects;
    /**
     * @var
     * @ORM\OneToMany(targetEntity="SERCOM\AppBundle\Entity\Classe", mappedBy="teacher")
     */
    private $classes;



   
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->subjects = new \Doctrine\Common\Collections\ArrayCollection();
        $this->classes = new \Doctrine\Common\Collections\ArrayCollection();

    }



    /**
     * Set person
     *
     * @param \SERCOM\AppBundle\Entity\Person $person
     * @return Teacher
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
     * Add subjects
     *
     * @param \SERCOM\AppBundle\Entity\Subject $subjects
     * @return Teacher
     */
    public function addSubject(\SERCOM\AppBundle\Entity\Subject $subjects)
    {
        $this->subjects[] = $subjects;

        return $this;
    }

    /**
     * Remove subjects
     *
     * @param \SERCOM\AppBundle\Entity\Subject $subjects
     */
    public function removeSubject(\SERCOM\AppBundle\Entity\Subject $subjects)
    {
        $this->subjects->removeElement($subjects);
    }

    /**
     * Get subjects
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSubjects()
    {
        return $this->subjects;
    }

    /**
     * Add classes
     *
     * @param \SERCOM\AppBundle\Entity\Classe $classes
     * @return Teacher
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
