<?php
/**
 * Created by PhpStorm.
 * User: Lwaxana
 * Date: 26/05/2015
 * Time: 13:12
 */

namespace SERCOM\AppBundle\agenda;


class Agenda {

    private $datehour;
    private $title;

    /**
     * @return mixed
     */
    public function getDatehour()
    {
        return $this->datehour;
    }

    /**
     * @param mixed $datehour
     */
    public function setDatehour($datehour)
    {
        $this->datehour = $datehour;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    public static function cmp($a, $b){
        return ($a->getDatehour() < $b->getDatehour()) ? -1 : 1;
    }

} 