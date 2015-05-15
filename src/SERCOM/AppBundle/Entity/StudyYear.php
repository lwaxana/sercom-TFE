<?php

namespace SERCOM\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StudyYear
 *
 * @ORM\Table(name="study_year")
 * @ORM\Entity(repositoryClass="SERCOM\AppBundle\Entity\Repository\StudyYearRepository")
 */
class StudyYear
{
    /**
     * @var integer
     * @ORM\Column(name="year_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $year_id;
    /**
     * @var string
     * @ORM\Column(name="year", type="string", length=50, nullable=false, unique=true)
     */
    private $year;
    /**
     * @var
     * @ORM\OneToMany(targetEntity="SERCOM\AppBundle\Entity\Student", mappedBy="year")
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
     * Get year_id
     *
     * @return integer 
     */
    public function getYearId()
    {
        return $this->year_id;
    }

    /**
     * Set year
     *
     * @param string $year
     * @return StudyYear
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return string 
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Add students
     *
     * @param \SERCOM\AppBundle\Entity\Student $students
     * @return StudyYear
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
}
