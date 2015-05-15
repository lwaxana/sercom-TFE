<?php
/**
 * Created by PhpStorm.
 * User: Lwaxana
 * Date: 02/05/2015
 * Time: 22:31
 */

namespace SERCOM\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;


class PrivateMessage3Type  extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options){

        $builder
            ->add('subject','text')
            ->remove('dateMessage')
            ->add('messagecontent','textarea', array('required' => false))
            ->remove('message_delete')
            ->remove('attachements')
            ->remove('sender','text')
            ->add('persons','entity', array('class'=>'SERCOMAppBundle:Person', 'mapped'=>false, 'multiple'=> false, 'empty_value' => false,
                'query_builder' => function(EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('p')
                        ->join('p.member','m')->where(' p.person_id = ?1')->setParameter(1, $options['attr']['idp']);  }))
            ->add('save','submit');

    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SERCOM\AppBundle\Entity\PrivateMessage'
        ) );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sercom_appbundle_privatemessage_2';
    }
} 