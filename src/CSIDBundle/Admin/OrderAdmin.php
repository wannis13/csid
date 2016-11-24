<?php
namespace CSIDBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;

class OrderAdmin extends Admin
{
	protected $translationDomain = 'csid';
	
	protected function configureRoutes(RouteCollection $collection)
	{
		$collection
			->remove('create')
			->remove('edit');
	
	}
	
	public static function getOrderStatus()
	{
		return array(
			'quotation' => 'Devis',
			'creation' => 'En cours',
			'order' => 'Commande'
		);
	}

	protected function configureDatagridFilters(DatagridMapper $datagridMapper)
	{
		$datagridMapper->add('number');
		$datagridMapper->add('quotationDate');
		$datagridMapper->add('status', null, array(), 'choice', array('choices' => OrderAdmin::getOrderStatus()));
	}

	protected function configureListFields(ListMapper $listMapper)
	{
		$listMapper->addIdentifier('number', null, array('label' => 'N° de commande', 'route' => array('name' => 'show')))
		->addIdentifier('created', null, array('label' => 'Date de création', 'route' => array('name' => 'show')))
		->addIdentifier('quotationDate', null, array('label' => 'Date du devis', 'route' => array('name' => 'show')))
		->addIdentifier('signatureDate', null, array('label' => 'Date de la facture', 'route' => array('name' => 'show')))
		->addIdentifier('status', 'choice', array('label' => 'Statut', 'choices' => OrderAdmin::getOrderStatus()))
		->addIdentifier('amount', null, array('label' => 'Montant H.T.'));
	}
	
	protected function configureShowFields(ShowMapper $showMapper)
	{
		$showMapper
		->add('number', null, array('label' => 'N° de commande'))
		->add('quotationDate', null, array('label' => 'Date du devis'))
		->add('signatureDate', null, array('label' => 'Date de la facture'))
		->add('status', 'choice', array('choices' => OrderAdmin::getOrderStatus(), 'label' => 'Statut'))
		->add('customer', null, array('label' => 'Client'))
		->add('createdBy', null, array('label' => 'Créateur'))
		->add('amount', null, array('label' => 'Montant H.T.'));
	}
}