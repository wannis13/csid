<?php
namespace MobilierIncendieBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class TarifClientSpecialAdmin extends Admin
{
    protected $translationDomain = 'csid';


    protected function configureRoutes(RouteCollection $collection)
    {
        // to remove a single route
        $collection->remove('create');

    }
   protected function configureFormFields(FormMapper $formMapper)
    {
        $object= $this -> getSubject();
        $help="<h4>Changer le prix de produit ".$object->getProduits()->getName()." pour le client ".$object->getClient()->getFirstname(). " " .$object->getClient()->getLastname()."</h4>";
        $formMapper
            ->with('admin.general', array('class' => 'col-md-6', 'collapsed' => true))
            ->add('prix','number', array('label' => 'admin.prix',
                'help' => $help
            ))
            ->end();
    }


    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        //$datagridMapper->add('quantite_min' ,null ,array('label'=>'admin.quantite_min'));
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('id', null, array('label' => 'id'));
        $listMapper->add('produits.name', null, array('label' => 'admin.produit'));
        $listMapper->add('client.firstname', null, array('label' => 'admin.firstname'));
        $listMapper->add('client.lastname', null, array('label' => 'admin.lastname'));
        $listMapper->add('prix', null, array('label' => 'admin.prix'));
          $listMapper->add('_action', 'actions', array(
                                            'actions' => array(
                                                'delete' => array(),
                                                'edit' => array(),
                                            )
                                  ))  ;
    }
}