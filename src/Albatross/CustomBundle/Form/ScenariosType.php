<?php

namespace Albatross\CustomBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ScenariosType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      
        $builder
            ->add('name', 'text')
            ->add('value', 'text')
        ;
    }

    public function getName()
    {
        return 'albatross_custombundle_scenarios';
    }
}
