<?php

namespace SERCOM\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Classe
 *
 * @ORM\Table(name="classe")
 * @ORM\Entity(repositoryClass="SERCOM\AppBundle\Entity\Repository\ClasseRepository")
 * @UniqueEntity("name")
 */
class Classe
{
    /**
     * @var integer
     * @ORM\Column(name="classe_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $classe_id;
    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=50, nullable=false)
     */
    private $name;

    /**
     * @var
     * @ORM\ManyToOne(targetEntity="SERCOM\AppBundle\Entity\Subject", inversedBy="classes")
     * @ORM\JoinColumns({@ORM\JoinColumn(name="subject_id", referencedColumnName="subject_id", nullable=false)})
     */
    private $suject;
    /**
     * @var
     * @ORM\ManyToOne(targetEntity="SERCOM\AppBundle\Entity\Teacher", inversedBy="classes")
     * @ORM\JoinColumns({@ORM\JoinColumn(name="person_id", referencedColumnName="person_id", nullable=true)})
     */
    private $teacher;
    /**
     * @var
     * @ORM\ManyToMany(targetEntity="SERCOM\AppBundle\Entity\Student", inversedBy="classes")
     * @ORM\JoinTable(name="classe_student",
     * 	joinColumns={
     *		@ORM\JoinColumn(name="classe_id", referencedColumnName="classe_id")},
     *       inverseJoinColumns={
     *		@ORM\JoinColumn(name="person_id", referencedColumnName="person_id")})
     */
    private $students;

    /**
     * @var
     * @ORM\OneToMany(targetEntity="SERCOM\AppBundle\Entity\CoursePlanning", mappedBy="classe")
     */
    private $plannings;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->students = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get classe_id
     *
     * @return integer 
     */
    public function getClasseId()
    {
        return $this->classe_id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Classe
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
     * Set suject
     *
     * @param \SERCOM\AppBundle\Entity\Subject $suject
     * @return Classe
     */
    public function setSuject(\SERCOM\AppBundle\Entity\Subject $suject)
    {
        $this->suject = $suject;

        return $this;
    }

    /**
     * Get suject
     *
     * @return \SERCOM\AppBundle\Entity\Subject 
     */
    public function getSuject()
    {
        return $this->suject;
    }

    /**
     * Set teacher
     *
     * @param \SERCOM\AppBundle\Entity\Teacher $teacher
     * @return Classe
     */
    public function setTeacher(\SERCOM\AppBundle\Entity\Teacher $teacher)
    {
        $this->teacher = $teacher;

        return $this;
    }

    /**
     * Get teacher
     *
     * @return \SERCOM\AppBundle\Entity\Teacher 
     */
    public function getTeacher()
    {
        return $this->teacher;
    }

    /**
     * Add students
     *
     * @param \SERCOM\AppBundle\Entity\Student $students
     * @return Classe
     */
    public function addStudent(\SERCOM\AppBundle\Entity\Student $students)
    {
        $this->students[] = $students;

        return $this;
    }

    /**
     * Remove students
     *
     * @param \SERCOM\AppBundle\Entity\Student $students
     */
    public function removeStudent(\SERCOM\AppBundle\Entity\Student $students)
    {
        $this->students->removeElement($students);
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



    /**
     * Add plannings
     *
     * @param \SERCOM\AppBundle\Entity\CoursePlanning $plannings
     * @return Classe
     */
    public function addPlanning(\SERCOM\AppBundle\Entity\CoursePlanning $plannings)
    {
        $this->plannings[] = $plannings;

        return $this;
    }

    /**
     * Remove plannings
     *
     * @param \SERCOM\AppBundle\Entity\CoursePlanning $plannings
     */
    public function removePlanning(\SERCOM\AppBundle\Entity\CoursePlanning $plannings)
    {
        $this->plannings->removeElement($plannings);
    }

    /**
     * Get plannings
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPlannings()
    {
        return $this->plannings;
    }
}
