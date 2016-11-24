<?php
/**
 * This file is part of the CSID project.
 *
 * (c) Barbara
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CSIDBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class AskAccountFormType extends \La2UserBundle\Form\Type\RegistrationFormType
{
	/**
	 * 
	 * {@inheritDoc}
	 * @see \La2UserBundle\Form\Type\RegistrationFormType::buildForm()
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        
        $builder
        	->add('company', null, array('label' => 'Societé : ', 'translation_domain' => 'FOSUserBundle'));

    }
    
    /**
     * 
     * {@inheritDoc}
     * @see \Symfony\Component\Form\AbstractType::getParent()
     */
    public function getParent()
    {
    	return 'fos_user_registration';
    }
    
    /**
     * 
     * {@inheritDoc}
     * @see \FOS\UserBundle\Form\Type\RegistrationFormType::getName()
     */
    public function getName()
    {
    	return 'csid_user_askactivation_form';
    }
    
    /**
     * 
     * {@inheritDoc}
     * @see \FOS\UserBundle\Form\Type\RegistrationFormType::setDefaultOptions()
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
    	$resolver->setDefaults(array(
    		'data_class' => 'La2UserBundle\Entity\User',
    		'intention'  => 'registration',
    	));
    }
    
}
