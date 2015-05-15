<?php
/**
 *
 * http://showmethecode.es/php/symfony/symfony2-4-dependent-forms/
 */

namespace SERCOM\AppBundle\Entity\EventListener;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Doctrine\ORM\EntityRepository;

class AddCatFieldSubscriber implements EventSubscriberInterface{

    private $propPathToDoc;

    public function __construct($propPathToDoc){
        $this->propPathToDoc = $propPathToDoc;
    }

    public static function getSubscribedEvents(){
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT => 'preSubmit'
        );
    }

    private function addCatForm($form, $cat = null){
        $formOptions = array(
            'class' => 'SERCOMAppBundle:AsblDocumentCat',
            'mapped' => false,
            'property' => 'name',
            'label' => 'Catgorie',
            'empty_value' => 'Categorie',
            'attr' => array('class' => 'category_selector')
        );
        if ( $cat ){
            $formOptions['data'] = $cat;
        }
        $form->add( 'cat' , 'entity', $formOptions);
    }

    public function preSetData(FormEvent $event){
        $data = $event->getData();
        $form = $event->getForm();

        if ( null === $data){
            return;
        }

        $accessor = PropertyAccess::createPropertyAccessor();
        $sous_cat = $accessor->getValue($data, $this->propPathToDoc);
        $cat = ($sous_cat)? $sous_cat->getCategory():null;
        $this->addCatForm($form, $cat);
    }

    public function preSubmit(FormEvent $event){
        $data = $event->getData();
        $form = $event->getForm();



        $this->addCatForm($form);
    }

} 