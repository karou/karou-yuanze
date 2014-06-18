<?php

namespace Albatross\CustomBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($current)
    {
        $current_menu = 'client';
        return $this->render('AlbatrossCustomBundle:Default:index.html.twig', array(
                    'current' => $current,
                    'menu_bar' => 'custom',
                    'menu_cal_cur' => $current_menu,
            ));
    }
    
    public function menuAction($current_menu)
    {
        $secu = $this->container->get('security.context');
        $menu = array();
        if(!$secu->isGranted('ROLE_TYPE_CLIENT')){
            if($secu->isGranted('ROLE_ADMIN')){
                $menu['client'] = array(
                    'label' => 'Client', //label is name for show on the page
                    'route' => 'customclient', 
                    'children' => array(
                        'add_customclient' => array(
                            'label' => 'Add New Client',
                            'route' => 'customclient_new',
                        )
                    )
                );
            }
            $menu['project'] = array(
                'label' => 'Project',
                'route' => 'customproject',
                'children' => array(
                    'add_customproject' => array(
                        'label' => 'Add New Project',
                        'route' => 'customproject_new'
                    ),
                )
            );
        }else{
            $menu['project'] = array(
                'label' => 'Project',
                'route' => 'customproject'
            );
        }
        return $this->render('AlbatrossCustomBundle:Default:menu.html.twig', array(
                    'menu' => $menu,
                    'menu_cal_cur' => $current_menu,
        ));
    }
}