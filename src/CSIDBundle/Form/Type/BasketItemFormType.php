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
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\FormInterface;

class BasketItemFormType extends AbstractType
{
	/**
	 * 
	 * @var string
	 */
	protected $class;
	
	/**
	 * 
	 * @param string $class
	 */
	public function __construct($class)
	{
		$this->class = $class;
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Symfony\Component\Form\AbstractType::buildForm()
	 */
	public function buildForm (FormBuilderInterface $builder, array $options)
	{
		$builder->add('technical', 'entity', 
			array(
				'class' => 'CSIDBundle:Technical',
				'choice_label' => 'name',
				'constraints' => array(
					new NotBlank()
				)
			));
		
		$builder->add('plateMatter', 'entity', 
			array(
				'class' => 'CSIDBundle:Matter',
				'choice_label' => 'name',
				'constraints' => array(
					new NotBlank()
				)
			));
		
		$builder->add('plateMatterColor', 'entity', 
			array(
				'class' => 'CSIDBundle:MatterColor',
				'choice_label' => 'name',
				'constraints' => array(
					new NotBlank()
				)
			));
		
		$builder->add('backplateMatter', 'entity',
			array(
				'class' => 'CSIDBundle:Matter',
				'choice_label' => 'name'
			));
		
		$builder->add('backplateMatterColor', 'entity',
			array(
				'class' => 'CSIDBundle:MatterColor',
				'choice_label' => 'name'
			));
		
		$builder->add('fixing', 'entity', 
			array(
				'class' => 'CSIDBundle:Fixing',
				'choice_label' => 'name',
				'constraints' => array(
					new NotBlank()
				)
			));
		
		$builder->add('plateHeight', 'number',
			array(
				'constraints' => array(
					new NotBlank()
				)
			));
		
		$builder->add('roundedCorner', 'number');
		
		$builder->add('standardBearer', 'choice',
			array(
				'choices' => array(
					'' => '',
					'left' => 'left',
					'right' => 'right',
					'top' => 'top',
					'bottom' => 'bottom'
				)
			));
		

		$builder->add('plateWidth', 'number',
			array(
				'constraints' => array(
					new NotBlank()
				)
			));
		
		$builder->add('printHeight', 'number',
			array(
				'constraints' => array(
					new NotBlank()
				)
			));
		
		$builder->add('printWidth', 'number',
			array(
				'constraints' => array(
					new NotBlank()
				)
			));
		
		$builder->add('nbHoles', 'number',
			array(
				'constraints' => array(
					new NotBlank()
				)
			));
		
		$builder->add('holesDiameter', 'number',
			array(
				'constraints' => array(
					new NotBlank()
				)
			));
		
		$builder->add('png', 'hidden',
			array(
			));
		
		$builder->add('json', 'hidden',
			array(
			));
		
		$builder->add('svg', 'hidden',
			array(
			));
		
	}

	/**
	 * 
	 * {@inheritDoc}
	 * @see \Symfony\Component\Form\AbstractType::setDefaultOptions()
	 */
	public function setDefaultOptions (OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => $this->class
		));
	}

	/**
	 * 
	 * {@inheritDoc}
	 * @see \Symfony\Component\Form\FormTypeInterface::getName()
	 */
	public function getName ()
	{
		return 'csid_basket_item_form';
	}
}
