<?php
/**
 * Created by PhpStorm.
 * User: Lwaxana
 * Date: 12/04/2015
 * Time: 19:34
 */

namespace SERCOM\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;


class ForumDelGroupsType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('name')
            ->remove('description');


    }

    function getParent(){
        return new ForumType();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'forum_delgroups';
    }

} 