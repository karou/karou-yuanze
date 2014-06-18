<?php

namespace Albatross\AceBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Albatross\AceBundle\Entity\Bu;
use Albatross\AceBundle\Entity\Country;
use Albatross\AceBundle\Form\BuType;
use Albatross\AceBundle\Form\CountryType;

/**
 * Bu controller.
 *
 */
class BuController extends Controller
{
    /**
     * Lists all Bu entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AlbatrossAceBundle:Bu')->findAll();

        return $this->render('AlbatrossAceBundle:Bu:index.html.twig', array(
            'entities' => $entities,
            'menu_bar' => 'admin',
            'menu_cal_cur' => 'countryandbu',
        ));
    }

    public function addAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AlbatrossAceBundle:Bu')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Bu entity.');
        }

        $form = $this->createForm(new CountryType(), new Country());

        return $this->render('AlbatrossAceBundle:Bu:add.html.twig', array(
            'entity'      => $entity,
            'form'   => $form->createView(),
            'menu_bar' => 'admin',
            'menu_cal_cur' => 'countryandbu',
        ));
    }
    
}
