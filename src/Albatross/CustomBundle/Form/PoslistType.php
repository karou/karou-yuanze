<?php

namespace Albatross\CustomBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PoslistType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('store_id')
            ->add('location_name')
            ->add('number_of_visits')
            ->add('store_name')
            ->add('address')
            ->add('address_2')
            ->add('city')
            ->add('county')
            ->add('state_region')
            ->add('postal_code')
            ->add('country')
            ->add('phone')
            ->add('fax')
            ->add('location_hours')
            ->add('export_email')
            ->add('export_email_name')
            ->add('export_language')
            ->add('location_status')
            ->add('location_photo_url')
            ->add('latitude')
            ->add('longitude')
            ->add('date_geocode_acquired')
            ->add('geocode_source')
            ->add('additional_comments')
            ->add('summary_label')
            ->add('summary_content')
            ->add('summary_display')
            ->add('region')
            ->add('customwave')
            ->add('path', null, array(
                'required' => false
            ))
            ->add('file', 'file', array(
                'required' => false,
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Albatross\CustomBundle\Entity\Poslist'
        ));
    }

    public function getName()
    {
        return 'albatross_custombundle_poslisttype';
    }
}
