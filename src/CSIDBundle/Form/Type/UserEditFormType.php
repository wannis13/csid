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


class UserEditFormType extends AbstractType
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
            ->add('lastname', null, array('label' => 'form.lastname', 'translation_domain' => 'csid'))
            ->add('firstname', null, array('label' => 'form.firstname', 'translation_domain' => 'csid'))
            ->add('address', null, array('label' => 'form.address', 'translation_domain' => 'csid'))
            ->add('postal_code', null, array('label' => 'form.postal_code', 'translation_domain' => 'csid'))
            ->add('city', null, array('label' => 'form.city', 'translation_domain' => 'csid'))
            ->add('siren', null, array('label' => 'form.siren', 'translation_domain' => 'csid'))
            ->add('company', null, array('label' => 'form.company', 'translation_domain' => 'csid'))
            ->add('margin_percent', null, array('label' => 'form.margin_percent', 'translation_domain' => 'csid'))
            ->add('tva', null, array('label' => 'form.tva', 'translation_domain' => 'csid'))
        	->add('command_no', null, array('label' => 'form.command_no', 'translation_domain' => 'csid'))
       	 	->add('logo', 'sonata_media_type', array(
        		'provider' => 'sonata.media.provider.image',
        		'context'  => 'logo',
       	 		'required' => false
        	));;
    }
	
    /**
     * 
     * {@inheritDoc}
     * @see \Symfony\Component\Form\FormTypeInterface::getName()
     */
    public function getName ()
    {
        return 'csid_user_edit_form';
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
