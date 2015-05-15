<?php
/**
 * Created by PhpStorm.
 * User: Lwaxana
 * Date: 16/04/2015
 * Time: 14:49
 */

namespace SERCOM\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class MemberEventType extends AbstractType {



    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $option = $options['attr']['id'];

        $builder
            ->remove('actif')
            ->remove('profstatus','text')
            ->remove('person', new PersonType())
            ->remove('asblfunctions','entity', array('class'=> 'SERCOMAppBundle:AsblFunction', 'property' => 'name', 'multiple' => true))
            ->remove('forumgroups', 'entity', array('class' => 'SERCOMAppBundle:ForumGroup', 'property' => 'name', 'multiple' => true, 'expanded' => true))
            ->add('dateproposals', 'entity',array( 'class' => 'SERCOMAppBundle:EventDateProposal',
                                                            'property' => 'evt_id',
                                                            'query_builder' => function(EntityRepository $er) use ($options){
                                                             return $er->createQueryBuilder('u')->where('u.events = ?1')->addOrderBy('u.datehour')->setParameter(1, $options['attr']['id']  );},
                                                            'multiple' => true,
                                                            'expanded' => true


            ))
        ;
    }

    public function getParent(){
        return new MemberType();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sercom_appbundle_member_event';
    }
} 