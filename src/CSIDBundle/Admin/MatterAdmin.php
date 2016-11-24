<?php
namespace CSIDBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class MatterAdmin extends Admin
{
	protected $translationDomain = 'csid';
	
	protected function configureFormFields(FormMapper $formMapper)
	{
		$image = $this->getSubject();
		
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
			->add('maxHeight', 'number', array('label' => 'admin.max_height'))
			->add('maxWidth', 'number', array('label' => 'admin.max_width'))
            ->add('pricePerM2', 'number', array('label' => 'admin.pricePerM2'))
            ->add('prciePrintPerM2', 'number', array('label' => 'admin.prciePrintPerM2'))
            ->add('pricePerHole', 'number', array('label' => 'admin.pricePerHole'))
            ->add('pricePerRoundedCorner', 'number', array('label' => 'admin.pricePerRoundedCorner'))
             ->add('priceFixedFlagship', 'number', array('label' => 'admin.priceFixedFlagship'))
			->add('file', 'file', $fileFieldOptions)
			->add('description', 'textarea', array('label' => 'admin.description', 'attr' => array('class' => 'ckeditor')))
		->end()
		->with('admin.matter_colors', array('class' => 'col-md-6'))
			->add('colors', 'sonata_type_model', array(
					'required' => false,
					'expanded' => true,
					'multiple' => true,
					'label' => 'admin.matter_colors'
			))
		->end()
		->with('admin.fixings', array('class' => 'col-md-6'))
			->add('fixings', 'sonata_type_model', array(
				'required' => false,
				'expanded' => true,
				'multiple' => true,
				'label' => 'admin.fixings'
			))
		->end()
		->with('admin.dimensions', array('class' => 'col-md-6'))
			->add('dimensions', 'sonata_type_model', array(
				'required' => false,
				'expanded' => true,
				'multiple' => true,
				'label' => 'admin.dimensions'
			))
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
		$object->uploadFileFoncee();
		$object->uploadFileOriginale();
		$object->uploadFileContrePlaque();
		$this->getModelManager()->update($object);
	}

	protected function configureDatagridFilters(DatagridMapper $datagridMapper)
	{
		$datagridMapper->add('name');
	}

	protected function configureListFields(ListMapper $listMapper)
	{
		$listMapper->addIdentifier('name', null, array('label' => 'matter.name'));
	}
}