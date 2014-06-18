<?php

namespace Albatross\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PasswordType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('password', 'repeated', array(
                    'first_name' => 'New_password',
                    'second_name' => 'Confirm_new_password',
                    'type' => 'password'
                ))
                ->add('aol_password', 'repeated', array(
                    'first_name' => 'New_password',
                    'second_name' => 'Confirm_new_password',
                    'type' => 'password'
                ))
                ->add('ace_password', 'repeated', array(
                    'first_name' => 'New_password',
                    'second_name' => 'Confirm_new_password',
                    'type' => 'password'
                ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Albatross\UserBundle\Entity\User',
        ));
    }

    public function getName() {
        return 'albatross_userbundle_passwordtype';
    }

}
