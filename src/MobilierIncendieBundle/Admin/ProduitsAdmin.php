<?php
namespace MobilierIncendieBundle\Admin;

use MobilierIncendieBundle\Form\ModeleType;
use MobilierIncendieBundle\Form\ReductionsType;
use MobilierIncendieBundle\Form\TarifClientSpecialType;
use MobilierIncendieBundle\Form\TarifsLivraisonType;
use MobilierIncendieBundle\Form\TarifsLivraisonParClientType;
use MobilierIncendieBundle\Form\OptionsType;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use MobilierIncendieBundle\Entity\Reductions;

class ProduitsAdmin extends Admin
{
    protected $translationDomain = 'csid';


    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->tab('admin.general', array('class' => 'col-md-12', 'collapsed' => true))
            ->with('admin.general', array('class' => 'col-md-6', 'collapsed' => true))
            ->add('name', 'text', array('label' => 'admin.name'))
            ->add('description', 'textarea', array('label' => 'admin.description', 'attr' => array('class' => 'ckeditor')))
            ->add('hauteur', 'number', array('label' => 'admin.hauteur'))
            ->add('largeur', 'number', array('label' => 'admin.largeur'))
            ->add('poids', 'number', array('label' => 'admin.poids'))
            ->add('reference', 'text', array('label' => 'admin.reference'))
            ->add('prix_achat', 'text', array('label' => 'admin.prix_achat'))
            ->add('prix', 'text', array('label' => 'admin.prix'))
            ->end();
        $formMapper->with('admin.matter_colors', array('class' => 'col-md-6'))
            ->add('coloris', 'sonata_type_model', array(
                'required' => false,
                'expanded' => true,
                'multiple' => true,

            ))
            ->end();
       /* $formMapper->with('admin.version', array('class' => 'col-md-6'))
            ->add('versions', 'sonata_type_model', array(
                'required' => false,
                'expanded' => true,
                'multiple' => true,

            ))
            ->end();*/
        $formMapper->with('admin.categories', array('class' => 'col-md-6'))
            ->add('categories', 'sonata_type_model', array(
                'required' => false,
                'expanded' => true,
                'multiple' => true,

            ))
            ->end();
        $formMapper->with('admin.images', array('class' => 'col-md-6'))
            ->add('images', 'sonata_type_model', array('required' => false, 'multiple' => true), array('link_parameters' => array('context' => 'images_produits')))
            ->end();
        $formMapper->with(' ', array('class' => 'col-md-6'))
            ->add('plaquette_pdf', 'sonata_type_model_list',
                array(
                    'label' => 'admin.plaquette',
                    'required' => true,
                    'by_reference' => false
                ),
                array(
                    'edit' => 'inline',
                    'inline' => 'table',
                    'link_parameters' => array(
                        'context' => 'plaquette_pdf',
                        'provider' => 'sonata.media.provider.file',
                    )
                )
            )
            ->end();
        $formMapper->with(' ', array('class' => 'col-md-6'))
            // ->add('plaquette_pdf','sonata_type_model', array('required' => false, 'multiple'=>false) ,array('link_parameters' => array('context' => 'plaquette_pdf')))

            ->add('code_ral_pdf', 'sonata_type_model_list',
                array(
                    'label' => 'admin.code_ral_pdf',
                    'required' => true,
                    'by_reference' => false
                ),
                array(
                    'edit' => 'inline',
                    'inline' => 'table',
                    'link_parameters' => array(
                        'context' => 'code_ral_pdf',
                        'provider' => 'sonata.media.provider.file',
                    )
                )
            )
            ->end();
        $formMapper->with(' ', array('class' => 'col-md-6'))
            // ->add('plaquette_pdf','sonata_type_model', array('required' => false, 'multiple'=>false) ,array('link_parameters' => array('context' => 'plaquette_pdf')))

            ->add('dossier_zip_image', 'sonata_type_model_list',
                array(
                    'label' => 'admin.dossier_zip_image',
                    'required' => true,
                    'by_reference' => false
                ),
                array(
                    'edit' => 'inline',
                    'inline' => 'table',
                    'link_parameters' => array(
                        'context' => 'dossier_zip_image',
                        'provider' => 'sonata.media.provider.file',
                    )
                )
            )
            ->end()
            ->end();
        $formMapper
            ->tab('admin.versions')
            ->with('admin.versions', array(
                'class' => 'col-md-6',
                'box_class' => 'box box-solid box-danger',
                // 'description' => 'Lorem ipsum',
            ))
            ->add('versions', 'collection', array(
                'type' => new ModeleType(),
                'required' => true,
                'allow_add' => true,
                'prototype' => true,
                'allow_delete' => true,
                'by_reference' => true,
                'label' => false,
                'options' => array('label' => 'Version', 'label_attr' => array('class' => 'answers')),
            ),
                array(
                    'edit' => 'inline',
                    'inline' => 'table',
                    'sortable' => 'id',
                )
            )


