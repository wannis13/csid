<?php
namespace CSIDBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class DimensionAdmin extends Admin
{
	protected $translationDomain = 'csid';
	
	protected function configureFormFields(FormMapper $formMapper)
	{
		$formMapper->with('admin.general', array('class' => 'col-md-6', 'collapsed' => true))
		->add('name', 'text', array('label' => 'admin.label'))
		->add('height', 'number', array('label' => 'dimension.height'))
		->add('width', 'number', array('label' => 'dimension.width'))
		->end();
	}

	protected function configureDatagridFilters(DatagridMapper $datagridMapper)
	{
		$datagridMapper->add('name');
	}

	protected function configureListFields(ListMapper $listMapper)
	{
		$listMapper->addIdentifier('name', null, array('label' => 'dimension.name'));
		$listMapper->addIdentifier('height', null, array('label' => 'dimension.height'));
		$listMapper->addIdentifier('width', null, array('label' => 'dimension.width'));
	}
}