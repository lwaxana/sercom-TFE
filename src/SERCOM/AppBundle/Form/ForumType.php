<?php

namespace SERCOM\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class ForumType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('forumgroups', 'entity', array(   'mapped'   => true,    'class'    => 'SERCOMAppBundle:ForumGroup',     'property' => 'name', 'multiple' => true , 'expanded' => true))
            ->add('save', 'submit');

    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SERCOM\AppBundle\Entity\Forum'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sercom_appbundle_forum';
    }
}
