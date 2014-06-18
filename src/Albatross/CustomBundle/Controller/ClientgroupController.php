<?php

namespace Albatross\CustomBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Albatross\CustomBundle\Entity\Clientgroup;
use Albatross\CustomBundle\Form\ClientgroupType;

/**
 * Clientgroup controller.
 *
 */
class ClientgroupController extends Controller
{
    /**
     * Lists all Clientgroup entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AlbatrossCustomBundle:Clientgroup')->findAll();

        return $this->render('AlbatrossCustomBundle:Clientgroup:index.html.twig', array(
            'entities' => $entities,
            'menu_bar' => 'admin',
            'menu_cal_cur' => 'group',
        ));
    }

    /**
     * Creates a new Clientgroup entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new Clientgroup();
        $form = $this->createForm(new ClientgroupType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('clientgroup_show', array('id' => $entity->getId())));
        }

        return $this->render('AlbatrossCustomBundle:Clientgroup:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to create a new Clientgroup entity.
     *
     */
    public function newAction()
    {
        $entity = new Clientgroup();
        $form   = $this->createForm(new ClientgroupType(), $entity);

        return $this->render('AlbatrossCustomBundle:Clientgroup:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'menu_bar' => 'admin',
            'menu_cal_cur' => 'group',
        ));
    }

    /**
     * Finds and displays a Clientgroup entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AlbatrossCustomBundle:Clientgroup')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Clientgroup entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('AlbatrossCustomBundle:Clientgroup:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
            'menu_bar' => 'admin',
            'menu_cal_cur' => 'group',        ));
    }

    /**
     * Displays a form to edit an existing Clientgroup entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AlbatrossCustomBundle:Clientgroup')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Clientgroup entity.');
        }

        $editForm = $this->createForm(new ClientgroupType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('AlbatrossCustomBundle:Clientgroup:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'menu_bar' => 'admin',
            'menu_cal_cur' => 'group',
        ));
    }

    /**
     * Edits an existing Clientgroup entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AlbatrossCustomBundle:Clientgroup')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Clientgroup entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ClientgroupType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('clientgroup_edit', array('id' => $id)));
        }

        return $this->render('AlbatrossCustomBundle:Clientgroup:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Clientgroup entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AlbatrossCustomBundle:Clientgroup')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Clientgroup entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('clientgroup'));
    }

    /**
     * Creates a form to delete a Clientgroup entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
