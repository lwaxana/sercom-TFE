<?php

namespace SERCOM\AppBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AsblDocumentCatType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name','text')
            ->add('description','text')
            ->add('sitegroup', 'entity', array( 'class' => 'SERCOMAppBundle:SiteGroup',
                                                'property' => 'description',
                                                'query_builder' => function(EntityRepository $er){
                                                    return $er->createQueryBuilder('u')->where('u.name LIKE ?1')->setParameter(1,"ROLE%");
                                                }
            ))
            ->add('save','submit')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SERCOM\AppBundle\Entity\AsblDocumentCat'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sercom_appbundle_asbldocumentcat';
    }
}
