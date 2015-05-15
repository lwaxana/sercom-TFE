<?php
/**
 * Created by PhpStorm.
 * User: Lwaxana
 * Date: 07/05/2015
 * Time: 11:41
 */

namespace SERCOM\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use SERCOM\AppBundle\Entity\EventListener\AddCatFieldSubscriber;
use SERCOM\AppBundle\Entity\EventListener\AddSousCatFieldSubscriber;


class AsblDocument2Type extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //http://showmethecode.es/php/symfony/symfony2-4-dependent-forms/
        $propPathtoDoc = "souscat";

        $builder//->addEventSubscriber(new AddSousCatFieldSubscriber($propPathtoDoc))
                ->addEventSubscriber(new AddCatFieldSubscriber($propPathtoDoc));

        $builder
            ->add('tags','tags_text', array('required' => false))
            ->add('file','file')
            ->add('souscat','entity', array('class' => 'SERCOMAppBundle:AsblDocumentSousCat', 'property' => 'name'))
            ->add('save','submit')
        ;


    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SERCOM\AppBundle\Entity\AsblDocument',

        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sercom_appbundle_asbldocument_2';
    }

} 