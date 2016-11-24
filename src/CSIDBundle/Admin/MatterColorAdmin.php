<?php
namespace CSIDBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Doctrine\ODM\PHPCR\Mapping\Annotations\PostPersist;

class MatterColorAdmin extends Admin
{
	protected $translationDomain = 'csid';
	
	protected function configureFormFields(FormMapper $formMapper)
	{
		$image = $this->getSubject();
		
		// Original
		$fileFieldOptions = array('required' => true, 'label' => 'admin.original_matter_image');
		if ($image && ($webPath = $image->getWebPath())) {
			$container = $this->getConfigurationPool()->getContainer();
			$fullPath = $container->get('request')->getBasePath().'/'.$webPath;
			$fileFieldOptions['help'] = '<img src="'.$fullPath.'" class="admin-preview" />';
			$fileFieldOptions['help'] = '<img src="'.$container->get('liip_imagine.cache.manager')->getBrowserPath($fullPath, 'heighten_120').'" />';
			if ($image && ($filename = $image->getWebPath())) {
				if($filename != "") {
					$fullPath = $container->get('request')->getBasePath().'/'.$filename;
					$preview = '<div class="imgFilePreview"><img src="'.$fullPath.'" /></div>';
				}
			}
		} else {
			$preview = '<div class="imgFilePreview"></div>';
		}
		$fileFieldOptions['help'] = $preview;
		
		// Dark 
		$fileDarkFieldOptions = array('required' => true, 'label' => 'admin.dark_matter_image.image');
		if ($image && ($webPath = $image->getDarkImagePath())) {
			$container = $this->getConfigurationPool()->getContainer();
			$fullPath = $container->get('request')->getBasePath().'/'.$webPath;
			$fileDarkFieldOptions['help'] = '<img src="'.$fullPath.'" class="admin-preview" />';
			$fileDarkFieldOptions['help'] = '<img src="'.$container->get('liip_imagine.cache.manager')->getBrowserPath($fullPath, 'heighten_120').'" />';
			if ($image && ($filename = $image->getDarkImagePath())) {
				if($filename != "") {
					$fullPath = $container->get('request')->getBasePath().'/'.$filename;
					$preview = '<div class="imgFilePreview"><img src="'.$fullPath.'" /></div>';
				}
			}
		} else {
			$preview = '<div class="imgFilePreview"></div>';
		}
		
		$fileDarkFieldOptions['help'] = $preview;
		
		if($image->getId() != null) {
			$fileDarkFieldOptions['required'] = false;
			$fileFieldOptions['required'] = false;
		}
		
		
		$formMapper->with('admin.general', array('class' => 'col-md-6', 'collapsed' => true))
		->add('name', 'text', array('label' => 'admin.label'))
		->add('file', 'file', $fileFieldOptions)
		->add('darkFile', 'file', $fileDarkFieldOptions)
		->end()
		->with('admin.matter', array('class' => 'col-md-6'))
		->add('matter', 'sonata_type_model', array(
			'required' => false,
			'expanded' => true,
			'multiple' => false,
			'label' => 'admin.matter'
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
		$object->uploadDarkImage();
		$this->getModelManager()->update($object);
	}
	
	public function prePersist($object)
	{
		$object->upload();
		$object->uploadDarkImage();
	}

	protected function configureDatagridFilters(DatagridMapper $datagridMapper)
	{
		$datagridMapper->add('name');
	}

	protected function configureListFields(ListMapper $listMapper)
	{
		$listMapper->add('image', 'string', array('template' => 'CSIDBundle:MatterColorAdmin:list_image.html.twig', 'label' => 'pictrogram.image'));
		$listMapper->addIdentifier('name', null, array('label' => 'matter.name'));
	}
}