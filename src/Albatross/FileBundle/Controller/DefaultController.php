<?php

namespace Albatross\FileBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Albatross\AceBundle\Form\AttachmentsType;
use Albatross\AceBundle\Entity\Attachments;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller {

    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $secu = $this->container->get('security.context');
        $user = $secu->getToken()->getUser();
        $sales_and_bd = 0;
        if ($secu->isGranted('ROLE_ADMIN') || $secu->isGranted('ROLE_SENIOR_PROJECT_MANAGER') || $secu->isGranted('ROLE_PROJECT_MANAGER') || $secu->isGranted('ROLE_BU_MANAGER') || $secu->isGranted('ROLE_EXECUTIVE_DIRECTOR')) {
            $sales_and_bd = 1;
        }
        if ($secu->isGranted('ROLE_HD_MANAGER') && $user->getPosition()->getName() == 'Top Management') {
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


        $sections = $em->getRepository("AlbatrossAceBundle:Filesection")->findBy(array("parent" => null), array("name" => "ASC"));


        $list_section = '';
        foreach ($sections as $section) {
            $filename = $section->getName();
            if (!(in_array($filename, $postionFileArr) && !($filename == $position || $secu->isGranted('ROLE_ADMIN')))) {
                $list_section .= '<option value="' . $section->getId() . '">' . $filename . '</option>';
            }
        }

        $attachmentsOption = $this->container->getParameter('valuelist');
        $attachmentsOption['attachments']['type'] = array('3' => 'other');
        $attachmentsForm = $this->createForm(new AttachmentsType(), $attachmentsOption);

        return $this->render('AlbatrossFileBundle:Default:index.html.twig', array(
                    'list_section' => $list_section,
                    'form' => $attachmentsForm->createView(),
        ));
    }

    public function uploadAction() {
        $attachmentsOption = $this->container->getParameter('valuelist');
        $attachmentsOption['attachments']['type'] = array('3' => 'other');
        $form = $this->createForm(new AttachmentsType(), $attachmentsOption);

        $request = $this->getRequest();

        $form->bindRequest($request);
        $data = $form->getData();
        if ($data['file'] == null) {
            return $this->redirect($this->generateUrl('filelist'));
        }
        $dir = date('Y-m-d');
        $filename = $data['file']->getClientOriginalName();
        $data['file']->move(
                $this->get('kernel')->getRootDir() . '/../web/otherFiles/' . $dir . '/', $filename
        );

        //get label
        $label = $data['label'];

        //get user info
        $secu = $this->container->get('security.context');
        $user_entity = $secu->getToken()->getUser();

        //get path info
        $path = 'otherFiles/' . $dir . '/' . $filename;

        //get date time
        $datetime = date('Y-m-d H:i:s');

        $em = $this->getDoctrine()->getManager();
        $attachment = new Attachments();
        $attachment->setUser($user_entity);
        $attachment->setPath($path);
        $attachment->setSubmitteddate(new \DateTime($datetime));
        $attachment->setStatus('0');
        $attachment->setType('3');
        $attachment->setLabel($label);

        $section = $request->get('section');
        $subsection = $request->get('sub_section');
        if ($subsection) {
            $filesection = $em->getRepository('AlbatrossAceBundle:FileSection')->findOneById($subsection);
        } else {
            $filesection = $em->getRepository('AlbatrossAceBundle:FileSection')->findOneById($section);
        }
        $attachment->setFilesection($filesection);

        $em->persist($attachment);
        $em->flush();
        return $this->redirect($this->generateUrl('file_homepage'));
    }

    public function downloadAction($id) {
        $em = $this->getDoctrine()->getManager();
        
        $file = $em->getRepository("AlbatrossAceBundle:Attachments")->findOneById($id);
        if (!$file)
            throw $this->createNotFoundException('Unable to find File entity.');
        
        // Generate response
        $response = new Response();

        // Set headers
        $response->headers->set('Cache-Control', 'private');
        $response->headers->set('Content-type', mime_content_type($file->getWebPath()));
        $response->headers->set('Content-Disposition', 'attachment; filename="' . basename($file->getWebPath()) . '";');
        $response->headers->set('Content-length', filesize($file->getWebPath()));

        // Send headers before outputting anything
        $response->sendHeaders();

        $response->setContent(readfile($file->getWebPath()));
        
        return $response;
    }

}
