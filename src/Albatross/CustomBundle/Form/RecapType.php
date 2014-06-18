<?php

namespace Albatross\CustomBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RecapType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('countryType')
            ->add('submittime')
            ->add('user')
            ->add('name', null,array(
                'required' => false
            ))
            ->add('actual', new SurveyNumberType())
            ->add('planned', new SurveyNumberType())
            ->add('infomations', new InfomationType())
            ->add('customwave',null,array(
                'required' => false
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Albatross\CustomBundle\Entity\Recap',
            'csrf_protection' => false
        ));
    }

    public function getName()
    {
        return 'albatross_custombundle_recaptype';
    }
}
