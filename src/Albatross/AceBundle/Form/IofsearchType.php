<?php

namespace Albatross\AceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class iofsearchType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $status = $options['data']['attachments']['status'];
        $builder
                ->add('label', 'text', array(
                    'required' => false
                ))
                ->add('bu', 'entity', array(
//                    'attr' => array('class' => 'chz'),
                    'expanded' => false,
                    'class' => 'AlbatrossAceBundle:Bu',
                    'property' => 'name',
                    'multiple' => false,
                    'required' => false
                ))
                ->add('project', 'text', array(
                    'required' => false
                ))
                ->add('user', 'entity', array(
//                    'attr' => array('class' => 'chz'),
                    'expanded' => false,
                    'class' => 'AlbatrossUserBundle:User',
                    'property' => 'username',
                    'multiple' => false,
                    'required' => false
                ))
                ->add('submit_from', 'text', array(
                    'required' => false
                ))
                ->add('submit_to', 'text', array(
                    'required' => false
                ))
                ->add('status', 'choice', array(
                    'choices' => $status,
                    'required' => true,
                ))
                ->add('number', 'text', array(
                    'required' => false
                ))
        ;
    }

    public function getName() {
        return 'albatross_acebundle_iofsearchtype';
    }

}
