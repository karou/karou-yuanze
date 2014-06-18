<?php

namespace Albatross\CustomBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class CustomwaveType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $number = array();
        for($i =1; $i<=36; $i++){
            $number[$i] = $i;
        }
        $number[100] = 'DEMO';
        $month = array('January','February','March','April','May','June','July','August','September','October','November','December');
        $month2 = array('July','August','September','October','November','December');
        for($v = -1; $v <3; $v++){
            if($v == -1){
                $year = date('Y', strtotime('-1 year', time()));
                foreach($month2 as $m){
                    $date[$m .' '.$year] = $m .' '.$year;
                }
            }else{
                $year = date('Y', strtotime('+'.$v.' year', time()));
                foreach($month as $m){
                    $date[$m .' '.$year] = $m .' '.$year;
                }
            }
        }
        $builder
            ->add('customclient', 'entity', array(
                    'expanded' => false,
                    'class' => 'AlbatrossCustomBundle:Customclient',
                    'property' => 'name',
                    'multiple' => false,
                    'required' => false
            ))
            ->add('projectname', 'text')
            ->add('number', 'choice', array(
                    'choices' => $number,
                    'required' => true,
            ))
            ->add('customproject', 'entity', array(
                'expanded' => false,
                'class' => 'AlbatrossCustomBundle:Customproject',
                'property' => 'name',
                'multiple' => false,
                'required' => false
            ))
            ->add('date', 'choice', array(
                    'choices' => $date,
            ))
            ->add('aceproject', 'entity', array(
                    'expanded' => false,
                    'attr' => array('class' => 'chz_aceproject'),
                    'class' => 'AlbatrossAceBundle:Project',
                    'property' => 'name',
                    'multiple' => true
            ))
            ->add('bis', 'choice', array(
                    'choices' => array('bis'),
                    'required' => false,
            ))
            ->add('totalnum', 'text')
        ;
    }

    public function getName()
    {
        return 'albatross_custombundle_customwavetype';
    }
}
