<?php
/**
 * Created by PhpStorm.
 * User: Lwaxana
 * Date: 14/04/2015
 * Time: 20:57
 */

namespace SERCOM\AppBundle\Image;


class Image {

    private $file;
    private $type;

    public function __construct($file, $type){
        $this->file = $file;
        $this->type = $type;
    }

    public function resizeProfile($path, $nompic ){

        $nouvelle_largeur = 200;
        $taille = getimagesize($this->file);
        $reduction = ( ($nouvelle_largeur * 100)/$taille[0] );
        $nouvelle_hauteur = (($taille[1]*$reduction)/100);

        if ( $this->type == 'jpeg'){
            $image = imagecreatefromjpeg($this->file);
        }
        if ( $this->type == 'gif'){
            $image = imagecreatefromgif($this->file);
        }
        if ( $this->type == 'png'){
            $image = imagecreatefrompng($this->file);
        }
        $nouvelle_image = imagecreatetruecolor($nouvelle_largeur, $nouvelle_hauteur);
        imagecopyresampled($nouvelle_image, $image,0,0,0,0, $nouvelle_largeur, $nouvelle_hauteur, $taille[0], $taille[1]);

        if ( $this->type == 'jpeg'){
            imagejpeg($nouvelle_image, $path."/".$nompic, 100);
        }
        if ( $this->type == 'gif'){
            imagegif($nouvelle_image, $path."/".$nompic, 100);
        }
        if ( $this->type == 'png'){
            imagepng($nouvelle_image, $path."/".$nompic, 100);
        }

    }

} 