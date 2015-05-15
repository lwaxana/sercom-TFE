<?php

namespace SERCOM\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Subject
 *
 * @ORM\Table(name="subject")
 * @ORM\Entity(repositoryClass="SERCOM\AppBundle\Entity\Repository\SubjectRepository")
 */
class Subject
{
    /**
     * @var integer
     * @ORM\Column(name="subject_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $subject_id;
    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=50, nullable=false, unique=true)
     */
    private $name;

    /**
     * @var
     * @ORM\ManyToMany(targetEntity="SERCOM\AppBundle\Entity\Teacher", inversedBy="subjects")
     * @ORM\JoinTable(name="teacher_subject",
     * 	joinColumns={
     *		@ORM\JoinColumn(name="subject_id", referencedColumnName="subject_id")},
     *       inverseJoinColumns={
     *		@ORM\JoinColumn(name="person_id", referencedColumnName="person_id")})
     */
    private $teachers;
    /**
     * @var
     * @ORM\OneToMany(targetEntity="SERCOM\AppBundle\Entity\Classe", mappedBy="suject")
     */
    private $classes;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->teachers = new \Doctrine\Common\Collections\ArrayCollection();
        $this->classes = new \Doctrine\Common\Collections\ArrayCollection();

    }

    /**
     * Get subject_id
     *
     * @return integer 
     */
    public function getSubjectId()
    {
        return $this->subject_id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Subject
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
     * Add teachers
     *
     * @param \SERCOM\AppBundle\Entity\Teacher $teachers
     * @return Subject
     */
    public function addTeacher(\SERCOM\AppBundle\Entity\Teacher $teachers)
    {
        $this->teachers[] = $teachers;

        return $this;
    }

    /**
     * Remove teachers
     *
     * @param \SERCOM\AppBundle\Entity\Teacher $teachers
     */
    public function removeTeacher(\SERCOM\AppBundle\Entity\Teacher $teachers)
    {
        $this->teachers->removeElement($teachers);
    }

    /**
     * Get teachers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTeachers()
    {
        return $this->teachers;
    }

    /**
     * Add classes
     *
     * @param \SERCOM\AppBundle\Entity\Classe $classes
     * @return Subject
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


}
