<?php

namespace Albatross\CustomBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SurveyNumberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pos')
            ->add('surveys')
            ->add('misfire')
            ->add('invalid')
            ->add('scenarios', 'collection', array(
                    'type' => new ScenariosType(),
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                    'required' => false
                ))
            ->add('type')
            ->add('recap')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Albatross\CustomBundle\Entity\SurveyNumber'
        ));
    }

    public function getName()
    {
        return 'albatross_custombundle_surveynumbertype';
    }
}
