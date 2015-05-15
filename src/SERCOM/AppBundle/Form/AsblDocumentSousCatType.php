<?php

namespace SERCOM\AppBundle\Form;

use Proxies\__CG__\SERCOM\AppBundle\Entity\AsblDocumentCat;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AsblDocumentSousCatType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name','text')

            ->add('category','entity', array('class' => 'SERCOMAppBundle:AsblDocumentCat', 'property' => 'name'))
            ->add('save','submit')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SERCOM\AppBundle\Entity\AsblDocumentSousCat'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sercom_appbundle_asbldocumentsouscat';
    }
}
