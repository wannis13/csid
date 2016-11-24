<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace La2UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;

class RegistrationFormType extends \FOS\UserBundle\Form\Type\RegistrationFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
               
        $builder
        ->add('lastname', null, array('label' => 'Nom : ', 'translation_domain' => 'FOSUserBundle'))
        ->add('firstname', null, array('label' => 'Prénom : ', 'translation_domain' => 'FOSUserBundle'))
        ->add('address', null, array('label' => 'Adresse : ', 'translation_domain' => 'FOSUserBundle'))
        ->add('postal_code', null, array('label' => 'Code postal : ', 'translation_domain' => 'FOSUserBundle'))
        ->add('city', null, array('label' => 'Ville : ', 'translation_domain' => 'FOSUserBundle'));
    }
}
