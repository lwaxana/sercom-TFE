<?php

namespace SERCOM\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ForumTopicType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text')
            ->add('dateTopic','date')
            ->add('locked', 'checkbox', array('label' => 'Sujet verrouillé ?', 'required' => false))
            ->add('priority','choice', array('choices' => array('1' => 'Annonce', '2' => 'Post-it', "3" => 'Sujet Ordinaire')))
            ->add('member', 'integer')
            ->add('forum', 'integer')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SERCOM\AppBundle\Entity\ForumTopic'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sercom_appbundle_forumtopic';
    }
}
