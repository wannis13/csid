<?php

namespace MobilierIncendieBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OrderMobilierIncendieType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('created')
            ->add('signatureDate')
            ->add('number')
            ->add('status')
            ->add('hideToCSID')
            ->add('amount')
            ->add('amountVAT')
            ->add('amountWithMargin')
            ->add('amountVATWithMargin')
            ->add('vat')
            ->add('createdBy')
            ->add('retailer')
            ->add('customer')
            ->add('signature')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MobilierIncendieBundle\Entity\OrderMobilierIncendie'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'mobilierincendiebundle_ordermobilierincendie';
    }
}
