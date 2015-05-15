<?php
/**
 * Created by PhpStorm.
 * User: Lwaxana
 * Date: 14/04/2015
 * Time: 14:03
 */

namespace SERCOM\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class PersonPictureType extends AbstractType{

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
            ->remove('picture','text')
            ->remove('password','password', array('required' => false))
            ->remove('address', new AddressType())
            ->remove('countries', 'entity')
            ->add('file','file')
            ->add('save', 'submit');
    }

   public function getParent(){
       return new PersonType();
   }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sercom_appbundle_person_picture_2';
    }

} 