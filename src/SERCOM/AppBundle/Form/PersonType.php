<?php

namespace SERCOM\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class PersonType extends AbstractType
{
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
            ->add('tel','text', array('required' => false))
            ->add('telgsm', 'text', array('required' => false))
            ->add('username','text')
            ->add('password','repeated', array('first_name' => 'password',
                                                'second_name' =>'confirm',
                                                'type'=> 'password',
                                                'constraints' => array( new NotBlank(), new Regex( array('pattern' => "/^(?=.*\\d){2}(?=.*[a-z]){2}(?=.*[A-Z]){2}.{8,25}$/"
                                                , 'message' => "Min 2 MAJ, 2 min, 2 chiffres 8 à 25 caract"))))  )


            ->add('picture','text')
            ->add('address', new AddressType())
            ->add('countries', 'entity', array('mapped' =>true , 'class' => 'SERCOMAppBundle:Country', 'property' => 'name', 'multiple' => true))
            ->add('save', 'submit');
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SERCOM\AppBundle\Entity\Person'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sercom_appbundle_person';
    }
}
