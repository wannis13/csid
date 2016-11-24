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
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class BasketConfirmFormType extends AbstractType
{
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Symfony\Component\Form\AbstractType::buildForm()
	 */
	public function buildForm (FormBuilderInterface $builder, array $options)
	{
		$builder
		->add('hide_to_csid', 'checkbox', array('label' => 'Hide customer informations to CSID', 'translation_domain' => 'csid', 'required' => false))
		->add('customer_id', 'hidden', array('required' => false))
		->add('email', 'email', array('label' => 'form.email', 'translation_domain' => 'csid'))
		->add('lastname', null, array('label' => 'form.lastname', 'translation_domain' => 'csid'))
		->add('firstname', null, array('label' => 'form.firstname', 'translation_domain' => 'csid'))
		->add('adress', null, array('label' => 'form.address', 'translation_domain' => 'csid'))
		->add('postal_code', null, array('label' => 'form.postal_code', 'translation_domain' => 'csid'))
		->add('city', null, array('label' => 'form.city', 'translation_domain' => 'csid'))
		->add('company', null, array('label' => 'form.company', 'translation_domain' => 'csid', 'required' => false));
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Symfony\Component\Form\FormTypeInterface::getName()
	 */
	public function getName ()
	{
		return 'csid_basket_confirm_form';
	}
}
