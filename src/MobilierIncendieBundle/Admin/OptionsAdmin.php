<?php
namespace MobilierIncendieBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use MobilierIncendieBundle\Form\ReductionsType;

class OptionsAdmin extends Admin
{
    protected $translationDomain = 'csid';


    protected function configureRoutes(RouteCollection $collection)
    {
        // to remove a single route
      //  $collection->remove('create');

    }
   protected function configureFormFields(FormMapper $formMapper)
    {
       /* $object= $this -> getSubject();
        $help="<h4>Changer l'option de produit ".$object->getProduits()->getName()."</h4>";
        $formMapper
            ->with('admin.general', array('class' => 'col-md-6', 'collapsed' => true))
            ->add('name',null, array('label' => 'admin.option',
                'help' => $help
            ))
            ->end();*/
        $formMapper
            ->with('admin.general', array('class' => 'col-md-6', 'collapsed' => true))
            ->add('produits',null, array('label' => 'admin.produits' ))
            ->add('name',null, array('label' => 'admin.option' ))
            ->add('prix' ,null,array('label'=>"Prix de base"))
            ->add('tarifs_degressifs', 'collection', array(
                'type' => new ReductionsType(),
                'required' => true,
                'allow_add' => true,
                'prototype' => true,
                'allow_delete' => true,
                'by_reference' => true,
                'label' => "Ajouter tarif dégressif pour cette option",
                'options' => array('label' => 'Tarif dégressif', 'label_attr' => array('class' => 'answers')),
            ),
                array(
                    'edit' => 'inline',
                    'inline' => 'table',
                    'sortable' => 'id',
                )
            )
        ;


    }


    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        //$datagridMapper->add('quantite_min' ,null ,array('label'=>'admin.quantite_min'));
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('id', null, array('label' => 'id'));
        $listMapper->add('produits.name', null, array('label' => 'admin.produit'));

        $listMapper->add('name', null, array('label' => 'admin.option'));
        $listMapper->add('prix', null, array('label' => 'Prix en €'));
          $listMapper->add('_action', 'actions', array(
                                            'actions' => array(
                                                'delete' => array(),
                                                'edit' => array(),
                                            )
                                  ))  ;
    }
    public function prePersist($object)
    {
        $this->preUpdate($object);
    }

    public function preUpdate($object)
    {
        $object->setTarifsDegressifs($object->getTarifsDegressifs());
      
    }
}