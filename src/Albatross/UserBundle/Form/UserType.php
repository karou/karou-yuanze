<?php

namespace Albatross\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('fullname', null, array(
                'required' => false,
            ))
            ->add('title', null, array(
                'required' => false,
            ))
            ->add('skype', null, array(
                'required' => false,
            ))
            ->add('mobile', null, array(
                'required' => false,
            ))
            ->add('office_phone', null, array(
                'required' => false,
            ))
            ->add('office_address', null, array(
                'required' => false,
            ))
            ->add('country', null, array(
                'required' => false,
            ))
            ->add('password')
            ->add('email', null, array(
                'required' => false,
            ))
            ->add('pic', null, array(
                'required' => false,
            ))
            ->add('file', 'file', array(
                'required' => false,
            ))
            ->add('aol_username', null, array(
                'required' => false,
            ))
            ->add('aol_password', null, array(
                'required' => false,
            ))
            ->add('ace_username', null, array(
                'required' => false,
            ))
            ->add('ace_password', null, array(
                'required' => false,
            ))
            ->add('crm_username', null, array(
                'required' => false,
            ))
            ->add('crm_password', null, array(
                'required' => false,
            ))
            ->add('create_at', null, array(
                'required' => false,
            ))
            ->add('update_at', null, array(
                'required' => false,
            ))
            ->add('type', 'choice', array(
                'choices' => array(0 => 'User', 1 => 'Client')
            ))
            ->add('customproject', null, array(
                'attr' => array(
                    'class' => 'chz_project',
                    'multiple' => true,
                    ),
                'required' => false
            ))
            ->add('customclient', null, array(
                'attr' => array(
                    'class' => 'chz_client',
                    'multiple' => true,
                    ),
                'required' => false
            ))
            ->add('identity', 'entity', array(
                    'expanded' => false,
                    'required' => false,
                    'class' => 'AlbatrossUserBundle:Identity',
                    'property' => 'name',
                    'multiple' => false,
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('i')
                                ->where("i.name != :client")
                                ->setParameters(array('client' => 'Client'));
                    }
            ))
            ->add('position', 'entity', array(
                    'expanded' => false,
                    'required' => false,
                    'class' => 'AlbatrossUserBundle:Position',
                    'property' => 'name',
                    'multiple' => false,
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('p')
                                ->where("p.name != :client")
                                ->setParameters(array('client' => 'Client'));
                    }
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Albatross\UserBundle\Entity\User'
        ));
    }

    public function getName()
    {
        return 'albatross_userbundle_usertype';
    }
}
