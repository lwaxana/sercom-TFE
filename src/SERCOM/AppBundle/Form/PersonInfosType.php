<?php
/**
 * Created by PhpStorm.
 * User: Lwaxana
 * Date: 12/04/2015
 * Time: 20:55
 */

namespace SERCOM\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PersonInfosType extends AbstractType{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder



            ->remove('username','text')
            ->remove('password','password')
            ->remove('picture','text')
            ->remove('address', new AddressType())
            ->remove('countries', 'entity', array('class' => 'SERCOMAppBundle:Country', 'property' => 'name', 'expanded' => true, 'multiple' => true));

    }

    public function getParent(){
        return new PersonType();
}

    /**
     * @return string
     */
    public function getName()
    {
        return 'sercom_appbundle_person_modify1';
    }
} 