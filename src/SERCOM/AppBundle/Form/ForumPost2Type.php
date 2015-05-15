<?php
/**
 * Created by PhpStorm.
 * User: Lwaxana
 * Date: 12/04/2015
 * Time: 20:39
 */

namespace SERCOM\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ForumPost2Type extends AbstractType{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('datePost','date')
            ->add('post_content','textarea', array('required' => false))
            ->add('moderated')
            ->add('member','entity', array('class' => 'SERCOMAppBundle:Member'))
            ->add('forumtopic', new ForumTopicAddTopic2Type())
            ->add('save','submit')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SERCOM\AppBundle\Entity\ForumPost'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sercom_appbundle_forumpost';
    }
} 