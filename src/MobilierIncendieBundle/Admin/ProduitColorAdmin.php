<?php
namespace MobilierIncendieBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use MobilierIncendieBundle\Form\ReductionsType;

class ProduitColorAdmin extends Admin
{
    protected $translationDomain = 'csid';
  /*  protected $baseRouteName = 'version';
    protected $baseRoutePattern = 'version';
    protected $classnameLabel = 'version';*/

    protected function configureFormFields(FormMapper $formMapper)
    {
        $image = $this->getSubject();

        // Original
        $fileFieldOptions = array('required' =>false, 'label' => 'admin.image_color');
        if ($image && ($webPath = $image->getWebPath())) {
            $container = $this->getConfigurationPool()->getContainer();
            $fullPath = $container->get('request')->getBasePath().'/'.$webPath;
            $fileFieldOptions['help'] = '<img src="'.$fullPath.'" class="admin-preview" />';
            $fileFieldOptions['help'] = '<img src="'.$container->get('liip_imagine.cache.manager')->getBrowserPath($fullPath, 'heighten_120').'" />';
            if ($image && ($filename = $image->getWebPath())) {
                if($filename != "") {
                    $fullPath = $container->get('request')->getBasePath().'/'.$filename;
                    $preview = '<div class="imgFilePreview"><img WIDTH="100" src="'.$fullPath.'" /></div>';
                }
            }
        } else {
            $preview = '<div class="imgFilePreview"></div>';
        }
        $fileFieldOptions['help'] = $preview;
        
        
        
        
        $formMapper
            ->with('admin.general', array('class' => 'col-md-6', 'collapsed' => true))
            ->add('produits',null, array('label' => 'admin.produits' ,'required'=>true ))
            ->add('name', 'text', array('label' => 'admin.label'))

            ->add('code_color', 'sonata_type_color_selector', array('label' => 'admin.color'))
            ->add('prix')
             ->add('quanitite_min' ,null ,array('label'=>'Quantite minimum ' ,'help'=>"Remplir ce champ lors de la création du coloris RAL personnalisé pour indiquer la quantité minimum pour acheter un produit avec option coloris RAL"))

            ->add('tarifs_degressifs', 'collection', array(
                'type' => new ReductionsType(),
                'required' => true,
                'allow_add' => true,
                'prototype' => true,
                'allow_delete' => true,
                'by_reference' => true,
                'label' => 'Ajouter tarif dégressif pour cette couleur',
                'options' => array('label' => 'Tarif dégressif', 'label_attr' => array('class' => 'answers')),
            ),
                array(
                    'edit' => 'inline',
                    'inline' => 'table',
                    'sortable' => 'id',
                )
            )

            ->add('active','checkbox',array('label'=>'admin.activate'))
            //->add('file', 'file', $fileFieldOptions)
            ->end();
    }


    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('name');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('id', null, array('label' => 'id'));
       // $listMapper->add('image', 'string', array('template' => 'CSIDBundle:MatterColorAdmin:list_image.html.twig', 'label' => 'pictrogram.image'));
        $listMapper->add('produits.name', null, array('label' => 'admin.produit'));
        $listMapper->addIdentifier('name', null, array('label' => 'admin.label'));
        $listMapper->add('prix', null, array('label' => 'Prix en €'));

        $listMapper->add('code_color', null ,array('label' => 'admin.color'));
        $listMapper->add('active',null,array('label'=>'admin.activate' ,  'editable' => true));
        $listMapper->add('_action', 'actions', array(
            'actions' => array(
                'delete' => array(),
                'edit' => array(),
            )
        ))  ;
    }
    /**
     *
     * {@inheritDoc}
     * @see \Sonata\AdminBundle\Admin\Admin::postUpdate($object)
     */
    public function postUpdate($object)
    {
        //$object->upload();
        $object->setTarifsDegressifs($object->getTarifsDegressifs());

    }

    public function prePersist($object)
    {
        //$object->upload();
        $object->setTarifsDegressifs($object->getTarifsDegressifs());

    }


    public function preUpdate($object)
    {
        $object->setTarifsDegressifs($object->getTarifsDegressifs());

    }
}