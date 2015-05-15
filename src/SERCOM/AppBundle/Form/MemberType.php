<?php

namespace SERCOM\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MemberType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('actif')
            ->add('profstatus','textarea')
            ->add('person')
            ->add('asblfunctions','entity', array('class'=> 'SERCOMAppBundle:AsblFunction', 'property' => 'name', 'multiple' => true))
            ->add('forumgroups', 'entity', array('class' => 'SERCOMAppBundle:ForumGroup', 'property' => 'name', 'multiple' => true, 'expanded' => true))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SERCOM\AppBundle\Entity\Member'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sercom_appbundle_member';
    }
}
