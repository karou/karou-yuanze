<?php

namespace Albatross\DailyBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Albatross\DailyBundle\Entity\Status;
use Albatross\DailyBundle\Form\StatusType;

/**
 * Status controller.
 *
 */
class StatusController extends Controller
{
    /**
     * Lists all Status entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AlbatrossDailyBundle:Status')->findBy(array(), array('weight' => 'asc'));

        return $this->render('AlbatrossDailyBundle:Status:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new Status entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new Status();
        $form = $this->createForm(new StatusType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('status_show', array('id' => $entity->getId())));
        }

        return $this->render('AlbatrossDailyBundle:Status:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to create a new Status entity.
     *
     */
    public function newAction()
    {
        $entity = new Status();
        $form   = $this->createForm(new StatusType(), $entity);

        return $this->render('AlbatrossDailyBundle:Status:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Status entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AlbatrossDailyBundle:Status')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Status entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('AlbatrossDailyBundle:Status:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Status entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AlbatrossDailyBundle:Status')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Status entity.');
        }

        $editForm = $this->createForm(new StatusType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('AlbatrossDailyBundle:Status:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Status entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AlbatrossDailyBundle:Status')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Status entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new StatusType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('status_edit', array('id' => $id)));
        }

        return $this->render('AlbatrossDailyBundle:Status:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Status entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AlbatrossDailyBundle:Status')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Status entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('status'));
    }

    /**
     * Creates a form to delete a Status entity by id.
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
