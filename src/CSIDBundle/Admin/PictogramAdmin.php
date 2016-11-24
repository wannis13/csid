<?php
namespace CSIDBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class PictogramAdmin extends Admin
{
	protected $translationDomain = 'csid';
	
	protected function configureFormFields(FormMapper $formMapper)
	{
		$image = $this->getSubject();
		
		$fileFieldOptions = array('required' => true, 'label' => 'pictrogram.image', 'attr' => array('class' => 'fileInputPreview'));
		if ($image && ($filename = $image->getFilename())) {
			$container = $this->getConfigurationPool()->getContainer();
			if($filename != "") {
				$fullPath = $container->get('request')->getBasePath().'/'.$image->getJpg();
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
		->add('name', 'text', array('label' => 'pictrogram.name'))
		->add('file', 'file', $fileFieldOptions)
		->end()
		->with('admin.category', array('class' => 'col-md-6'))
			->add('categories', 'sonata_type_model', array(
					'required' => false,
					'expanded' => true,
					'multiple' => true,
					'label' => 'pictrogram.category'
			))
			->end();
	}
	
	/*public function postPersist($pictrogram)
	{
		$uniqid = $this->getRequest()->query->get('uniqid');
		$svg = $this->getRequest()->request->get($uniqid);
		$pictrogram->setSVG($svg['svg']);
	}*/
	
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
		$listMapper->add('image', 'string', array('template' => 'CSIDBundle:PictogramAdmin:list_image.html.twig', 'label' => 'pictrogram.image'));
		$listMapper->addIdentifier('name', null, array('label' => 'pictrogram.name'));
		$listMapper->addIdentifier('categories', null, array('label' => 'pictrogram.category'));
	}
}