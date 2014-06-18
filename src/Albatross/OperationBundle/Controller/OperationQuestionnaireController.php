<?php

namespace Albatross\OperationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Albatross\OperationBundle\Entity\OperationQuestionnaire;
use Albatross\OperationBundle\Form\OperationQuestionnaireType;

/**
 * OperationQuestionnaire controller.
 *
 */
class OperationQuestionnaireController extends Controller
{
    /**
     * Lists all OperationQuestionnaire entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AlbatrossOperationBundle:OperationQuestionnaire')->findAll();

        return $this->render('AlbatrossOperationBundle:OperationQuestionnaire:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new OperationQuestionnaire entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new OperationQuestionnaire();
        $form = $this->createForm(new OperationQuestionnaireType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('operationquestionnaire_show', array('id' => $entity->getId())));
        }

        return $this->render('AlbatrossOperationBundle:OperationQuestionnaire:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to create a new OperationQuestionnaire entity.
     *
     */
    public function newAction()
    {
        $entity = new OperationQuestionnaire();
        $form   = $this->createForm(new OperationQuestionnaireType(), $entity);

        return $this->render('AlbatrossOperationBundle:OperationQuestionnaire:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a OperationQuestionnaire entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AlbatrossOperationBundle:OperationQuestionnaire')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find OperationQuestionnaire entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('AlbatrossOperationBundle:OperationQuestionnaire:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing OperationQuestionnaire entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AlbatrossOperationBundle:OperationQuestionnaire')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find OperationQuestionnaire entity.');
        }

        $editForm = $this->createForm(new OperationQuestionnaireType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('AlbatrossOperationBundle:OperationQuestionnaire:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing OperationQuestionnaire entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AlbatrossOperationBundle:OperationQuestionnaire')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find OperationQuestionnaire entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new OperationQuestionnaireType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('operationquestionnaire_edit', array('id' => $id)));
        }

        return $this->render('AlbatrossOperationBundle:OperationQuestionnaire:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a OperationQuestionnaire entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AlbatrossOperationBundle:OperationQuestionnaire')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find OperationQuestionnaire entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('operationquestionnaire'));
    }

    /**
     * Creates a form to delete a OperationQuestionnaire entity by id.
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
