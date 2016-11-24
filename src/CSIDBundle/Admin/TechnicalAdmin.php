<?php
namespace CSIDBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class TechnicalAdmin extends Admin
{
	protected $translationDomain = 'csid';
	
	protected function configureFormFields(FormMapper $formMapper)
	{
		$image = $this->getSubject();
		
		$fileFieldOptions = array('required' => true, 'label' => 'admin.image');
		if ($image && ($webPath = $image->getWebPath())) {
			$container = $this->getConfigurationPool()->getContainer();
			$fullPath = $container->get('request')->getBasePath().'/'.$webPath;
			$fileFieldOptions['help'] = '<img src="'.$fullPath.'" class="admin-preview" />';
			$fileFieldOptions['help'] = '<img src="'.$container->get('liip_imagine.cache.manager')->getBrowserPath($fullPath, 'heighten_120').'" />';
		}
		
		$fileFieldOptions = array('required' => true, 'label' => 'admin.image', 'attr' => array('class' => 'fileInputPreview'));
		
		if($image->getId() != null) {
			$fileFieldOptions['required'] = false;
		}
		
		if ($image && ($filename = $image->getWebPath())) {
			$container = $this->getConfigurationPool()->getContainer();
			if($filename != "") {
				$fullPath = $container->get('request')->getBasePath().'/'.$filename;
				$preview = '<div class="imgFilePreview"><img src="'.$fullPath.'" /></div>';
			}
		} else {
			$preview = '<div class="imgFilePreview"></div>';
		}
		$fileFieldOptions['help'] = $preview;
		
		$formMapper
		->with('admin.general', array('class' => 'col-md-6', 'collapsed' => true))
			->add('name', 'text', array('label' => 'admin.label'))
			->add('blackplate', null, array('label' => 'Contre-plaque?'))
			->add('canHaveHole', null, array('label' => 'Perçages?'))
			->add('type', 'choice', array('label' => 'admin.type', 'choices' => array(
				0 => 'Impression numérique de plaques & autocollants',
				1 => 'Gravure',
				2 => 'Relief',
				3 => 'Découpe traversante',
				4 => 'Détourage - découpe contour',
				5 => 'Favoris'
 			)))
			->add('description', 'textarea', array('label' => 'admin.description', 'attr' => array('class' => 'ckeditor')))
			->add('file', 'file', $fileFieldOptions)
		->end()
		->with('admin.category', array('class' => 'col-md-6'))
			->add('matters', 'sonata_type_model', array(
					'required' => false,
					'expanded' => true,
					'multiple' => true,
					'label' => 'admin.matters'
			))
			->end();
	}

	protected function configureDatagridFilters(DatagridMapper $datagridMapper)
	{
		$datagridMapper->add('name');
	}

	protected function configureListFields(ListMapper $listMapper)
	{
		$listMapper->addIdentifier('name', null, array('label' => 'admin.name'));
	}
}