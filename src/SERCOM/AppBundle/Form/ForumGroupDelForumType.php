<?php
/**
 * Created by PhpStorm.
 * User: Lwaxana
 * Date: 11/04/2015
 * Time: 21:39
 */

namespace SERCOM\AppBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class ForumGroupDelForumType extends AbstractType{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('name')
            ->remove('description')
            ->remove('members')
            ->add('save', 'submit')
        ;
    }

   public function getParent(){
       return new ForumGroupType();
   }
    /**
     * @return string
     */
    public function getName()
    {
        return 'sercom_appbundle_forumgroup_delforum';
    }
} 