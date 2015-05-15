<?php
/**
 * Created by PhpStorm.
 * User: Lwaxana
 * Date: 16/04/2015
 * Time: 14:59
 */

namespace SERCOM\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EventDateProp2Type extends AbstractType{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('member')
            ->remove('events')
            ->add('datehour','datetime', array('widget' => 'single_text', 'label' => false))
        ;
    }

    public function getParent(){
        return new EventDateProposalType();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sercom_appbundle_eventdateproposal2';
    }

} 