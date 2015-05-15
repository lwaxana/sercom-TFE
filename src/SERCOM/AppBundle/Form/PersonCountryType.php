<?php
/**
 * Created by PhpStorm.
 * User: Lwaxana
 * Date: 12/04/2015
 * Time: 21:33
 */

namespace SERCOM\AppBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class PersonCountryType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('lastname', 'text')
            ->remove('firstname', 'text')
            ->remove('email','email')
            ->remove('tel','text')
            ->remove('telgsm', 'text')
            ->remove('username','text')
            ->remove('password','repeated', array('first_name' => 'password', 'second_name' =>'confirm', 'type'=> 'password'))
            ->remove('picture','text')
            ->remove('address', new AddressType());

    }

    public function getParent(){
        return new PersonType();
}

    /**
     * @return string
     */
    public function getName()
    {
        return 'sercom_appbundle_person_country';
    }

} 