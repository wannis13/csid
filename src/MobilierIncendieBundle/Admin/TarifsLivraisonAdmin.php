<?php
namespace MobilierIncendieBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class TarifsLivraisonAdmin extends Admin
{
    protected $translationDomain = 'csid';


    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('create');
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $object = $this->getSubject();
        $help = "<h4>Changer le prix de produit " . $object->getProduits()->getName() . "</h4>";
        $formMapper
            ->with('admin.general', array('class' => 'col-md-6', 'collapsed' => true))
            ->add('quantite_min', 'number', array('label' => 'admin.quantite_min'))
            ->add('quantite_max', 'number', array('label' => 'admin.quantite_max'))
            ->add('prix_unitaire', 'number', array('label' => 'admin.prix'
                ,  'help' => $help
                )
            )
            ->end();
    }
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
       // $datagridMapper->add('quantite_min', null, array('label' => 'admin.quantite_min'));
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('id', null, array('label' => 'id'));
        $listMapper->add('produits.name', null, array('label' => 'admin.produit'));
        $listMapper->add('quantite_min', null, array('label' => 'admin.quantite_min'));
        $listMapper->add('quantite_max', null, array('label' => 'admin.quantite_max'));
        $listMapper->add('prix_unitaire', null, array('label' => 'admin.prix'));
        $listMapper->add('_action', 'actions', array(
            'actions' => array(
                'delete' => array(),
                'edit' => array(),
            )
        ));
    }
}