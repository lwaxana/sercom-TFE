<?php
/**
 * Created by PhpStorm.
 * User: Lwaxana
 * Date: 26/04/2015
 * Time: 15:26
 */

namespace SERCOM\AppBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class PersonModifInscriptionType extends AbstractType{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastname', 'text')
            ->add('firstname', 'text')
            ->add('email','email')
            ->remove('tel','text', array('required' => false))
            ->remove('telgsm', 'text', array('required' => false))
            ->add('username','text')
            ->remove('password','repeated', array('first_name' => 'password', 'second_name' =>'confirm', 'type'=> 'password'))
            ->remove('picture','text')
            ->remove('address', new AddressType())
            ->remove('countries', 'entity', array('mapped' =>true , 'class' => 'SERCOMAppBundle:Country', 'property' => 'name', 'expanded' => true, 'multiple' => true))
            ->add('membre', 'choice', array('choices' => array(' '), 'required' => false, 'mapped' => false, 'expanded' => true, 'multiple' => true, 'data' => array($options['attr']['m']) ))
            ->add('teacher', 'choice', array('choices' => array(' '), 'required' => false, 'mapped' => false, 'expanded' => true, 'multiple' => true, 'data' => array($options['attr']['t']) ))
            ->add('student', 'choice', array('choices' => array(' ' ), 'required' => false, 'mapped' => false, 'expanded' => true, 'multiple' => true, 'data' => array($options['attr']['s']) ))
            ->add('save', 'submit')
            ->remove('date_inscription');
    }

    public function getParent(){
        return new PersonType();
    }


    /**
     * @return string
     */
    public function getName()
    {
        return 'sercom_appbundle_person_modif_inscr';
    }
} 