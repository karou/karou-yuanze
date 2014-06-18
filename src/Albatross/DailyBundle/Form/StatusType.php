<?php

namespace Albatross\DailyBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class StatusType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('status')
            ->add('editable', null, array('required' => false))
            ->add('weight', null, array('required' => false))
            ->add('today', null, array('required' => false))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Albatross\DailyBundle\Entity\Status'
        ));
    }

    public function getName()
    {
        return 'albatross_dailybundle_statustype';
    }
}
