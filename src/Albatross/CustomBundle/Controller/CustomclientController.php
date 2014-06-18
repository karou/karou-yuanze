<?php

namespace Albatross\CustomBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Albatross\CustomBundle\Entity\Customclient;
use Albatross\CustomBundle\Form\CustomclientType;

/**
 * Customclient controller.
 *
 */
class CustomclientController extends Controller {

    /**
     * Lists all Customclient entities.
     *
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $data = $this->getRequest()->request->get('keyword');
        if($data == null){
            $data = '';
        }
        $qb = $em->createQueryBuilder();
        $qb->select('c')
                ->from('AlbatrossCustomBundle:Customclient', 'c')
                ->leftJoin('c.clientgroup', 'g')
                ->where('c.name LIKE :keyword OR g.name LIKE :keyword');
        $qb->setParameters(
                array('keyword' => '%'.$data.'%')
                );
        $query = $qb->getQuery();
        $entities = $query->getResult();
        
        //search end
//        $entities = $em->getRepository('AlbatrossCustomBundle:Customclient')->findAll();
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $entities, $this->get('request')->query->get('page', 1), 20/* page number */
        );
        return $this->render('AlbatrossCustomBundle:Customclient:index.html.twig', array(
                    'entities' => $pagination,
                    'current' => 'custom_project',
                    'menu_bar' => 'custom',
                    'menu_cal_cur' => 'client',
                    'data' => $data
        ));
    }

    /**
     * Creates a new Customclient entity.
     *
     */
    public function createAction(Request $request) {
        $entity = new Customclient();
        $form = $this->createForm(new CustomclientType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('customclient_show', array('id' => $entity->getId(),)));
        }

        return $this->render('AlbatrossCustomBundle:Customclient:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                    'current' => 'custom_project',
                    'menu_bar' => 'custom',
                    'menu_cal_cur' => 'client',
        ));
    }

    /**
     * Displays a form to create a new Customclient entity.
     *
     */
    public function newAction() {
        $entity = new Customclient();
        $form = $this->createForm(new CustomclientType(), $entity);

        return $this->render('AlbatrossCustomBundle:Customclient:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                    'current' => 'custom_project',
                    'menu_bar' => 'custom',
                    'menu_cal_cur' => 'client',
        ));
    }

    /**
     * Finds and displays a Customclient entity.
     *
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AlbatrossCustomBundle:Customclient')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Customclient entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('AlbatrossCustomBundle:Customclient:show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),
                    'current' => 'custom_project',
                    'menu_bar' => 'custom',
                    'menu_cal_cur' => 'client',));
    }

    /**
     * Displays a form to edit an existing Customclient entity.
     *
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AlbatrossCustomBundle:Customclient')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Customclient entity.');
        }

        $editForm = $this->createForm(new CustomclientType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('AlbatrossCustomBundle:Customclient:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
                    'current' => 'custom_project',
                    'menu_bar' => 'custom',
                    'menu_cal_cur' => 'client',
        ));
    }

    /**
     * Edits an existing Customclient entity.
     *
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AlbatrossCustomBundle:Customclient')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Customclient entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new CustomclientType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $entity->upload();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('customclient_edit', array('id' => $id)));
        }

        return $this->render('AlbatrossCustomBundle:Customclient:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Customclient entity.
     *
     */
    public function deleteAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AlbatrossCustomBundle:Customclient')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Customclient entity.');
        }
        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('customclient'));
    }

    /**
     * Creates a form to delete a Customclient entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {
        return $this->createFormBuilder(array('id' => $id))
                        ->add('id', 'hidden')
                        ->getForm()
        ;
    }
    
    public function checkClientNameAction(){
        $data = $this->getRequest()->getContent();
        $em = $this->getDoctrine()->getManager();
        $result = $em->getRepository('AlbatrossCustomBundle:Customclient')->findByName($data);

        if(empty($result)){
            $return = 1;
        }else{
            $return = 0;
        }
        
        return new \Symfony\Component\HttpFoundation\Response($return);
    }
    
    public function checkProjectBindToClientAction(){
        $em = $this->getDoctrine()->getManager();
        $id = $this->getRequest()->getContent();
        $customclientEntity = $em->getRepository('AlbatrossCustomBundle:Customclient')->find($id);
        $customprojectEntity = $customclientEntity->getCustomproject()->toArray();
        $count = count($customprojectEntity);
        return new Response($count);
    }

}
