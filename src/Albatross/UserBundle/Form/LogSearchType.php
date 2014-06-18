<?php

namespace Albatross\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class LogSearchType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('logsearch_from', 'text', array(
                    'required' => false
                ))
                ->add('logsearch_to', 'text', array(
                    'required' => false
                ))
                ->add('search_down', 'text', array(
                    'required' => true,
                    'attr' => array('style' => 'display:none', 'value' => '0'),
                ))
        ;
    }

    public function getName() {
        return 'albatross_userbundle_logsearchtype';
    }

}
