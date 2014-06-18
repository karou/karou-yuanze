<?php

namespace Albatross\AceBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Albatross\AceBundle\Entity\Country;
use Albatross\AceBundle\Form\CountryType;

/**
 * Country controller.
 *
 */
class CountryController extends Controller {

    /**
     * Creates a new Country entity.
     *
     */
    public function createAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $bu = $em->getRepository('AlbatrossAceBundle:Bu')->find($id);
        if (!$bu) {
            throw $this->createNotFoundException('Unable to find Bu entity.');
        }
        $entity = new Country();
        $form = $this->createForm(new CountryType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $entity->setBu($bu);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('bu'));
        }

        return $this->render('AlbatrossAceBundle:Bu:add.html.twig', array(
                    'entity' => $bu,
                    'form' => $form->createView(),
                    'menu_bar' => 'admin',
                    'menu_cal_cur' => 'countryandbu'
        ));
    }

    /**
     * Deletes a Country entity.
     *
     */
    public function deleteAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AlbatrossAceBundle:Country')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Country entity.');
        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('bu'));
    }

}
