<?php
namespace MobilierIncendieBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;

class QuestionsAdmin extends Admin
{
    protected $translationDomain = 'csid';


    protected function configureRoutes(RouteCollection $collection)
    {
        // to remove a single route
        $collection->remove('create');
        $collection->remove('edit');

    }
   protected function configureFormFields(FormMapper $formMapper)
    {
        $object= $this -> getSubject();
        $help="<h4>Changer l'option de produit ".$object->getProduits()->getName()."</h4>";
        $formMapper
            ->with('admin.general', array('class' => 'col-md-6', 'collapsed' => true))
            ->add('nom' ,null , array('required'=>true ,'label'=>'Nom'))
            ->add('prenom' ,null , array('required'=>true ,'label'=>'Prénom'))
            ->add('email' ,null , array('required'=>true ,'label'=>'Email'))
            ->add('message' ,null , array('required'=>true ,'label'=>'Message'))
           /* ->add('name',null, array('label' => 'admin.option',
                'help' => $help
            ))*/
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

        $listMapper->add('nom', null, array('label' => 'admin.nom'));
        $listMapper->add('prenom', null, array('label' => 'admin.prenom'));
        $listMapper->add('email', null, array('label' => 'admin.email'));
        $listMapper->add('message', null, array('label' => 'admin.message'));


        $listMapper->add('_action', 'actions', array(
                                            'actions' => array(
                                                'delete' => array(),
                                                /*'edit' => array(),*/
                                                'show'=>array()
                                            )
                                  ))  ;
    }

    public function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
           

            ->add('produits.name', null, array('label' => 'admin.produit'))
            ->add('nom', null, array('label' => 'admin.nom'))
            ->add('prenom', null, array('label' => 'admin.prenom'))
            ->add('email', null, array('label' => 'admin.email'))
            ->add('message', null, array('label' => 'admin.message'))

            ->end();
    }
}