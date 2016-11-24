<?php
namespace CSIDBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class PictogramCategoryAdmin extends Admin
{
	protected $translationDomain = 'csid';
	
	protected function configureFormFields(FormMapper $formMapper)
	{
		$image = $this->getSubject();
		
		$fileFieldOptions = array('required' => true, 'label' => 'pictrogram.image', 'attr' => array('class' => 'fileInputPreview'));
		if ($image && ($filename = $image->getFilename())) {
			$container = $this->getConfigurationPool()->getContainer();
			if($filename != "") {
				$fullPath = $container->get('request')->getBasePath().'/'.$image->getWebPath();
				$preview = '<div class="imgFilePreview"><img src="'.$fullPath.'" /></div>';
			}
		} else {
			$preview = '<div class="imgFilePreview"></div>';
		}
		$fileFieldOptions['help'] = $preview;
		
		if($image->getId() != null) {
			$fileFieldOptions['required'] = false;
		}
		
		$formMapper->with('admin.general', array('class' => 'col-md-6', 'collapsed' => true))
		->add('name', 'text', array('label' => 'category.name'))
		->add('file', 'file', $fileFieldOptions)
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

	protected function configureDatagridFilters(DatagridMapper $datagridMapper)
	{
		$datagridMapper->add('name');
	}

	protected function configureListFields(ListMapper $listMapper)
	{
		$listMapper->add('image', 'string', array('template' => 'CSIDBundle:MatterColorAdmin:list_image.html.twig', 'label' => 'pictrogram.image'));
		$listMapper->addIdentifier('name', null, array('label' => 'category.name'));
	}
}