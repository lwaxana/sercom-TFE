<?php
/**
 * Created by PhpStorm.
 * User: Lwaxana
 * Date: 10/04/2015
 * Time: 12:06
 */

namespace SERCOM\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class ForumGroupAddForumType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->remove('forums')
            ->remove('members')
        ;
    }

    public function getParent(){
        return new ForumGroupType();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sercom_appbundle_forumgroup_addforum';
    }

} 