<?php

namespace SERCOM\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ForumPostAddTopicType extends AbstractType
{
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
        return new ForumPostType();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sercom_appbundle_forumpost_addtopic';
    }
}
