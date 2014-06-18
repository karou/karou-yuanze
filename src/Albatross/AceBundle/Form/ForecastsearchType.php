<?php

namespace Albatross\AceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class forecastsearchType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $month = $options['data']['month'];
        $user_bu = $options['data']['user_bu'];
        $builder
                ->add('client', 'text', array(
                    'required' => false
                ))
                ->add('bu', 'entity', array(
                    'expanded' => false,
                    'class' => 'AlbatrossAceBundle:Bu',
                    'property' => 'name',
                    'multiple' => false,
                    'required' => false
                ))
                ->add('project', 'text', array(
                    'required' => false
                ))
                ->add('contract', 'text', array(
                    'required' => false
                ))
                ->add('step', 'choice', array(
                    'choices' => array('Contract','PM update','IOF'),
                    'multiple' => true,
                    'required' => false
                ))
                ->add('scope_f', 'text', array(
                    'required' => false
                ))
                ->add('scope_t', 'text', array(
                    'required' => false
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
                ->add('f_month', 'choice', array(
                    'choices' => $month,
                    'multiple' => true,
                    'required' => false
                ))
                ->add('scope_year', 'checkbox', array(
                    'required' => false
                ))
                ->add('update_f', 'text', array(
                    'required' => false
                ))
                ->add('update_t', 'text', array(
                    'required' => false
                ))
        ;
        if($user_bu == ''){
            $builder
                ->add('user', 'entity', array(
                    'expanded' => false,
                    'class' => 'AlbatrossUserBundle:User',
                    'property' => 'username',
                    'multiple' => true,
                    'required' => false,
                    'attr' => array('class' => 'user_choose'),
                    'query_builder' => function (EntityRepository $er) {
                         $query = $er->createQueryBuilder('u')
                                ->leftJoin('u.position', 'p')
                                ->leftJoin('u.identity', 'i')
                                ->where("i.name in ('Project Manager', 'Senior Project Manager', 'BU manager')")
                                ->andWhere("u.status = :active")
                                ->setParameters(array('active' => 'active'));
                         return $query;
                    }
                ));
        }else{
            $builder
                ->add('user', 'entity', array(
                    'expanded' => false,
                    'property' => 'username',
                    'multiple' => true,
                    'required' => false,
                    'attr' => array('class' => 'user_choose'),
                    'class' => 'AlbatrossUserBundle:User',
                    'query_builder' => function (EntityRepository $er) use ($user_bu) {
                         $query = $er->createQueryBuilder('u')
                                ->leftJoin('u.position', 'p')
                                ->leftJoin('u.identity', 'i')
                                ->where("p.id = :pid")
                                ->andWhere("i.name in ('Project Manager', 'Senior Project Manager', 'BU manager')")
                                ->andWhere("u.status = :active")
                                ->setParameters(array('pid' => (int)$user_bu, 'active' => 'active'));
                         return $query;
                    }
                ));
        }
    }

    public function getName() {
        return 'albatross_acebundle_forecastsearchtype';
    }

}
