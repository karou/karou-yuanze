<?php

namespace Albatross\AceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class AttachinfoType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('bu', 'entity', array(
                    'attr' => array('class' => 'chz'),
                    'expanded' => false,
                    'class' => 'AlbatrossAceBundle:Bu',
                    'property' => 'name',
                    'multiple' => false,
                    'required' => false
                ))
                ->add('project', 'entity', array(
                    'attr' => array('class' => 'chz_project'),
                    'expanded' => false,
                    'class' => 'AlbatrossAceBundle:Project',
                    'property' => 'name',
                    'multiple' => false,
                    'required' => false
                ))
                ->add('scope')
                ->add('fwstartdate', 'text')
                ->add('fwenddate', 'text')
                ->add('reportduedate', 'text', array(
                    'required' => false
                ))
                ->add('comment')
        ;
    }
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Albatross\AceBundle\Entity\Attachinfo'
        ));
    }
    public function getName() {
        return 'albatross_acebundle_attachinfotype';
    }
    
}
