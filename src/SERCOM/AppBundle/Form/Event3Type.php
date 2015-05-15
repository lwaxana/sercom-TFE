<?php
/**
 * Created by PhpStorm.
 * User: Lwaxana
 * Date: 17/04/2015
 * Time: 17:45
 */

namespace SERCOM\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class Event3Type extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('name')
            ->remove('description')
            ->remove('member', new MemberEventType())

            ->remove('dateHourEvent', 'entity', array(  'class' => 'SERCOMAppBundle:EventDateProposal',
                                                     'property' => 'datehour',
                                                     'query_builder' => function(EntityRepository $er)  use ($options) {
                                                        return $er->createQueryBuilder('u')->where('u.events = ?1')->orderBy('u.datehour', 'ASC')->setParameter(1, $options['attr']['id'] );   },
                                                     'expanded' => true, 'multiple' => false,

            ));

    }

    public function getParent(){
        return new EventType();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sercom_appbundle_event_3';
    }

} 