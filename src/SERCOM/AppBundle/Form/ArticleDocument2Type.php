<?php
/**
 * Created by PhpStorm.
 * User: Lwaxana
 * Date: 21/04/2015
 * Time: 13:27
 */

namespace SERCOM\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ArticleDocument2Type extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('name')
            ->remove('picture')
            ->remove('article')
            ->remove('url')
            ->add('file','file', array('required' => false, 'label' => false))
        ;
    }

    public function getParent(){
        return new ArticleDocumentType();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sercom_appbundle_articledocument_2';
    }

} 