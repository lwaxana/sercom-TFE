<?php

namespace SERCOM\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CoursePlanning
 *
 * @ORM\Table(name="course_planning")
 * @ORM\Entity(repositoryClass="SERCOM\AppBundle\Entity\Repository\CoursePlanningRepository")
 */
class CoursePlanning
{
    /**
     * @var integer
     * @ORM\Column(name="planning_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $planning_id;
    /**
     * @var string
     * @ORM\Column(name="datecours", type="datetime", nullable=false)
     */
    private $datecours;


    /**
     * @var
     * @ORM\ManyToOne(targetEntity="SERCOM\AppBundle\Entity\CoursePlace", inversedBy="courseplannings")
     * @ORM\JoinColumns({
     * 	@ORM\JoinColumn(name="place_id", referencedColumnName="place_id", nullable=false)})
     */
    private $courseplace;

    /**
     * @var
     * @ORM\ManyToOne(targetEntity="SERCOM\AppBundle\Entity\Classe", inversedBy="plannings")
     * @ORM\JoinColumns({
     * 	@ORM\JoinColumn(name="classe_id", referencedColumnName="classe_id", nullable=false)})
     */
    private $classe;


    /**
     * Constructor
     */
    public function __construct()
    {

    }

    /**
     * Get planning_id
     *
     * @return integer 
     */
    public function getPlanningId()
    {
        return $this->planning_id;
    }

    /**
     * Set datecours
     *
     * @param \DateTime $datecours
     * @return CoursePlanning
     */
    public function setDatecours($datecours)
    {
        $this->datecours = $datecours;

        return $this;
    }

    /**
     * Get datecours
     *
     * @return \DateTime 
     */
    public function getDatecours()
    {
        return $this->datecours;
    }


    /**
     * Set courseplace
     *
     * @param \SERCOM\AppBundle\Entity\CoursePlace $courseplace
     * @return CoursePlanning
     */
    public function setCourseplace(\SERCOM\AppBundle\Entity\CoursePlace $courseplace)
    {
        $this->courseplace = $courseplace;

        return $this;
    }

    /**
     * Get courseplace
     *
     * @return \SERCOM\AppBundle\Entity\CoursePlace 
     */
    public function getCourseplace()
    {
        return $this->courseplace;
    }

    /**
     * @return mixed
     */
    public function getClasse()
    {
        return $this->classe;
    }

    /**
     * @param mixed $classe
     */
    public function setClasse($classe)
    {
        $this->classe = $classe;
    }






}
