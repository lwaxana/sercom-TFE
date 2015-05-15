<?php
/**
 * Created by PhpStorm.
 * User: Lwaxana
 * Date: 05/05/2015
 * Time: 14:20
 */

namespace SERCOM\AppBundle\Form\DataTransformer;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;
use SERCOM\AppBundle\Entity\Tag;

class TagToStringTransformer implements DataTransformerInterface {

    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    /**
     * Transforms an object (issue) to a string (number).
     *
     * @param  Issue|null $issue
     * @return string
     */
    public function transform($tags)
    {

        if (null === $tags) {
            return "";
        }

        $tab = array();
        foreach($tags as $t){
            array_push($tab, $t->getTagName());
        }
        return implode(", ", $tab );
    }

    /**
     * Transforms a string (number) to an object (issue).
     *
     * @param  string $number
     * @return Issue|null
     * @throws TransformationFailedException if object (issue) is not found.
     */
    public function reverseTransform($value)
    {



        if (!$value) {

            return null;
        }

        $tags = new ArrayCollection();

        if ( null === $value ) return $tags;

        $tokens = preg_split( '/(\s*,\s*)+/', $value, -1, PREG_SPLIT_NO_EMPTY );

        foreach ( $tokens as $token ) {
            if ( null === ( $tag = $this->om->getRepository('SERCOMAppBundle:Tag')->findOneByTagName($token) ) ) {
                $tag = new Tag();
                $tag->setTagName( $token );
                $this->om->persist( $tag );
            }
            $tags->add( $tag );
        }
        $this->om->flush();

        return $tags;
    }
} 