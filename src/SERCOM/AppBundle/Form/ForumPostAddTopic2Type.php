<?php
/**
 * Created by PhpStorm.
 * User: Lwaxana
 * Date: 12/04/2015
 * Time: 20:41
 */

namespace SERCOM\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ForumPostAddTopic2Type extends AbstractType{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('moderated')
            ->remove('member')
            ->remove('datePost')

        ;
    }

    public function getParent(){
        return new ForumPost2Type();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sercom_appbundle_forumpost_addtopic2';
    }

} 