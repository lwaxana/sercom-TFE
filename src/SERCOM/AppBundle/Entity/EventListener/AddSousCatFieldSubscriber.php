<?php
/**
 *   http://showmethecode.es/php/symfony/symfony2-4-dependent-forms/
 */

namespace SERCOM\AppBundle\Entity\EventListener;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Doctrine\ORM\EntityRepository;
use SERCOM\AppBundle\Entity\AsblDocumentSousCat;
use SERCOM\AppBundle\Entity\AsblDocument;
use SERCOM\AppBundle\Entity\AsblDocumentCat;

class AddSousCatFieldSubscriber implements EventSubscriberInterface {

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

    private function addSousCatForm($form, $cat_id){
        $formOptions = array(
            'class' => 'SERCOMAppBundle:AsblDocumentSousCat',
            'empty_value' => 'Sous catégorie',
            'label' => 'Sous Catégorie',
            'mapped' => false,
            'attr' => array('class' => 'souscat_selector',
        ),
            'query_builder' => function ( EntityRepository $repository) use ($cat_id){
                $qb = $repository->createQueryBuilder('souscat')
                    ->innerJoin('souscat.category', 'categorie')
                    ->where('categorie = :categorie')
                    ->setParameter('categorie', $cat_id);
                return $qb;
            }
        );
        $form->add($this->propPathToDoc, 'entity', $formOptions);
    }

    public function preSetData(FormEvent $event){
        $data = $event->getData();
        $form = $event->getForm();

        if ( null === $data){
            return;
        }

        $accessor = PropertyAccess::createPropertyAccessor();
        $cat_id = $accessor->getValue($data, $this->propPathToDoc);
        $this->addSousCatForm($form, $cat_id);
    }

    public function preSubmit(FormEvent $event){
        $data = $event->getData();
        $form = $event->getForm();

        $cat_id = array_key_exists('category', $data) ? $data['category']:null;

        $this->addSousCatForm($form, $cat_id);
    }

} 