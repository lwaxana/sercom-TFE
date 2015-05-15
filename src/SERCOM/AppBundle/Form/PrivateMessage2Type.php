<?php
/**
 * Created by PhpStorm.
 * User: Lwaxana
 * Date: 01/05/2015
 * Time: 15:49
 */

namespace SERCOM\AppBundle\Form;



use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;


class PrivateMessage2Type extends AbstractType{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {



        $builder
            ->add('subject','text')
            ->remove('dateMessage')
            ->add('messagecontent','textarea', array('required' => false))
            ->remove('message_delete')
            ->add('attachements','collection', array('type'=> new AttachementType(), 'allow_add' => true, 'allow_delete' => true, 'required'=>false))
            ->remove('sender','text')
            ->add('persons','entity', array('class'=>'SERCOMAppBundle:Person', 'mapped'=>false, 'multiple'=> true, 'empty_value' => false,
                    'query_builder' => function(EntityRepository $er) use ($options) {
                        return $er->createQueryBuilder('p')
                            ->join('p.member','m')->where('p.validate = 1 AND p.person_id != ?1 AND m.actif = 1')->setParameter(1, $options['attr']['idp']);  }))
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