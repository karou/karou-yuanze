<?php

namespace Albatross\CustomBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CustomclientType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('name')
                ->add('file')
                ->add('clientgroup', 'entity', array(
                        'expanded' => false,
                        'class' => 'AlbatrossCustomBundle:Clientgroup',
                        'property' => 'name',
                        'multiple' => false,
                        'required' => false
                ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Albatross\CustomBundle\Entity\Customclient'
        ));
    }

    public function getName() {
        return 'albatross_custombundle_customclienttype';
    }

}
