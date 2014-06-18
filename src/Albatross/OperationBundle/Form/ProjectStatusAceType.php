<?php

namespace Albatross\OperationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class projectStatusAceType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('brand', 'entity', array(
                    'expanded' => false,
                    'class' => 'AlbatrossCustomBundle:Customclient',
                    'property' => 'name',
                    'multiple' => true,
                    'required' => false,
                    'attr' => array('class' => 'multi_choose'),
                ))
                ->add('bu', 'entity', array(
                    'expanded' => false,
                    'class' => 'AlbatrossAceBundle:Bu',
                    'property' => 'name',
                    'multiple' => true,
                    'required' => false,
                    'attr' => array('class' => 'multi_choose'),
                ))
                ->add('ace', 'entity', array(
                    'expanded' => false,
                    'class' => 'AlbatrossAceBundle:Project',
                    'property' => 'name',
                    'multiple' => true,
                    'required' => false,
                    'attr' => array('class' => 'multi_choose'),
                ))
                ->add('acenumber', 'entity', array(
                    'expanded' => false,
                    'class' => 'AlbatrossAceBundle:Task',
                    'property' => 'projectnumber',
                    'multiple' => true,
                    'required' => false,
                    'attr' => array('class' => 'multi_choose'),
                    'query_builder' => function (EntityRepository $er) {
                         $query = $er->createQueryBuilder('t')
                                ->where("t.number > 100 AND t.number < 117 ");
                         return $query;
                    }
                ))
                ->add('country', 'entity', array(
                    'expanded' => false,
                    'class' => 'AlbatrossAceBundle:Country',
                    'property' => 'name',
                    'multiple' => true,
                    'required' => false,
                    'attr' => array('class' => 'multi_choose'),
                ))
                ->add('pm', 'entity', array(
                    'expanded' => false,
                    'class' => 'AlbatrossUserBundle:User',
                    'property' => 'username',
                    'multiple' => true,
                    'required' => false,
                    'attr' => array('class' => 'multi_choose'),
                ))
                ->add('fw_s_f', 'text', array(
                    'required' => false
                ))
                ->add('fw_s_t', 'text', array(
                    'required' => false
                ))
                ->add('fw_e_f', 'text', array(
                    'required' => false
                ))
                ->add('fw_e_t', 'text', array(
                    'required' => false
                ))
                ->add('due_f', 'text', array(
                    'required' => false
                ))
                ->add('due_t', 'text', array(
                    'required' => false
                ))
                ->add('assign_f', 'text', array(
                    'required' => false
                ))
                ->add('assign_t', 'text', array(
                    'required' => false
                ))
                ->add('fw_done_f', 'text', array(
                    'required' => false
                ))
                ->add('fw_done_t', 'text', array(
                    'required' => false
                ))
                ->add('editing_done_f', 'text', array(
                    'required' => false
                ))
                ->add('editing_done_t', 'text', array(
                    'required' => false
                ))
        ;
    }

    public function getName() {
        return 'albatross_operationbundle_projectstatusacetype';
    }
}
