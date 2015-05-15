<?php
/**
 * Created by PhpStorm.
 * User: Lwaxana
 * Date: 27/04/2015
 * Time: 12:58
 */

namespace SERCOM\AppBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class PersonModifRightsType extends AbstractType{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('membre', 'choice', array('choices' => array(' '), 'required' => false, 'mapped' => false, 'expanded' => true, 'multiple' => true, 'data' => array($options['attr']['m']) ));
        if ( $options['attr']['d'] == "ROLE_PRESIDENT" ){
            $builder->add('accesmembre', 'entity', array( 'mapped' => false, 'class' => 'SERCOMAppBundle:SiteGroup', 'property' => 'description',
                'query_builder' => function(EntityRepository $er ){
                    return $er->createQueryBuilder('u')->where('u.name LIKE :role AND u.name NOT LIKE :role2 AND u.name NOT LIKE :role3')
                        ->setParameters(array('role' =>'ROLE%', 'role2' => "ROLE_TEACHER", 'role3' => "ROLE_STUDENT"));
                }, 'empty_value' => 'Choisir un accès', 'required' => false

            ));
        }
        if ( $options['attr']['d'] == "ROLE_ANIMATEUR" ){
            $builder->add('accesmembre', 'entity', array( 'mapped' => false, 'class' => 'SERCOMAppBundle:SiteGroup', 'property' => 'description',
                'query_builder' => function(EntityRepository $er ){
                    return $er->createQueryBuilder('u')->where('u.name LIKE :role AND u.name NOT LIKE :role2  AND u.name NOT LIKE :role3 AND u.name NOT LIKE :role4')
                        ->setParameters(array('role' => 'ROLE%', 'role2' => 'ROLE_PRESIDENT', 'role3' => 'ROLE_TEACHER', 'role4' => 'ROLE_STUDENT' ));
                }, 'empty_value' => 'Choisir un accès', 'required' => false

            ));
        }
        $builder->add('teacher', 'choice', array('choices' => array(' '), 'required' => false, 'mapped' => false, 'expanded' => true, 'multiple' => true, 'data' => array($options['attr']['t']) ));

        $builder->add('accesprof', 'entity', array( 'mapped' => false, 'class' => 'SERCOMAppBundle:SiteGroup', 'property' => 'description',
            'query_builder' => function(EntityRepository $er ){
                return $er->createQueryBuilder('u')->where('u.name LIKE :role ')->setParameter('role','ROLE_TEACHER');
            }, 'empty_value' => 'Choisir un accès', 'required' => false

        ));


        $builder->add('student', 'choice', array('choices' => array(' ' ), 'required' => false, 'mapped' => false, 'expanded' => true, 'multiple' => true, 'data' => array($options['attr']['s']) ));
        $builder->add('accesetudiant', 'entity', array( 'mapped' => false, 'class' => 'SERCOMAppBundle:SiteGroup', 'property' => 'description',
            'query_builder' => function(EntityRepository $er ){
                return $er->createQueryBuilder('u')->where('u.name LIKE :role ')->setParameter('role','ROLE_STUDENT');
            }, 'empty_value' => 'Choisir un accès', 'required' => false

        ));

        $builder->add('save', 'submit');

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
        return 'sercom_appbundle_person_modif_rights';
    }

} 