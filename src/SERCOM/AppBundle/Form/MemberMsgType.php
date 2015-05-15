<?php
/**
 * Created by PhpStorm.
 * User: Lwaxana
 * Date: 01/05/2015
 * Time: 17:41
 */

namespace SERCOM\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class MemberMsgType extends AbstractType{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {



        $builder
            ->remove('actif')
            ->remove('profstatus','text')
            ->add('person', new Person4Type())

            ->remove('asblfunctions','entity', array('class'=> 'SERCOMAppBundle:AsblFunction', 'property' => 'name', 'multiple' => true))
            ->remove('forumgroups', 'entity', array('class' => 'SERCOMAppBundle:ForumGroup', 'property' => 'name', 'multiple' => true, 'expanded' => true))
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
        return 'sercom_appbundle_member_msg';
    }


} 