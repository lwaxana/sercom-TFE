<?php
/**
 * Created by PhpStorm.
 * User: Lwaxana
 * Date: 24/04/2015
 * Time: 22:25
 */

namespace SERCOM\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class MemberSearchType extends AbstractType {

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
        return 'sercom_appbundle_member_search';
    }

}
