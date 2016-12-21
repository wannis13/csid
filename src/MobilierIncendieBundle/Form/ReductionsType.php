<?php

namespace MobilierIncendieBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ReductionsType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('quantite_min')
            ->add('quantite_max')
            ->add('prix_unitaire')
            ->add('reduction')
            //->add('produits')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MobilierIncendieBundle\Entity\Reductions'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'mobilierincendiebundle_reductions';
    }
  
}
