<?php
/**
 * Created by PhpStorm.
 * User: Lwaxana
 * Date: 17/04/2015
 * Time: 17:46
 */

namespace SERCOM\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;


class EventDateProp3Type extends AbstractType{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('datehour','datetime')
            ->add('events', new Event3Type())
            ->remove('member', new MemberType())
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
        return 'sercom_appbundle_eventdateproposal_3';
    }

} 