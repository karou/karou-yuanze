<?php

namespace Albatross\CustomBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CustomfieldType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $language = $options['language']['language'];
        $BrandMaterial = $options['language']['brandmaterial'];
        
        $builder
            ->add('material_name', 'choice', array(
                'choices' => $BrandMaterial,
                'required' => false
            ))
            ->add('path', null, array(
                'required' => false
            ))
            ->add('path_2', null, array(
                'required' => false
            ))
            ->add('path_3', null, array(
                'required' => false
            ))
            ->add('path_4', null, array(
                'required' => false
            ))
            ->add('file', 'file', array(
                'required' => false,
            ))
            ->add('file_2', 'file', array(
                'required' => false
            ))
            ->add('file_3', 'file', array(
                'required' => false
            ))
            ->add('file_4', 'file', array(
                'required' => false
            ))
            ->add('fieldtype')
            ->add('report_type', 'choice', array(
                'choices' => array(
                    0 => 'Flash SPE',
                    1 => 'SPE',
                    2 => 'SPA',
                ),
                'required' => false
            ))
            ->add('report_executive', null, array(
                'required' => false
            ))
            ->add('report_zone')
            ->add('main_brief', null, array(
                'required' => false
            ))
            ->add('brief_translation', 'choice', array(
                'choices' => $language,
                'required' => false
            ))
            ->add('submittime')
            ->add('customwave')
            ->add('user')
            ->add('country', 'entity', array(
                'required' => false,
                'class' => 'AlbatrossAceBundle:Country',
                'property' => 'name',
                'multiple' => true
            ))
            //mm part start
            ->add('mm_brand')
            ->add('mm_date')
            ->add('mm_address')
            ->add('mm_nextstep')
            ->add('mm_agenda_of_the_meeting')
            ->add('mm_clients_feedback')
            ->add('mm_comments')
            ->add('mm_purpose')
            ->add('attendees','collection', array(
                    'type' => new AttendeesType(),
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                    'required' => false
                ))
            ->add('client_confirmation')
            ->add('pm_confirmation')
            ->add('upload_waiting_clonage')
            ->add('proofreading')
            ->add('client_signature')
            ->add('pm_signature')
            ->add('upload_waiting_clonage_signature')
            ->add('proofreading_signature')
            ->add('question_status')
            ->add('question_file1_label')
            ->add('question_file2_label')
            ->add('question_file3_label')
            ->add('question_file4_label')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Albatross\CustomBundle\Entity\Customfield',
            'language' => array(),
            'csrf_protection' => false
        ));
    }

    public function getName()
    {
        return 'albatross_custombundle_customfieldtype';
    }
}
