<?php

namespace MobilierIncendieBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class QuestionsType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom' ,null , array('required'=>true ,'label'=>'Nom'))
            ->add('prenom' ,null , array('required'=>true ,'label'=>'Prénom'))
            ->add('email' ,null , array('required'=>true ,'label'=>'Email'))
            ->add('message' ,null , array('required'=>true ,'label'=>'Message'))
           
            //->add('produits')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MobilierIncendieBundle\Entity\Questions'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'mobilierincendiebundle_questions';
    }
}
