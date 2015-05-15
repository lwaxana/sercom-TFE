<?php
/**
 * Created by PhpStorm.
 * User: Lwaxana
 * Date: 22/04/2015
 * Time: 09:11
 */

namespace SERCOM\AppBundle\Utils;


class SpecialChar {

    //http://forum.alsacreations.com/topic-20-26918-1-resolu-Remplacer-des-caracteres-speciaux.html
    static public function removeChar($str){
        $msg = htmlentities("$str");
        $msg0 = preg_replace('#&(.)(acute|cedil|circ|ring|tilde|uml|grave);#', '$1', $msg);
        return $msg0;
    }

    static public function removeSpace($str){
        return str_replace(' ','',$str);
    }

} 