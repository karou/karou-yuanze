<?php

namespace Albatross\CustomBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CustomprojectType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $type = $options['data']['type'];
        $scope = $options['data']['scope'];
        $builder
                ->add('customclient', 'entity', array(
                    'expanded' => false,
                    'class' => 'AlbatrossCustomBundle:Customclient',
                    'property' => 'name',
                    'multiple' => false,
                ))
                ->add('type', 'choice', array(
                    'choices' => $type
                ))
                ->add('scope', 'choice', array(
                    'choices' => $scope
                ))
        ;
    }

    public function getName() {
        return 'albatross_custombundle_customprojecttype';
    }

}