            ->end()
            ->end();

        $formMapper->tab('admin.tarifs_clints', array('class' => 'col-md-12'))
            ->with('Tarif dégressifs', array('class' => 'col-md-6'))
            ->add('tarifs_degressifs', 'collection', array(
                'type' => new ReductionsType(),

                'required' => true,
                'allow_add' => true,
                'prototype' => true,
                'allow_delete' => true,
                'by_reference' => true,
                'label' => false,
                'options' => array('label' => 'Tarif dégressifs', 'label_attr' => array('class' => 'answers')),


            ),
                array(
                    'edit' => 'inline',
                    'inline' => 'table',
                    'sortable' => 'id',
                )
            )
            ->end()
            ->with('Tarif spécial par client', array('class' => 'col-md-6'))
            ->add('tarifs_clints', 'collection', array(
                'type' => new TarifClientSpecialType(),

                'required' => true,
                'allow_add' => true,
                'prototype' => true,
                'allow_delete' => true,
                'by_reference' => true,
                'label' => false,
                'options' => array('label' => 'tarif spécial', 'label_attr' => array('class' => 'answers')),
            ),
                array(
                    'edit' => 'inline',
                    'inline' => 'table',
                    'sortable' => 'id',
                )
            )
            ->end()
            ->end();


        $formMapper
            ->tab('admin.tarifs_livraison')
            ->with('Tarifs par quantité', array('class' => 'col-md-6'))
            ->add('Tarifs_livraison', 'collection', array(
                'type' => new TarifsLivraisonType(),

                'required' => true,
                'allow_add' => true,
                'prototype' => true,
                'allow_delete' => true,
                'by_reference' => true,
                'label' => false,
                'options' => array('label' => 'tarif de livraison', 'label_attr' => array('class' => 'answers')),
            ),
                array(
                    'edit' => 'inline',
                    'inline' => 'table',
                    'sortable' => 'id',
                )
            )
            ->end()
            ->with('admin.tarifs_par_client', array(
                'class' => 'col-md-6',
                'box_class' => 'box box-solid box-danger',
               // 'description' => 'Lorem ipsum',
                // ...
            ))
            ->add('tarifs_livraison_par_client', 'collection', array(
                'type' => new TarifsLivraisonParClientType(),

                'required' => true,
                'allow_add' => true,
                'prototype' => true,
                'allow_delete' => true,
                'by_reference' => true,
                'label' => false,
                'options' => array('label' => 'Tarif de livraison par client', 'label_attr' => array('class' => 'answers')),
            ),
                array(
                    'edit' => 'inline',
                    'inline' => 'table',
                    'sortable' => 'id',
                )
            )


            ->end()
            ->end();
        $formMapper
            ->tab('admin.options')
                ->with('admin.options', array(
                    'class' => 'col-md-6',
                    'box_class' => 'box box-solid box-danger',
                    // 'description' => 'Lorem ipsum',
                   ))
                ->add('options', 'collection', array(
                    'type' => new OptionsType(),
                    'required' => true,
                    'allow_add' => true,
                    'prototype' => true,
                    'allow_delete' => true,
                    'by_reference' => true,
                    'label' => false,
                    'options' => array('label' => 'Option', 'label_attr' => array('class' => 'answers')),
                ),
                    array(
                        'edit' => 'inline',
                        'inline' => 'table',
                        'sortable' => 'id',
                    )
                )


                ->end()
        ->end();


    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('name');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('id', null, array('label' => 'id'));
        $listMapper->addIdentifier('name', null, array('label' => 'admin.name'))
            ->add('description', 'textarea', array('label' => 'admin.description'))
            ->add('hauteur', null, array('label' => 'admin.hauteur'))
            ->add('largeur', null, array('label' => 'admin.largeur'))
            ->add('poids', null, array('label' => 'admin.poids'))
            ->add('reference', null, array('label' => 'admin.reference'))
            ->add('prix', null, array('label' => 'admin.prix'));
    }

    public function prePersist($object)
    {
        $this->preUpdate($object);
    }

    public function preUpdate($object)
    {
        $object->setTarifsDegressifs($object->getTarifsDegressifs());
        $object->setTarifsClints($object->getTarifsClints());
        $object->setTarifsLivraison($object->getTarifsLivraison());
        $object->setTarifsLivraisonParClient($object->getTarifsLivraisonParClient());
        $object->setOptions($object->getOptions());
        $object->setVersions($object->getVersions());
    }
}