<?php
/**
 * Created by PhpStorm.
 * User: Lwaxana
 * Date: 28/04/2015
 * Time: 22:57
 */

namespace SERCOM\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class ArticleDocumentTextType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $texte = $options['attr']['texte'];

        $builder
            ->remove('name')
            ->remove('url')
            ->remove('picture')
            ->add('article', new SiteArticle3Type())
            ->add('content','textarea', array('mapped' => false, 'data' => $texte ))
            ->add('save', 'submit')
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
        return 'sercom_appbundle_articledocument_text';
    }

} 