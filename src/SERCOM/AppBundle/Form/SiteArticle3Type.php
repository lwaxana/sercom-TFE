<?php
/**
 * Created by PhpStorm.
 * User: Lwaxana
 * Date: 28/04/2015
 * Time: 23:36
 */

namespace SERCOM\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class SiteArticle3Type extends AbstractType{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $texte = $options['attr']['texte'];

        $builder

            ->add('title','text', array('required' => true))
            ->add('sitesection', 'entity', array('class' => 'SERCOMAppBundle:SiteSection', 'property' => 'name'))
            ->add('content','textarea', array('mapped' => false, 'required' => false,  'data' => $texte ))
            ->add('documents', 'collection', array( 'mapped' => false, 'type' => new ArticleDocument2Type(), 'allow_add' => true, 'allow_delete' => true, 'label' => false))
            ->add('tags','tags_text', array('required' => false))
            ->add('save','submit')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SERCOM\AppBundle\Entity\SiteArticle'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sercom_appbundle_sitearticle_3';
    }


} 