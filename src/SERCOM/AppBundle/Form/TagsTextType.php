<?php
/**
 * Created by PhpStorm.
 * User: Lwaxana
 * Date: 06/05/2015
 * Time: 08:20
 */

namespace SERCOM\AppBundle\Form;

use Doctrine\Common\Persistence\ObjectManager;
use SERCOM\AppBundle\Form\DataTransformer\TagToStringTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;


class TagsTextType extends AbstractType{

    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * Constructor
     *
     * @param ObjectManager $om
     */
    function __construct( ObjectManager $om )
    {
        $this->om = $om;
    }

    public function buildForm( FormBuilderInterface $builder, array $options )
    {
        $transformer = new TagToStringTransformer( $this->om );
        $builder->addModelTransformer( $transformer );
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return "text";
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'tags_text';
    }
} 