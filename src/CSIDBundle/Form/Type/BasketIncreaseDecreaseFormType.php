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

class BasketIncreaseDecreaseFormType extends AbstractType
{
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Symfony\Component\Form\AbstractType::buildForm()
	 */
	public function buildForm (FormBuilderInterface $builder, array $options)
	{
		$builder
		
		->add('label', null, array('label' => 'form.label', 'translation_domain' => 'csid'))
		->add('amount', 'number', array('label' => 'form.amount', 'translation_domain' => 'csid'))
		->add('vat', 'number', array('label' => 'form.vat', 'translation_domain' => 'csid'))
		->add('type', 'choice', array(
		    'choices' => array(1 => 'Augmentation', 0 => 'Remise'),
		))
		->add('save', 'submit', array('label' => 'form.add', 'translation_domain' => 'csid'));
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Symfony\Component\Form\FormTypeInterface::getName()
	 */
	public function getName ()
	{
		return 'csid_basket_increase_decrease_form';
	}
}
