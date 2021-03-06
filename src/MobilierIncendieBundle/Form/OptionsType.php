<?php

namespace MobilierIncendieBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OptionsType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('prix')
            //->add('produits')
          /*  ->add('tarifs_degressifs' ,'collection',
                array('type'=> new ReductionsType(),
                    'required' => true,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'label' => false,
                    'options' => array('label' => 'Option', 'label_attr' => array('class' => 'answers'))
                ))*/
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MobilierIncendieBundle\Entity\Options'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'mobilierincendiebundle_options';
    }
}
