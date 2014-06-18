<?php

namespace Albatross\DailyBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class FileUploadType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('file', 'file')
                ->add('text', 'text')
        ;
    }

    public function getName() {
        return 'albatross_dailybundle_fileuploadtype';
    }

}
