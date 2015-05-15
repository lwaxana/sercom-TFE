<?php
/**
 * Created by PhpStorm.
 * User: Lwaxana
 * Date: 06/04/2015
 * Time: 12:37
 */

namespace SERCOM\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PersonInscriptionType extends AbstractType{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->remove('picture')
        ->remove('address')
        ->remove('tel')
        ->remove('telgsm')
        ->remove('countries');

    }

    public function getParent(){
        return new PersonType();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sercom_appbundle_person_inscription';
    }
}