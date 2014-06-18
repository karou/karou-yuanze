<?php

namespace Albatross\AceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ForecastType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('bu')
            ->add('project')
            ->add('task')
            ->add('fwstartdate')
            ->add('fwenddate')
            ->add('reportduedate')
            ->add('scope')
            ->add('pm', 'entity', array(
                    'expanded' => false,
                    'class' => 'AlbatrossUserBundle:User',
                    'property' => 'username',
                    'multiple' => false,
                    'required' => false
                ))
            ->add('edittime')
            ->add('task')
            ->add('reportduetext')
            ->add('reporttype')
        ;
    }

    public function getName()
    {
        return 'albatross_acebundle_forecasttype';
    }
}
