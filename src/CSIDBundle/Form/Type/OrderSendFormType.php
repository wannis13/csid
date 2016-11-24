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

class OrderSendFormType extends AbstractType
{
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Symfony\Component\Form\AbstractType::buildForm()
	 */
	public function buildForm (FormBuilderInterface $builder, array $options)
	{
		$builder->add('recipients', 'collection', 
			array(
				'type' => 'email',
				'prototype' => true,
				'allow_delete' => true,
				'allow_add' => true,
				'delete_empty' => true,
				'required' => true,
				'options' => array(
					'required' => true,
					'attr' => array(
						'class' => 'email-box'
					),
					'label' => 'E-mail',
				)
			));
		$builder->add('message', 'textarea');
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Symfony\Component\Form\FormTypeInterface::getName()
	 */
	public function getName ()
	{
		return 'csid_order_send_form';
	}
}
