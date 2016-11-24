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
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CustomerFormType extends AbstractType
{
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Symfony\Component\Form\AbstractType::buildForm()
	 */	
	public function buildForm (FormBuilderInterface $builder, array $options)
	{
		
		$builder
		->add('username', null, array('label' => 'form.username', 'translation_domain' => 'csid'))
		->add('email', 'email', array('label' => 'form.email', 'translation_domain' => 'csid'))
		->add('plainPassword', 'repeated', array(
			'type' => 'password',
			'options' => array('translation_domain' => 'FOSUserBundle'),
			'first_options' => array('label' => 'form.password'),
			'second_options' => array('label' => 'form.password_confirmation'),
			'invalid_message' => 'fos_user.password.mismatch',
			'required' => false
		))
		->add('lastname', null, array('label' => 'form.lastname', 'translation_domain' => 'csid'))
		->add('firstname', null, array('label' => 'form.firstname', 'translation_domain' => 'csid'))
		->add('address', null, array('label' => 'form.address', 'translation_domain' => 'csid'))
		->add('postal_code', null, array('label' => 'form.postal_code', 'translation_domain' => 'csid'))
		->add('city', null, array('label' => 'form.city', 'translation_domain' => 'csid'))
		->add('company', null, array('label' => 'form.company', 'translation_domain' => 'csid'))
		->add('siren', null, array('label' => 'form.siren', 'translation_domain' => 'csid'));
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Symfony\Component\Form\FormTypeInterface::getName()
	 */
	public function getName ()
	{
		return 'csid_customer_edit_form';
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Symfony\Component\Form\AbstractType::setDefaultOptions()
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'La2UserBundle\Entity\User'
		));
	}
    
}
