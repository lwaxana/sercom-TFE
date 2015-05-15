<?php

namespace SERCOM\AppBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ClasseType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name','text')
            ->add('suject','entity', array('class' => 'SERCOMAppBundle:Subject', 'multiple' => false, 'property' => 'name'))
            ->add('teacher', 'entity', array('class' => 'SERCOMAppBundle:Person', 'multiple' => false, 'mapped' => false,
                                             'query_builder' => function(EntityRepository $er){
                                                 return $er->createQueryBuilder('p')->join('p.teacher','t')->where('t.person = p.person_id and t.actif = 1');  } ))
            ->add('students', 'entity', array('class' => 'SERCOMAppBundle:Student', 'multiple' => true, 'expanded'=>true, 'required' => false,
                                                 'query_builder' => function(EntityRepository $er){
                                                 return $er->createQueryBuilder('p')->join('p.person','t')->where('p.actif = 1');  } ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SERCOM\AppBundle\Entity\Classe'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sercom_appbundle_classe';
    }
}
