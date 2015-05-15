<?php
/**
 * Created by PhpStorm.
 * User: Lwaxana
 * Date: 12/04/2015
 * Time: 20:37
 */

namespace SERCOM\AppBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ForumTopicAddTopic2Type extends AbstractType {

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
            ->remove('priority')
            ->remove('locked');


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
        return 'sercom_appbundle_forumtopic_addpost2';
    }
} 