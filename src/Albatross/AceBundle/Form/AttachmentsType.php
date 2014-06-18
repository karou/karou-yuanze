<?php

namespace Albatross\AceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AttachmentsType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $status = $options['data']['attachments']['status'];
        $type = $options['data']['attachments']['type'];
        if (isset($type[3]) && $type[3] == 'other') {
            $builder
                    ->add('file', 'file', array(
                        'required' => false
                    ))
                    ->add('label', null, array(
                        'required' => false
                    ))
                    ->add('filesection', 'entity', array(
                        'expanded' => false,
                        'class' => 'AlbatrossAceBundle:FileSection',
                        'property' => 'name',
                        'multiple' => false,
                        'required' => false
                    ))
                ;
        }
        else
            $builder
                    ->add('status', 'choice', array(
                        'choices' => $status,
                        'required' => true,
                    ))
                    ->add('type', 'choice', array(
                        'choices' => $type,
                        'required' => true,
                    ))
                    ->add('path')
                    ->add('file', 'file', array(
                        'required' => false
                    ))
                    ->add('text', 'text', array(
                        'required' => false
                    ))
                    ->add('label', null, array(
                        'required' => false
                    ))
                    ->add('message', 'textarea', array(
                        'required' => false
                    ))
                    ->add('attachinfo', 'collection', array(
                        'type' => new AttachinfoType(),
                        'allow_add' => true,
                        'allow_delete' => true,
                        'by_reference' => false
                    ))
                    ->add('project_id', 'text', array(
                        'required' => false
                    ))
                    ->add('project_name', 'text', array(
                        'required' => false
                    ))
                    ->add('user', 'entity', array(
//                    'attr' => array('class' => 'chz'),
                        'expanded' => false,
                        'class' => 'AlbatrossUserBundle:User',
                        'property' => 'username',
                        'multiple' => false,
                    ))
                    ->add('parents', 'text', array(
                        'required' => false
                    ))
                    ->add('wave', 'entity', array(
                        'expanded' => false,
//                    'attr' => array('class' => 'chz_wave'),
                        'class' => 'AlbatrossCustomBundle:Customwave',
                        'property' => 'name',
                        'multiple' => false,
                        'required' => false
                    ))
                    ->add('customclient', 'entity', array(
                        'expanded' => false,
//                    'attr' => array('class' => 'chz_wave'),
                        'class' => 'AlbatrossCustomBundle:Customclient',
                        'property' => 'name',
                        'multiple' => false,
                        'required' => false
                    ))
                    ->add('customproject', 'entity', array(
                        'expanded' => false,
//                    'attr' => array('class' => 'chz_wave'),
                        'class' => 'AlbatrossCustomBundle:Customproject',
                        'property' => 'name',
                        'multiple' => false,
                        'required' => false
                    ))
                    ->add('attachtype', 'text', array(
                        'required' => false
                    ))
            ;
    }

    public function getName() {
        return 'albatross_acebundle_attachmenttype';
    }

}
