<?php

namespace Albatross\OperationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Albatross\OperationBundle\Entity\OperationProject;
use Albatross\OperationBundle\Form\OperationProjectType;

/**
 * OperationProject controller.
 *
 */
class OperationProjectController extends Controller
{
    /**
     * Lists all OperationProject entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AlbatrossOperationBundle:OperationProject')->findAll();

        return $this->render('AlbatrossOperationBundle:OperationProject:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new OperationProject entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new OperationProject();
        $form = $this->createForm(new OperationProjectType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('operationproject_show', array('id' => $entity->getId())));
        }

        return $this->render('AlbatrossOperationBundle:OperationProject:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to create a new OperationProject entity.
     *
     */
    public function newAction()
    {
        $entity = new OperationProject();
        $form   = $this->createForm(new OperationProjectType(), $entity);

        return $this->render('AlbatrossOperationBundle:OperationProject:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a OperationProject entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AlbatrossOperationBundle:OperationProject')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find OperationProject entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('AlbatrossOperationBundle:OperationProject:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing OperationProject entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AlbatrossOperationBundle:OperationProject')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find OperationProject entity.');
        }

        $editForm = $this->createForm(new OperationProjectType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('AlbatrossOperationBundle:OperationProject:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing OperationProject entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AlbatrossOperationBundle:OperationProject')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find OperationProject entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new OperationProjectType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('operationproject_edit', array('id' => $id)));
        }

        return $this->render('AlbatrossOperationBundle:OperationProject:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a OperationProject entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AlbatrossOperationBundle:OperationProject')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find OperationProject entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('operationproject'));
    }

    /**
     * Creates a form to delete a OperationProject entity by id.
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
