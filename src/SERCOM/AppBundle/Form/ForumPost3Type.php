<?php
/**
 * Created by PhpStorm.
 * User: Lwaxana
 * Date: 24/04/2015
 * Time: 21:03
 */

namespace SERCOM\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class ForumPost3Type  extends AbstractType{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('datePost','date')
            ->add('post_content','textarea', array('required' => false))
            ->remove('moderated')
            ->remove('member','entity', array('class' => 'SERCOMAppBundle:Member'))
            ->remove('forumtopic', new ForumTopicAddTopic2Type())
            ->add('save','submit')
        ;
    }

    public function getParent()
    {
        return new ForumPostType();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sercom_appbundle_forumpost_edit';
    }
}

