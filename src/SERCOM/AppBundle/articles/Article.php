<?php
/**
 * Created by PhpStorm.
 * User: Lwaxana
 * Date: 26/05/2015
 * Time: 12:42
 */

namespace SERCOM\AppBundle\articles;


use SERCOM\AppBundle\Entity\SiteArticle;

class Article {

    private $title;
    private $picture;
    private $id;
    private $preview;

    public function __construct(SiteArticle $a){
        $this->title = $a->getTitle();
        $this->picture = $this->pickPicture($a);
        $this->id = $a->getArticleId();
        $this->preview = $a->getPreview();
    }

    /**
    * @return mixed
     */
        public function getId()
         {
            return $this->id;
        }/**
         * @param mixed $id
         */
        public function setId($id)
        {
            $this->id = $id;
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

    /**
     * @return mixed
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * @param mixed $picture
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;
    }

    /**
     * @return string
     */
    public function getPreview()
    {
        return $this->preview;
    }

    /**
     * @param string $preview
     */
    public function setPreview($preview)
    {
        $this->preview = $preview;
    }



    private function pickPicture(SiteArticle $a){
        $docs = $a->getDocuments();
        $pics = array();
        foreach( $docs as $doc){
            if ( $doc->getPicture() ){
                array_push($pics, $doc);
            }
        }

        if ( !empty($pics)){
            if ( count($pics) > 1){
                $v = array_rand($pics, 1);
                $pic = $pics[$v];
                return $pic->getUrl();
            }
            else{
                $pic = $pics[0];
                return $pic->getUrl();
            }
        }
        else{
            return null;
        }




    }

} 