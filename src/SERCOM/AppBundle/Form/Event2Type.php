<?php
/**
 * Created by PhpStorm.
 * User: Lwaxana
 * Date: 16/04/2015
 * Time: 15:01
 */

namespace SERCOM\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class Event2Type extends AbstractType{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('validate', 'checkbox', array('required' => 'false'))
            ->remove('dateHourEvent','datetime', array('required'=>'false'))
            ->remove('member', new MemberEventType())
            ->add('dateproposals', 'collection', array('type' => new EventDateProp2Type(), 'allow_add' => true, 'allow_delete' => true, 'label' => false));
        ;
    }

    public function getParent(){
        return new EventType();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sercom_appbundle_event2';
    }
} 