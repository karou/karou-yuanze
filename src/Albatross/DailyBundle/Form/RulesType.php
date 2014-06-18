<?php

namespace Albatross\DailyBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class RulesType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('status', 'entity', array(
                    'attr' => array('class' => 'chz'),
                    'expanded' => false,
                    'multiple' => true,
                    'class' => 'AlbatrossDailyBundle:Status',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('s')
                                ->where("s.editable = 0");
                    }
                ))
                ->add('clients', 'entity', array(
                    'required' => false,
                    'attr' => array('class' => 'chz'),
                    'expanded' => false,
                    'multiple' => true,
                    'class' => 'AlbatrossDailyBundle:Client',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('c')
                                ->orderBy("c.clientName", "ASC");
                    }
                ))
                ->add('bu', null, array('attr' => array('class' => 'chz_single'), 'required' => false))
                ->add('countries', 'entity', array(
                    'required' => false,
                    'attr' => array('class' => 'chz'),
                    'expanded' => false,
                    'multiple' => true,
                    'class' => 'AlbatrossAceBundle:Country',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('c')
                                ->orderBy("c.name", "ASC");
                    }
                ))
//                ->add('surveys', 'entity', array(
//                    'required' => false,
//                    'attr' => array('class' => 'chz'),
//                    'expanded' => false,
//                    'multiple' => true,
//                    'class' => 'AlbatrossDailyBundle:Survey',
//                    'query_builder' => function (EntityRepository $er) {
//                        return $er->createQueryBuilder('s')
//                                ->orderBy("s.surveyName", "ASC");
//                    }
//                ))
                ->add('surveyKeyword', null, array('required' => false))
                ->add('region', null, array('required' => false))
                ->add('city', null, array('required' => false))
                ->add('payrollCurr', null, array('required' => false))
                ->add('billingRate', null, array('required' => false))
                ->add('exclude', null, array('required' => false))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Albatross\DailyBundle\Entity\Rules'
        ));
    }

    public function getName() {
        return 'albatross_dailybundle_rulestype';
    }

}
