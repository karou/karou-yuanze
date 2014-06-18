<?php

namespace Albatross\OperationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OperationQuestionnaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fwsdate')
            ->add('fwedate')
            ->add('reportdate')
            ->add('survey_num')
            ->add('assigned_num')
            ->add('fw_num')
            ->add('editing_num')
            ->add('first_visit_date')
            ->add('last_visit_date')
            ->add('info_type')
            ->add('modified_date')
            ->add('campaign')
            ->add('questionnaire')
            ->add('bu')
            ->add('country')
            ->add('customclient')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Albatross\OperationBundle\Entity\OperationQuestionnaire'
        ));
    }

    public function getName()
    {
        return 'albatross_operationbundle_operationquestionnairetype';
    }
}
