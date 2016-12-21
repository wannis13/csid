<?php
namespace MobilierIncendieBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class CategorieAdmin extends Admin
{
    protected $translationDomain = 'csid';

    protected function configureFormFields(FormMapper $formMapper)
    {


        $formMapper
            ->with('admin.general', array('class' => 'col-md-6', 'collapsed' => true))
            ->add('name', 'text', array('label' => 'admin.label'))
            ->end();
    }


    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('name');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('id', null, array('label' => 'id'));
        $listMapper->addIdentifier('name', null, array('label' => 'admin.label'));

    }
}