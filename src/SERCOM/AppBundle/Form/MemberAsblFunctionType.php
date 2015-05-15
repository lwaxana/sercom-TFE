<?php
/**
 * Created by PhpStorm.
 * User: Lwaxana
 * Date: 27/04/2015
 * Time: 21:45
 */

namespace SERCOM\AppBundle\Form;

use SERCOM\AppBundle\Entity\Member;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MemberAsblFunctionType extends AbstractType{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('actif')
            ->remove('profstatus','text')
            ->remove('person')
            ->add('asblfunctions','entity', array('mapped' => true, 'class'=> 'SERCOMAppBundle:AsblFunction', 'property' => 'fonction', 'multiple' => true, 'expanded' => true, 'required' => false))
            ->remove('forumgroups', 'entity', array('class' => 'SERCOMAppBundle:ForumGroup', 'property' => 'fonction', 'multiple' => true, 'expanded' => true))
            ->add('save','submit')
        ;
    }

    public function getParent(){
        return new MemberType();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sercom_appbundle_member_asblfct';
    }

} 