<?php

namespace Albatross\CustomBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class InfomationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('new_pos_in_Wave')
            ->add('delete_pos_in_Wave')
            ->add('invalids_to_be_invoiced')
            ->add('misfires_to_be_invoiced')
            ->add('purchases_made')
            ->add('recap')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Albatross\CustomBundle\Entity\Infomation'
        ));
    }

    public function getName()
    {
        return 'albatross_custombundle_infomationtype';
    }
}
