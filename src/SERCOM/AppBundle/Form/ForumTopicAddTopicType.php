<?php

namespace SERCOM\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ForumTopicAddTopicType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('dateTopic')
            ->remove('member')
            ->remove('forum')


        ;
    }
    
    public function getParent(){
        return new ForumTopicType();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sercom_appbundle_forumtopic_addpost';
    }
}
