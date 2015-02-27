<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Alumbrados\CmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Description of UserType
 *
 * @author Martijn
 */
class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('roles', 'choice', array(
                'choices' => array(
                    'ROLE_USER' => 'ROLE_USER',
                    'ROLE_ADMIN' => 'ROLE_ADMIN'
                ),
                'multiple' => true,
                'expanded' => true,
                'label' => 'roles'
            ))
            ->add('enabled', 'checkbox', array(
                'label' => 'enabled',
                'attr' => array('align_with_widget' => true),
                'required' => false
            ))                 
            ->add('first_name', 'text', array(
                'label' => 'first_name'
            ))
            ->add('initials', 'text', array(
                'label' => 'initials'
            ))
            ->add('last_name', 'text', array(
                'label' => 'last_name'
            ))
            ->add('street', 'text', array(
                'label' => 'street'
            ))
            ->add('street_number', 'integer', array(
                'label' => 'street_number'
            ))
            ->add('street_number_addition', 'text', array(
                'label' => 'addition',
                'required' => false
            ))
            ->add('zipcode', 'text', array(
                'label' => 'zipcode'
            ))
            ->add('city', 'text', array(
                'label' => 'city'
            ))
            ->add('country', 'country', array(
                'preferred_choices' => array('NL'),
                'label' => 'country'
            ));
    }

    public function getParent()
    {
        return 'fos_user_registration';
    }

    public function getName()
    {
        return 'cart_customer';
    }
}
