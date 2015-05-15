<?php
/**
 * Created by PhpStorm.
 * User: Lwaxana
 * Date: 06/05/2015
 * Time: 11:45
 */

namespace SERCOM\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class DocumentSearchType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('search','search', array('required' => true, 'trim' => true))
            ->add('save','submit')
        ;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sercom_appbundle_document_search';
    }

} 