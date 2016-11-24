<?php
namespace CSIDBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class FixingAdmin extends Admin
{
	protected $translationDomain = 'csid';
	
	protected function configureFormFields(FormMapper $formMapper)
	{
		$image = $this->getSubject();
		
		$fileFieldOptions = array('required' => true, 'label' => 'matter.color.image');
		if ($image && ($webPath = $image->getWebPath())) {
			$container = $this->getConfigurationPool()->getContainer();
			$fullPath = $container->get('request')->getBasePath().'/'.$webPath;
			$fileFieldOptions['help'] = '<img src="'.$fullPath.'" class="admin-preview" />';
			$fileFieldOptions['help'] = '<img src="'.$container->get('liip_imagine.cache.manager')->getBrowserPath($fullPath, 'heighten_120').'" />';
		}
		
		$fileFieldOptions = array('required' => true, 'label' => 'pictrogram.image', 'attr' => array('class' => 'fileInputPreview'));
		if ($image && ($filename = $image->getWebPath())) {
			$container = $this->getConfigurationPool()->getContainer();
			if($filename != "") {
				$fullPath = $container->get('request')->getBasePath().'/'.$filename;
				$preview = '<div class="imgFilePreview"><img src="'.$fullPath.'" /></div>';
			}
		} else {
			$preview = '<div class="imgFilePreview"></div>';
		}
		
		if($image->getId() != null) {
			$fileFieldOptions['required'] = false;
		}
		
		$fileFieldOptions['help'] = $preview;
		
		$formMapper
		->with('admin.general', array('class' => 'col-md-6', 'collapsed' => true))
		->add('name', 'text', array('label' => 'admin.label'))
		->add('file', 'file', $fileFieldOptions)
		->add('price', 'number', array('label' => 'admin.price'))
		->add('description', 'textarea', array('label' => 'admin.description', 'attr' => array('class' => 'ckeditor')))
		->end();
	}
	
	/**
	 *
	 * {@inheritDoc}
	 * @see \Sonata\AdminBundle\Admin\Admin::postUpdate($object)
	 */
	public function postUpdate($object)
	{
		$object->upload();
		$this->getModelManager()->update($object);
	}
	
	/**
	 *
	 * {@inheritDoc}
	 * @see \Sonata\AdminBundle\Admin\Admin::postPersist($object)
	 */
	public function postPersist($object)
	{
		$object->upload();
		$this->getModelManager()->update($object);
	}

	protected function configureDatagridFilters(DatagridMapper $datagridMapper)
	{
		$datagridMapper->add('name');
	}

	protected function configureListFields(ListMapper $listMapper)
	{
		$listMapper->addIdentifier('name', null, array('label' => 'fixing.name'));
		$listMapper->addIdentifier('price', null, array('label' => 'fixing.price'));
	}
}