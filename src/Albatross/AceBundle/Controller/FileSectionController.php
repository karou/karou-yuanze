<?php

namespace Albatross\AceBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Albatross\AceBundle\Entity\FileSection;
use Albatross\AceBundle\Form\FileSectionType;
use Albatross\AceBundle\Form\AttachmentsType;

/**
 * FileSection controller.
 *
 */
class FileSectionController extends Controller
{
    /**
     * Lists all FileSection entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AlbatrossAceBundle:FileSection')->findAll();

        return $this->render('AlbatrossAceBundle:FileSection:index.html.twig', array(
            'entities' => $entities,
            'menu_bar' => 'admin',
            'menu_cal_cur' => 'filesection',
        ));
    }

    /**
     * Creates a new FileSection entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new FileSection();
        $form = $this->createForm(new FileSectionType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('filesection_show', array('id' => $entity->getId())));
        }

        return $this->render('AlbatrossAceBundle:FileSection:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to create a new FileSection entity.
     *
     */
    public function newAction()
    {
        $entity = new FileSection();
        $form   = $this->createForm(new FileSectionType(), $entity);

        return $this->render('AlbatrossAceBundle:FileSection:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'menu_bar' => 'admin',
            'menu_cal_cur' => 'filesection',
        ));
    }

    /**
     * Finds and displays a FileSection entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AlbatrossAceBundle:FileSection')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FileSection entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('AlbatrossAceBundle:FileSection:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
            'menu_bar' => 'admin',
            'menu_cal_cur' => 'filesection',      ));
    }

    /**
     * Displays a form to edit an existing FileSection entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AlbatrossAceBundle:FileSection')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FileSection entity.');
        }

        $editForm = $this->createForm(new FileSectionType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('AlbatrossAceBundle:FileSection:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'menu_bar' => 'admin',
            'menu_cal_cur' => 'filesection',
        ));
    }

    /**
     * Edits an existing FileSection entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AlbatrossAceBundle:FileSection')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FileSection entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new FileSectionType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('filesection_edit', array('id' => $id)));
        }

        return $this->render('AlbatrossAceBundle:FileSection:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a FileSection entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AlbatrossAceBundle:FileSection')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find FileSection entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('filesection'));
    }

    /**
     * Creates a form to delete a FileSection entity by id.
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
    
    public function SectionListAction($current){
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AlbatrossAceBundle:FileSection')->findAll();

        $secu = $this->container->get('security.context');
        $user = $secu->getToken()->getUser();
        $sales_and_bd = 0;
        if($secu->isGranted('ROLE_ADMIN') || $secu->isGranted('ROLE_SENIOR_PROJECT_MANAGER') || $secu->isGranted('ROLE_PROJECT_MANAGER') || $secu->isGranted('ROLE_BU_MANAGER') || $secu->isGranted('ROLE_EXECUTIVE_DIRECTOR')){
            $sales_and_bd = 1;
        }
        if($secu->isGranted('ROLE_HD_MANAGER') && $user->getPosition()->getName() == 'Top Management'){
            $sales_and_bd = 1;
        }
        $position = $user->getPosition()->getName();
        $postionFileArr = array('Market Research Dept',
                                'Quality Control Dept',
                                'Finance Dept',
                                'Human Resource Dept',
                                'Consumer Insight Dept',
                                'Pacific Editing Dept',
                                'Greater China Ed. Dept',
                                'Atlantic Editing Dept',
                                'Global Operations Dept',
                                'Middle East BU',
                                'United States BU',
                                'Russia BU',
                                'Brazil BU',
                                'North East Europe BU',
                                'Australia BU',
                                'China BU',
                                'United Kingdom BU',
                                'Japan BU',
                                'Hong Kong BU',
                                'Singapore BU',
                                'India BU',
                                'Korea BU',
                                'Thailand BU',
                                'Taiwan BU',
                                'South West Europe BU',
                                'Client',
                                );
        $positionAccessArr = array();
        foreach($entities as $entity){
            $filename = $entity->getName();
            if(in_array($filename, $postionFileArr)){
                if($filename == $position || $secu->isGranted('ROLE_ADMIN')){
                    $positionAccessArr[$filename] = 1;
                }else{
                    $positionAccessArr[$filename] = 0;
                }
            }else{
                $positionAccessArr[$filename] = 1;
            }
        }
        $attachmentsOption = $this->container->getParameter('valuelist');
        $attachmentsOption['attachments']['type'] = array('3' => 'other');
        $attachmentsForm = $this->createForm(new AttachmentsType(), $attachmentsOption);
        
        return $this->render('AlbatrossAceBundle:Default:section.html.twig', array(
            'entities' => $entities,
            'current' => $current,
            'attachmentsForm' => $attachmentsForm->createView(),
            'sales_and_bd' => $sales_and_bd,
            'positionAccess' => $positionAccessArr,
        ));
    }
}