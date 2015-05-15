<?php
/**
 * Created by PhpStorm.
 * User: Lwaxana
 * Date: 27/04/2015
 * Time: 21:34
 */

namespace SERCOM\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class PersonSiteGroupType extends AbstractType {

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
            ->remove('tel','text', array('required' => false))
            ->remove('telgsm', 'text', array('required' => false))
            ->remove('username','text')
            ->remove('password','repeated', array('first_name' => 'password', 'second_name' =>'confirm', 'type'=> 'password'))
            ->remove('picture','text')
            ->remove('address', new AddressType())
            ->remove('countries', 'entity', array('mapped' =>true , 'class' => 'SERCOMAppBundle:Country', 'property' => 'name', 'expanded' => true, 'multiple' => true))
            ->add('sitegroups', 'entity', array('mapped' => true, 'class' => 'SERCOMAppBundle:SiteGroup', 'property' => 'description', 'required' => false, 'multiple' => true, 'expanded' => true,
                'query_builder' => function(EntityRepository $er)  {
                    return $er->createQueryBuilder('u')->where('u.name LIKE ?1')->setParameter(1, 'ADMIN%' );   }
            ))
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
        return 'sercom_appbundle_person_sitegroups';
    }
} 