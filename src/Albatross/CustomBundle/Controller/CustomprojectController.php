<?php

namespace Albatross\CustomBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Albatross\CustomBundle\Entity\Customproject;
use Albatross\CustomBundle\Form\CustomprojectType;
use Albatross\CustomBundle\Form\CustomfieldType;
use Albatross\CustomBundle\Entity\Customfield;
use Albatross\CustomBundle\Form\RecapType;
use Albatross\CustomBundle\Form\ScenariosType;
use Albatross\CustomBundle\Entity\Recap;
use Albatross\CustomBundle\Entity\SurveyNumber;
use Albatross\CustomBundle\Entity\Infomation;
use Albatross\AceBundle\Form\AttachmentsType;
use Albatross\AceBundle\Entity\Attachments;
use Albatross\CustomBundle\Entity\Aolquestionnaire;
use Albatross\AceBundle\Entity\IOFFile;
use Albatross\AceBundle\Entity\IOFMessage;
use Albatross\AceBundle\Entity\Attachinfo;
use Albatross\CustomBundle\Entity\KickOffMeetingRecap;
use Albatross\CustomBundle\Form\KickOffMeetingRecapType;
use Albatross\CustomBundle\Entity\Invoice;
use Albatross\CustomBundle\Form\InvoiceType;
use Albatross\CustomBundle\Entity\TranslationFile;

/**
 * Customproject controller.
 *
 */
class CustomprojectController extends Controller {

    /**
     * Lists all Customproject entities.
     *
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $secu = $this->container->get('security.context');
        $user = $secu->getToken()->getUser();

        $groupk = $this->getRequest()->get('groupk');
        $clientk = $this->getRequest()->get('clientk');
        $typek = $this->getRequest()->get('typek');
        $scopek = $this->getRequest()->get('scopek');
        $aceNum = $this->getRequest()->get('ace_num_k');
        
        $scopeNumArr = array('1' => 14,
            '2' => 7, '3' => 11, '4' => 13,
            '5' => 9, '6' => 10, '7' => 16,
            '8' => 6, '9' => 4, '10' => 5,
            '11' => 12, '12' => 3, '13' => 15,
            '14' => 8, '15' => 1, '16' => 2);

        if ($secu->isGranted('ROLE_TYPE_CLIENT'))
            $user_type = 1;
        else
            $user_type = 0;

        $scopeAndTypeOption = $this->container->getParameter('valuelist');
        $scope = $scopeAndTypeOption['scope'];
        $type = $scopeAndTypeOption['type'];

        if ($groupk == '' && $clientk == '' && $typek == '' && $scopek == '' && $aceNum == '') {
            if ($user->getType() != 1) {
                $entities = $em->getRepository('AlbatrossCustomBundle:Customproject')->findAll();
            } else {
                $qb = $em->createQueryBuilder();
                $qb->add('select', 'c')
                        ->add('from', 'AlbatrossCustomBundle:Customproject c')
                        ->leftJoin('c.user', 'u')
                        ->where('u.id = :uid');
                $qb->setParameters(array(
                    'uid' => $user->getId()
                ));

                $query = $qb->getQuery();
                $entities = $query->getResult();
            }
        } else {
            $parameters = array();
            if ($user->getType() != 1) {
                $qb = $em->createQueryBuilder();
                $qb->add('select', 'c')
                        ->add('from', 'AlbatrossCustomBundle:Customproject c')
                        ->leftJoin('c.customclient', 'cc')
                        ->leftJoin('cc.clientgroup', 'cg')
                        ->leftJoin('c.customwave', 'cw')
                        ->leftJoin('cw.project', 'proj')
                        ->leftJoin('proj.tasks', 'task')
                        ->leftJoin('cw.campaign', 'campaign')
                        ->leftJoin('campaign.aolsurvey', 'aol')
                        ->leftJoin('aol.location', 'location')
                        ->leftJoin('location.country', 'country')
                        ->leftJoin('country.bu', 'bu')
                        ->where('cc.id is not null');
            } else {
                $qb = $em->createQueryBuilder();
                $qb->add('select', 'c')
                        ->add('from', 'AlbatrossCustomBundle:Customproject c')
                        ->leftJoin('c.user', 'u')
                        ->where('u.id = :uid');
                $parameters = array(
                    'uid' => $user->getId()
                );
            }
            if ($groupk != '') {
                $qb->andWhere('cg.name LIKE :groupk');
            }
            if ($clientk != '') {
                $qb->andWhere('cc.name LIKE :clientk');
            }
            if ($typek != '') {
                $qb->andWhere('c.type = :typek');
            }
            if ($scopek != '') {
                if ($scopek > 16) {
                    $qb->andWhere('c.scope = :scopek');
                } else {
                    $qb->andWhere('c.scope = :scopek OR (bu.code = :bucode AND (c.scope = :bucode2 OR c.scope = 17 OR c.scope = 18 OR c.scope = 19)) OR (task.number > 100 AND task.number < 117 AND task.number = :tasknum)');
                }
            }
            if ($aceNum != '') {
                $qb->andWhere('task.number > 100 AND task.number < 117 AND task.projectnumber LIKE :acenum');
            }
            //==================================================================
            if ($groupk != '') {
                $parameters = array_merge($parameters, array('groupk' => '%' . $groupk . '%'));
            }
            if ($clientk != '') {
                $parameters = array_merge($parameters, array('clientk' => '%' . $clientk . '%'));
            }
            if ($typek != '') {
                $parameters = array_merge($parameters, array('typek' => $typek));
            }
            if ($scopek != '') {
                if ($scopek > 16) {
                    $parameters = array_merge($parameters, array('scopek' => $scopek));
                } else {
                    $parameters = array_merge($parameters, array('scopek' => $scopek, 'bucode' => $scope[$scopek], 'bucode2' => $scopek, 'tasknum' => $scopeNumArr[$scopek] + 100));
                }
            }
            if ($aceNum != '') {
                $parameters = array_merge($parameters, array('acenum' => '%' . $aceNum . '%'));
            }

            $qb->setParameters($parameters);
            $query = $qb->getQuery();
            $entities = $query->getResult();
        }
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $entities, $this->get('request')->query->get('page', 1), 20/* page number */
        );

        $scopeOption = $this->getOption($scope);
        $typeOption = $this->getOption($type);
        return $this->render('AlbatrossCustomBundle:Customproject:index.html.twig', array(
                    'entities'      => $pagination,
                    'current'       => 'custom_project',
                    'menu_bar'      => 'custom',
                    'menu_cal_cur'  => 'project',
                    'scope'         => $scope,
                    'type'          => $type,
                    'scopek'        => $scopek,
                    'typek'         => $typek,
                    'clientk'       => $clientk,
                    'groupk'        => $groupk,
                    'typeoption'    => '<select id="typeselect" name="typek">' . $typeOption . '</select>',
                    'scopeoption'   => '<select id="scopeselect" name="scopek">' . $scopeOption . '</select>',
                    'user_type'     => $user_type
        ));
    }

    public function foroneTimeToLastStartAction() {
        $em = $this->getDoctrine()->getManager();

        $projArr    = $em->getRepository('AlbatrossCustomBundle:CustomProject')->findAll();
        $allArr     = array();
        $hasFileArr = array();

        foreach ($projArr as $proj) {
            $waveArr = $proj->getCustomwave()->toArray();
            foreach ($waveArr as $wave) {
                $attach     = $wave->getAttachments();
                $field      = $wave->getCustomfield()->toArray();
                $invoice    = $wave->getInvoice();
                if (!empty($attach) || !empty($field) || !empty($invoice)) {
                    $hasFileArr[$proj->getId()][] = $wave;
                }
                $allArr[$proj->getId()][] = $wave;
            }
        }
        $lastWaveArr = array();
        foreach ($allArr as $key => $all) {
            if (!empty($hasFileArr[$key])) {
                $checkBiggestNum = array('num' => 0, 'year' => 0, 'month' => 0);
                foreach ($hasFileArr[$key] as $k => $waveHasFile) {
                    if (($waveHasFile->getYear() > $checkBiggestNum['year']) ||
                            ( ($waveHasFile->getYear() == $checkBiggestNum['year']) && ($waveHasFile->getMonth() > $checkBiggestNum['month']) ) ||
                            ( ($waveHasFile->getYear() == $checkBiggestNum['year']) && ($waveHasFile->getMonth() == $checkBiggestNum['month']) && ($waveHasFile->getWavenum() > $checkBiggestNum['num']) )) {
                        $checkBiggestNum['num']     = $waveHasFile->getWavenum();
                        $checkBiggestNum['year']    = $waveHasFile->getYear();
                        $checkBiggestNum['month']   = $waveHasFile->getMonth();
                        $lastWaveArr[$key]          = $waveHasFile;
                    }
                }
            }
        }
        foreach ($lastWaveArr as $k => $waveHaveToSave) {
            $waveHaveToSave->setLastStart(1);
            $em->persist($waveHaveToSave);
        }

        $em->flush();
        var_dump('all done');
        exit();
    }

    //invoice part//////////////////////////////////////////////////////////////
    public function invoiceAction() {
        $form = $this->createForm(new InvoiceType());

        $render = 'AlbatrossCustomBundle:Customproject:invoice.html.twig';
        return $this->render($render, array(
            'invoiceForm' => $form->createView()
        ));
    }
    
    public function createInvoiceAction(Request $request, $wid, $type) {
        $entity = new Invoice();
        $form = $this->createForm(new InvoiceType(), $entity);
        $form->bind($request);
        $em = $this->getDoctrine()->getManager();
        $waveEntity = $em->getRepository('AlbatrossCustomBundle:Customwave')->find($wid);

        //if type 1 deposit, type 2 balance
        $typeArr = array('Deposit' => 1, 'Balance' => 2);

        if ($form->isValid()) {
            $this->checkLastKaWave($waveEntity);
            $content = $entity->getFile();
            $filename = $content->getClientOriginalName();
            $entity->setCustomwave($waveEntity);
            $date = date('ymd');
            $path = 'Invoice/' . $date . '/' . $entity->getCustomwave()->getName() . '/' . $type . '/' . $filename;
            $dir = $this->get('kernel')->getRootDir() . '/../web/Invoice/' . $date . '/' . $entity->getCustomwave()->getName() . '/' . $type . '/';
            $content->move($dir, $filename);
            $entity->setInvoiceType($typeArr[$type]);
            $entity->setPath($path);
            $em->persist($entity);
            $em->flush();

            if (is_object($entity->getCustomwave()->getProjectManager()) && !($to = $entity->getCustomwave()->getProjectManager()->getEmail()))
                $to = "dbentouhami@albatross.fr";
            if (is_object($entity->getProjectManager()) && !($toCc = $entity->getProjectManager()->getEmail()))
                $toCc = "dbentouhami@albatross.fr";
            $message = \Swift_Message::newInstance()
                    ->setSubject('New Invoice Uploaded')
                    ->setFrom('noreply@albatrossasia.com')
                    ->setTo($to)
                    ->setCc($toCc)
                    ->setBcc("dbentouhami@albatross.fr")
                    ->setBody(
                        $this->renderView(
                                'AlbatrossCustomBundle:Email:invoice.html.twig', array('invoice' => $entity)
                            )
                    )
            ;
            $this->get('mailer')->send($message);
        }

        $referer = $this->getRequest()->headers->get('referer');
        return $this->redirect($referer);
    }

    protected function getInvoiceList($waves) {
        $result = array();
        foreach ($waves as $wave) {
            $invoices = $wave->getInvoice()->toArray();
            if (!empty($invoices)) {
                foreach ($invoices as $invoice) {
                    if(is_object($invoice->getBu())){
                        $buCode = $invoice->getBu()->getCode();
                    }else{
                        $buCode = '';
                    }
                    if ($invoice->getInvoiceType() == 1) {
                        if (!isset($result[$wave->getId()]['deposit']))
                            $result[$wave->getId()]['deposit'] = '<ul class="invoice-list-ul">';
                        $result[$wave->getId()]['deposit'] .= '<li onclick="showInvoiceInfo(\'' . $invoice->getId() . '\')">' . $invoice->getLabel() .' '. $buCode . '</li>';
                    }else if ($invoice->getInvoiceType() == 2) {
                        if (!isset($result[$wave->getId()]['balance']))
                            $result[$wave->getId()]['balance'] = '<ul class="invoice-list-ul">';
                        $result[$wave->getId()]['balance'] .= '<li onclick="showInvoiceInfo(\'' . $invoice->getId() . '\')">' . $invoice->getLabel() .' '. $buCode . '</li>';
                    }
                }
            }
        }
        return $result;
    }

    public function showInvoiceInfoAction($invoiceid) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $invoiceEntity = $em->getRepository('AlbatrossCustomBundle:Invoice')->find($invoiceid);
        $typeArr = array(1 => 'Deposit Invoice', 2 => 'Balance Invoice');
        $result = '<table id="invoice-info-table"><tr><th colspan="2">' . $typeArr[$invoiceEntity->getInvoiceType()] . '</th></tr>';
        $result .= '<tr><th>Label</th><td>' . $invoiceEntity->getLabel() . '</td></tr>';
        $result .= '<tr><th>Download</th><td><form action="' . $request->getBaseUrl() . '/Customproject/downloadInvoiceFile/' . $invoiceid . '"><input type="submit" value="Download" /></td></tr>';
        $result .= '<tr><th>Invoice Number</th><td>' . $invoiceEntity->getNumber() . '</td></tr>';
        $result .= '<tr><th>Description</th><td>' . $invoiceEntity->getDescription() . '</td></tr>';
        if($invoiceEntity->getRegional() && is_object($invoiceEntity->getBu())){
            $result .= '<tr><th>is it a local invoice for a regional program ?</th><td>yes</td></tr>';
            $result .= '<tr><th>BU</th><td>' . $invoiceEntity->getBu()->getName() . '</td></tr>';
            $result .= '<tr><th>Project Manager</th><td>' . $invoiceEntity->getProjectmanager()->getFullname() . '</td></tr>';
        }else{
            $result .= '<tr><th>is it a local invoice for a regional program ?</th><td>no</td></tr>';
        }
        $result .= '<tr><th colspan="2"><input type="button" value="close" onclick="closeInvoiceInfo();"></th></tr>';
        $result .= '</table>';

        return new Response($result);
    }

    //end invoice part//////////////////////////////////////////////////////////
    //kick off meeting part/////////////////////////////////////////////////////
    public function meetingRecapAction() {
        $form = $this->createForm(new KickOffMeetingRecapType());

        $render = 'AlbatrossCustomBundle:Customproject:kickOffMeetingRecap.html.twig';
        return $this->render($render, array(
                    'meetingRecapForm' => $form->createView()
        ));
    }

    public function CreateUpdateKickOffMeetingAction(Request $request, $wid) {

        $entity = new KickOffMeetingRecap();
        $form = $this->createForm(new KickOffMeetingRecapType(), $entity);
        $form->bind($request);
        $em = $this->getDoctrine()->getManager();
        $waveEntity = $em->getRepository('AlbatrossCustomBundle:Customwave')->find($wid);

        if ($form->isValid()) {
            $entity->setCustomwave($waveEntity);
            $em->persist($entity);
            $em->flush();
        }

        $referer = $this->getRequest()->headers->get('referer');
        return $this->redirect($referer);
    }

    public function createUpdateKickOffMeetingInfoAction($wid) {
        $em = $this->getDoctrine()->getManager();
        $waveEntity = $em->getRepository('AlbatrossCustomBundle:Customwave')->find($wid);
        $KickOffMeetingEntity = $waveEntity->getMeetingRecap();
        $result = '<table><tr><th colspan="2">Kick-Off Meeting Recap
                <span id="close_button" style=" position: absolute; right:0;" onclick="closeKickOff();" title="close">
                <img width="20px" height="20px" style="margin-right: 5px;" src="/images/close.png">
                </span></th></tr>' .
                '<tr><td class="kick-off-title">Wave Name: </td><td>' . $waveEntity->getName() . '</td></tr>' .
                '<tr><td>PM attendee</td><td>' . $KickOffMeetingEntity->getPmAttendee() . '</td></tr>' .
                '<tr><td>OP attendee</td><td>' . $KickOffMeetingEntity->getOpAttendee() . '</td></tr>' .
                '<tr><td>VA attendee</td><td>' . $KickOffMeetingEntity->getVaAttendee() . '</td></tr>' .
                '<tr><td>QC attendee</td><td>' . $KickOffMeetingEntity->getQcAttendee() . '</td></tr>' .
                '<tr><td>Report attendee</td><td>' . $KickOffMeetingEntity->getReportAttendee() . '</td></tr>' .
                '<tr><td>Brand Positioning, Products categories</td><td>'
                . $KickOffMeetingEntity->getText1() . '</td></tr>' .
                '<tr><td>What are the objectives of this Store Evaluation Program?</td><td>'
                . $KickOffMeetingEntity->getText2() . '</td></tr>' .
                '<tr><td>Key points to pay attention for the report</td><td>'
                . $KickOffMeetingEntity->getText3() . '</td></tr>' .
                '<tr><td>Timeline</td><td>'
                . $KickOffMeetingEntity->getText4() . '</td></tr>' .
                '<tr><td>Scenario/key points for OP surveys screening</td><td>'
                . $KickOffMeetingEntity->getText5() . '</td></tr>' .
                '<tr><td>Key points for the SPE profile and recruitment</td><td>'
                . $KickOffMeetingEntity->getText6() . '</td></tr>' .
                '<tr><td>Training methods</td><td>'
                . $KickOffMeetingEntity->getText7() . '</td></tr>' .
                '<tr><td>Payrate and flexibility/Bonus</td><td>'
                . $KickOffMeetingEntity->getText8() . '</td></tr>' .
                '<tr><td>Key points for the SPE Briefing</td><td>'
                . $KickOffMeetingEntity->getText9() . '</td></tr>' .
                '<tr><td>Debrief of the pilot visit/Go through the Questionnaire</td><td>'
                . $KickOffMeetingEntity->getText10() . '</td></tr>' .
                '<tr><td>Key points for editing/QC</td><td>'
                . $KickOffMeetingEntity->getText11() . '</td></tr>' .
                '<tr><td>Specific requirements: misfires records, proof of visit…</td><td>'
                . $KickOffMeetingEntity->getText12() . '</td></tr></table>';
        return new Response($result);
    }

    //end kick off meeting part/////////////////////////////////////////////////
    protected function getOption($data) {
        $result = '<option value=""></option>';
        foreach ($data as $key => $d) {
            $result .= '<option value="' . $key . '">' . $d . '</option>';
        }
        return $result . '</select>';
    }

    /**
     * Creates a new Customproject entity.
     *
     */
    public function createAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $entity = new Customproject();
        $scopeAndTypeOption = $this->container->getParameter('valuelist');
        $form = $this->createForm(new CustomprojectType(), $scopeAndTypeOption);
        $form->bind($request);
        $data = $form->getData();

        //set name client name_type_scope
        $name = $data['customclient']->getName() . '_';
        $name .= $scopeAndTypeOption['type'][$data['type']] . '_';
        $name .= $scopeAndTypeOption['scope'][$data['scope']];

        $entity->setCustomclient($data['customclient']);
        $entity->setName($name);
        $entity->setScope($data['scope']);
        $entity->setType($data['type']);

        $em->persist($entity);
        $em->flush();
        return $this->redirect($this->generateUrl('customproject'));
    }

    /**
     * Displays a form to create a new Customproject entity.
     *
     */
    public function newAction() {
        $scopeAndTypeOption = $this->container->getParameter('valuelist');

        $form = $this->createForm(new CustomprojectType(), $scopeAndTypeOption);

        return $this->render('AlbatrossCustomBundle:Customproject:new.html.twig', array(
                    'form' => $form->createView(),
                    'current' => 'custom_project',
                    'menu_bar' => 'custom',
                    'menu_cal_cur' => 'project',
        ));
    }

    /**
     * Finds and displays a Customproject entity.
     *
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();
        $secu = $this->container->get('security.context');
        $user = $secu->getToken()->getUser();
        $entity = $em->getRepository('AlbatrossCustomBundle:Customproject')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Customproject entity.');
        }

        $parammeter = $this->container->getParameter('valuelist');
        $language = $parammeter['language'];
        $step = $parammeter['questionnaire'];
        $material = $parammeter['brandmaterial'];
        $deleteForm = $this->createDeleteForm($id);
        $recapform = $this->createForm(new RecapType());
        $waves = $entity->getCustomwave()->toArray();

        if (!empty($waves))
            $invoiceEntity = $this->getInvoiceList($waves);
        else
            $invoiceEntity = array();
        $wavesname = array();
        $lastIOF = array();
        foreach ($waves as $w) {
            $pending = $w->getName();
            $pendingArr = explode('_', $pending);
            $wavesname[$w->getId()][0] = str_replace('w', 'Wave ', $pendingArr[3]);
            $wavesname[$w->getId()][1] = $pendingArr[4];
            if ($w->getAttachments() != null) {
                if ($w->getAttachments()->getChildren()) {
                    $iofID_entity = $this->getLastIOF($w->getAttachments(), $em);
                    $lastIOF[$w->getId()]['id'] = $iofID_entity->getId();
                    $lastIOF[$w->getId()]['status'] = $iofID_entity->getStatus();
                } else {
                    $lastIOF[$w->getId()]['id'] = $w->getAttachments()->getId();
                    $lastIOF[$w->getId()]['status'] = $w->getAttachments()->getStatus();
                }
            }
        }
        $waveNameList = '<table id="waveList"><tr><th colspan="2" style="font-style:italic; font-weight: bold; padding-left:7px; text-align:left;">Quick link:</th></tr>';
        $waveIndex = 1;
        foreach ($wavesname as $key => $wn) {
            $waveNameList .= '<tr><th>' . $waveIndex . ':</th><td><a href="#' . $key . '-table">' . $wn[0] . '-' . $wn[1] . '</a></td></tr>';
            $waveIndex++;
        }
        $waveNameList .= '</table>';
        $projectNamePending = $entity->getName();
        $projectName = str_replace('_', ' ', $projectNamePending);
        if (!empty($waves)) {
            $operation = $this->getProjectOperation($waves);
            $operation_2 = $this->getProjectOperation2($waves);
            $operationInformation = $this->combineTwoArr($operation, $operation_2);
        } else {
            $operationInformation = '';
        }
        return $this->render('AlbatrossCustomBundle:Customproject:show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),
                    'recapform' => $recapform->createView(),
                    'current' => 'custom_project',
                    'menu_bar' => 'custom',
                    'menu_cal_cur' => 'project',
                    'language' => $language,
                    'qstep' => $step,
                    'material' => $material,
                    'wavename' => $wavesname,
                    'projectName' => $projectName,
                    'operation' => $operationInformation,
                    'lastIOF' => $lastIOF,
                    'waveNameList' => $waveNameList,
                    'user' => $user,
                    'invoiceEntity' => $invoiceEntity
        ));
    }

    protected function getLastIOF($entity, $em) {
        if ($child = $em->getRepository('AlbatrossAceBundle:Attachments')->findLastOneByParentID($entity->getId())) {
            $entity = $this->getLastIOF($child, $em);
        }
        return $entity;
    }

    protected function combineTwoArr($operation, $operation_2) {
        $result = array();
        $buArr = array();
        foreach ($operation as $waveId => $projectinfo) {
            foreach ($projectinfo as $buname => $pi) {
                $buArr[$waveId][] = $buname;
            }
        }
        foreach ($operation_2 as $waveId2 => $aolinfo) {
            foreach ($aolinfo as $bname2 => $ai) {
                $buArr[$waveId2][] = $bname2;
            }
        }

        foreach ($buArr as $wid => $bu) {
            $buArr[$wid] = array_unique($bu);
        }

        foreach ($buArr as $wid => $bu) {
            foreach ($bu as $buname) {
                if (isset($operation[$wid][$buname])) {
                    $result[$wid][$buname]['ace'] = $operation[$wid][$buname];
                } else {
                    $result[$wid][$buname]['ace'] = '';
                }
                if (isset($operation_2[$wid][$buname])) {
                    $result[$wid][$buname]['aol'] = $operation_2[$wid][$buname];
                } else {
                    $result[$wid][$buname]['aol'] = '';
                }
            }
        }
        return $result;
    }

    //check the operations if belong to this customproject bu
    protected function getCustomprojectBuByWave($waves) {
        $em = $this->getDoctrine()->getManager();

        foreach ($waves as $wave) {
            if ($projecName = $wave->getCustomproject()->getName())
                break;
        }

        $pNameArr = explode('_', $projecName);
        $bu = $pNameArr[2];
        $notCheckArr = array('WW', 'APAC', 'EU');
        if (in_array($bu, $notCheckArr))
            return '';
        $buEntity = $em->getRepository('AlbatrossAceBundle:Bu')->findOneByCode($bu);
        $buName = $buEntity->getName();
        return $buName;
    }

    //get ace project information
    protected function getProjectOperation($waves) {
        $em = $this->getDoctrine()->getManager();
        $filterBu = $this->getCustomprojectBuByWave($waves);
        $waveArr = array();
        foreach ($waves as $wave) {
            $waveArr[] = $wave->getId();
        }
        $qb = $em->createQueryBuilder();
        $qb->select('p', 't', 'w')
                ->from('AlbatrossAceBundle:Project', 'p')
                ->leftJoin('p.tasks', 't')
                ->leftJoin('p.customwave', 'w');
        $temp = 1;
        foreach ($waveArr as $w) {
            if ($temp == 1) {
                $qb->andWhere(sprintf('w.id=:key_%d', $temp))
                        ->setParameter('key_' . $temp, $w);
            } else {
                $qb->orWhere(sprintf('w.id=:key_%d', $temp))
                        ->setParameter('key_' . $temp, $w);
            }
            $temp++;
        }
        $query = $qb->getQuery();
        $taskResult = $query->getArrayResult();
//==============================================================================
        $buArr = $this->getBuArr();
//==============================================================================
        $taskArr = array();
        $taskBuProjectArr = array();
        $taskPrjNameDuedate = array();
        $taskResultIndex = 0;
        foreach ($taskResult as $r) {
            foreach ($r['tasks'] as $task) {
                if ($task['number'] > 100 && $task['number'] < 117) {
                    $taskArr[] = $task['id'];
                    $taskBuProjectArr[$taskResultIndex]['bu'] = $task['number'] - 100;
                    $taskBuProjectArr[$taskResultIndex]['project'] = $r['id'];
                }
                if ($task['number'] == 600) {
                    $taskPrjNameDuedate[$r['name']] = $task['reportduedate'];
                }
                if (!isset($taskPrjNameDuedate[$r['name']])) {
                    $taskPrjNameDuedate[$r['name']] = '';
                }
            }
            $taskResultIndex++;
        }
//==============================================================================
        $pm_qb = $em->createQueryBuilder();
        $pm_qb->select('pm', 't', 'p')
                ->from('AlbatrossAceBundle:Forecast', 'pm')
                ->leftJoin('pm.task', 't')
                ->leftJoin('t.project', 'p');
        $index = 1;
        foreach ($taskArr as $t) {
            if ($index == 1) {
                $pm_qb->andWhere(sprintf('t.id=:key_%d', $index))
                        ->setParameter('key_' . $index, $t);
            } else {
                $pm_qb->orWhere(sprintf('t.id=:key_%d', $index))
                        ->setParameter('key_' . $index, $t);
            }
            $index++;
        }
        $pm_query = $pm_qb->getQuery();
        $pmResult = $pm_query->getArrayResult();
        $pmFinalResult = array();
        foreach ($pmResult as $p) {
            $pmFinalResult[$p['task']['id']]['project'] = $p['task']['project']['name'];
            $pmFinalResult[$p['task']['id']]['bu'] = $buArr[$p['task']['number'] - 100];
            $pmFinalResult[$p['task']['id']]['scope'] = $p['scope'];
            $pmFinalResult[$p['task']['id']]['fwstartdate'] = $p['fwstartdate']->format('Y-m-d');
            $pmFinalResult[$p['task']['id']]['fwenddate'] = $p['fwenddate']->format('Y-m-d');
            $pmFinalResult[$p['task']['id']]['reportduedate'] = $p['reporttype'] ? $p['reportduetext'] : $p['reportduedate']->format('Y-m-d');
        }
//==============================================================================
        $iof_qb = $em->createQueryBuilder();
        $iof_qb->select('iof', 'a', 'cw', 'b', 'p')
                ->from('AlbatrossAceBundle:Attachinfo', 'iof')
                ->leftJoin('iof.bu', 'b')
                ->leftJoin('iof.project', 'p')
                ->leftJoin('iof.attachments', 'a')
                ->leftJoin('a.customwave', 'cw')
                ->where('a.children = 0')
        ;
        $seq = 1;
        foreach ($waveArr as $w) {
            if ($seq == 1) {
                $iof_qb->andWhere(sprintf('cw.id= :key_%d', $seq))
                        ->setParameter('key_' . $seq, $w);
            } else {
                $iof_qb->orWhere(sprintf('cw.id= :key_%d', $seq))
                        ->setParameter('key_' . $seq, $w);
            }
            $seq++;
        }
        $iof_query = $iof_qb->getQuery();
        $iof_result = $iof_query->getArrayResult();
        //
        //make final result ====================================================
        //
        $result = array();
        foreach ($taskResult as $tr) {
            foreach ($tr['tasks'] as $t) {
                if ($t['number'] > 100 && $t['number'] < 117) {
                    $result[$buArr[$t['number'] - 100]]
                            [$tr['name']]
                            ['fwstartdate'] = $t['fwstartdate'];
                    $result[$buArr[$t['number'] - 100]]
                            [$tr['name']]
                            ['fwenddate'] = $t['fwenddate'];
                    $result[$buArr[$t['number'] - 100]]
                            [$tr['name']]
                            ['step'] = 'ACE';
                    $result[$buArr[$t['number'] - 100]]
                            [$tr['name']]
                            ['scope'] = $t['scope'] ? $t['scope'] : 0;
                    $result[$buArr[$t['number'] - 100]]
                            [$tr['name']]
                            ['number'] = $t['projectnumber'];
                    $result[$buArr[$t['number'] - 100]]
                            [$tr['name']]
                            ['reportduedate'] = $taskPrjNameDuedate[$tr['name']];
                    $result[$buArr[$t['number'] - 100]]
                            [$tr['name']]
                            ['waveid'] = $tr['customwave']['id'];
                    $result[$buArr[$t['number'] - 100]]
                            [$tr['name']]
                            ['wavename'] = $tr['customwave']['name'];
                }
            }
        }
        foreach ($pmFinalResult as $pm) {
            if (isset($result[$pm['bu']]) && isset($result[$pm['bu']][$pm['project']])) {
                $result[$pm['bu']][$pm['project']]['fwstartdate'] = $pm['fwstartdate'];
                $result[$pm['bu']][$pm['project']]['fwenddate'] = $pm['fwenddate'];
                $result[$pm['bu']][$pm['project']]['scope'] = $pm['scope'];
                $result[$pm['bu']][$pm['project']]['step'] = 'PM';
                $result[$pm['bu']][$pm['project']]['reportduedate'] = $pm['reportduedate'];
            }
        }

        foreach ($iof_result as $iof) {
            if (isset($result[$iof['bu']['name']]
                            [$iof['project']['name']])) {
                $result[$iof['bu']['name']]
                        [$iof['project']['name']]
                        ['fwstartdate'] = $iof['fwstartdate']->format('Y-m-d');
                $result[$iof['bu']['name']]
                        [$iof['project']['name']]
                        ['fwenddate'] = $iof['fwenddate']->format('Y-m-d');
                $result[$iof['bu']['name']]
                        [$iof['project']['name']]
                        ['step'] = 'IOF';
                $result[$iof['bu']['name']]
                        [$iof['project']['name']]
                        ['scope'] = $iof['scope'];
                if (!$iof['reporttype']) {
                    $result[$iof['bu']['name']]
                            [$iof['project']['name']]
                            ['reportduedate'] = $iof['reportduedate']->format('Y-m-d');
                } else if ($iof['reporttype']) {
                    $result[$iof['bu']['name']]
                            [$iof['project']['name']]
                            ['reportduedate'] = $iof['reportduedatetext'];
                }
            }
        }
        $final = array();
        foreach ($result as $k => $r) {
            if ($filterBu == '' || $filterBu == $k) {
                foreach ($r as $pname => $info) {
                    if (isset($info['waveid'])) {
                        if ($info['number'] != '') {
                            $final[$info['waveid']][$k][$pname] = $info;
                        }
                    } else {
                        $final = array();
                    }
                }
            }
        }
        return $final;
    }

    protected function getProjectOperation2($waves) {
        $campaign = array();
        $final = array();
        $filterBu = $this->getCustomprojectBuByWave($waves);
        foreach ($waves as $wave) {
            $campaign[$wave->getId()] = $wave->getCampaign()->toArray();
        }
        $status_Total = array(
            'Declined',
            'Open Opportunities - No Applications',
            'Open Opportunities - With Applications');
        $status_Assigned = array(
            'Assigned - Completed not yet submitted',
            'Assigned - In "Working" status',
            'Assigned - Returned Completely',
            'Assigned (Accepted where Acceptance is Required)');
        $statusFWdone = array(
            'On Hold - General',
            'Validation - After Return',
            'Validation - In Progress',
            'Validation - Pending');
        $statusValidation = array(
            'Completed - Pending Export',
            'Completed - RFA(s) closed',
            'Completed - RFA(s) open',
            'Completed Exported',
            'Hide from Reports; Hide from Client Survey Explorer',
            'Hide from Reports; OK for Client Survey Explorer',
            'Completed - Export Failed');
        foreach ($campaign as $waveid => $waveC) {
            foreach ($waveC as $obj) {
                $survey = $obj->getAolsurvey()->toArray();

                foreach ($survey as $surveykey => $s) {
                    if (!is_object($s->getLocation()->getCountry())) {
                        var_dump($s->getLocation()->getLocCountryCode());
                        exit();
                    }
                    if ($s->getMailboxName() != 'mdelete' && $s->getMailboxName() != 'invalidsurvey') {
                        if (!isset($final[$waveid][$s->getLocation()->getCountry()->getBu()->getName()]['total']))
                            $final[$waveid][$s->getLocation()->getCountry()->getBu()->getName()]['total'] = 0;
                        if (!isset($final[$waveid][$s->getLocation()->getCountry()->getBu()->getName()]['assign']))
                            $final[$waveid][$s->getLocation()->getCountry()->getBu()->getName()]['assign'] = 0;
                        if (!isset($final[$waveid][$s->getLocation()->getCountry()->getBu()->getName()]['fwdone']))
                            $final[$waveid][$s->getLocation()->getCountry()->getBu()->getName()]['fwdone'] = 0;
                        if (!isset($final[$waveid][$s->getLocation()->getCountry()->getBu()->getName()]['validation']))
                            $final[$waveid][$s->getLocation()->getCountry()->getBu()->getName()]['validation'] = 0;
                        if (in_array($s->getSurveyStatusName(), $statusValidation)) {
                            $final[$waveid][$s->getLocation()->getCountry()->getBu()->getName()]['validation']++;
                            $final[$waveid][$s->getLocation()->getCountry()->getBu()->getName()]['fwdone']++;
                            $final[$waveid][$s->getLocation()->getCountry()->getBu()->getName()]['assign']++;
                            $final[$waveid][$s->getLocation()->getCountry()->getBu()->getName()]['total']++;
                        } else if (in_array($s->getSurveyStatusName(), $statusFWdone)) {
                            $final[$waveid][$s->getLocation()->getCountry()->getBu()->getName()]['fwdone']++;
                            $final[$waveid][$s->getLocation()->getCountry()->getBu()->getName()]['assign']++;
                            $final[$waveid][$s->getLocation()->getCountry()->getBu()->getName()]['total']++;
                        } else if (in_array($s->getSurveyStatusName(), $status_Assigned)) {
                            $final[$waveid][$s->getLocation()->getCountry()->getBu()->getName()]['assign']++;
                            $final[$waveid][$s->getLocation()->getCountry()->getBu()->getName()]['total']++;
                        } else if (in_array($s->getSurveyStatusName(), $status_Total)) {
                            $final[$waveid][$s->getLocation()->getCountry()->getBu()->getName()]['total']++;
                        }
                        $final[$waveid][$s->getLocation()->getCountry()->getBu()->getName()]['aolname'][] = $obj->getQuestionnaire()->getName();
                        $final[$waveid][$s->getLocation()->getCountry()->getBu()->getName()]['campaign'][] = $obj->getName();
                        $final[$waveid][$s->getLocation()->getCountry()->getBu()->getName()]['num'] = $final[$waveid][$s->getLocation()->getCountry()->getBu()->getName()]['total'];
                    }
                }
            }
        }
        foreach ($final as $key => $f) {
            foreach ($f as $k => $unique) {
                if ($filterBu == '' || $filterBu == $k) {
                    $final[$key][$k]['aolname'] = array_unique($unique['aolname']);
                    $final[$key][$k]['campaign'] = array_unique($unique['campaign']);
                    $final[$key][$k]['assignPercent'] = floor(($unique['assign'] / $unique['total']) * 100) . '% (' . $unique['assign'] . ')';
                    $final[$key][$k]['fwdonePercent'] = floor(($unique['fwdone'] / $unique['total']) * 100) . '% (' . $unique['fwdone'] . ')';
                    $final[$key][$k]['validationPercent'] = floor(($unique['validation'] / $unique['total']) * 100) . '% (' . $unique['validation'] . ')';
                } else {
                    unset($final[$key][$k]);
                }
            }
        }
        return $final;
    }

    public function recapGetAolquestionnaireAction() {
        $em = $this->getDoctrine()->getManager();
        $data = $this->getRequest()->getContent();
        //set Aol Questionnaire selection
        $aolqb = $em->createQueryBuilder();
        $aolqb->select('a')
                ->from('AlbatrossCustomBundle:Aolquestionnaire', 'a')
                ->leftJoin('a.customfield', 'cf')
                ->leftJoin('cf.customwave', 'cw')
                ->where('cw.id = :cwid');
        $aolqb->setParameters(array(
            'cwid' => $data,
        ));
        $aolquery = $aolqb->getQuery();
        $aolEntitys = $aolquery->getArrayResult();
        $result = '<table style="border-collapse: collapse;">';
        $index = 0;
        if (empty($aolEntitys)) {
            $result = 'No aol questionnaire.';
        } else {
            foreach ($aolEntitys as $aol) {
                $index++;
                if ($index % 3 == 0)
                    $result .= '<td><input type="checkbox" class="recap_form_input" name="aolquestionnaire[]" value="' . $aol['id'] . '">' . $aol['name'] . '</td></tr><tr>';
                else
                    $result .= '<td><input type="checkbox" class="recap_form_input" name="aolquestionnaire[]" value="' . $aol['id'] . '">' . $aol['name'] . '</td>';
            }
        }

        return new Response($result);
    }

    //get Country from updated poslist country
    public function recapGetCountryAction() {
        $data = $this->getRequest()->getContent();
        $poslistdataEntities = $this->getAllPosCountry($data);

        $selectedCountry = $this->getRecapSeletedCountry($data);

        $result = '<table style="border-collapse: collapse;">';
        $countryArr = array();
        $index = 0;
        if (!empty($poslistdataEntities)) {
            foreach ($poslistdataEntities as $c) {
                $countryArr[$c->getCountry()->getId()] = $c->getCountry()->getName();
            }
            foreach ($countryArr as $k => $c) {
                $index++;
                if (isset($selectedCountry[$k])) {
                    if ($index % 3 == 0)
                        $result .= '<td><input type="checkbox" disabled="disabled" class="recap_form_input" name="recapcountry[]" value="' . $k . '"><font color="gray">' . $c . '</font></td></tr><tr>';
                    else
                        $result .= '<td><input type="checkbox" disabled="disabled" class="recap_form_input" name="recapcountry[]" value="' . $k . '"><font color="gray">' . $c . '</font></td>';
                }else {
                    if ($index % 3 == 0)
                        $result .= '<td><input type="checkbox" class="recap_form_input" name="recapcountry[]" value="' . $k . '">' . $c . '</td></tr>';
                    else
                        $result .= '<td><input type="checkbox" class="recap_form_input" name="recapcountry[]" value="' . $k . '">' . $c . '</td>';
                }
            }
            if ($index % 3 == 0) {
                rtrim($result, '<tr>');
                $result .= '</table>';
            } else if ($index % 2 == 0 && $index % 3 != 0) {
                $result .= '<td></td></tr></table>';
            } else {
                $result .= '<td></td><td></td></tr></table>';
            }
        } else {
            $result = 'No country info from POS list.';
        }

        return new Response($result);
    }

    protected function getRecapSeletedCountry($cid) {
        $em = $this->getDoctrine()->getManager();
        $recapEntityQb = $em->createQueryBuilder();
        $recapEntityQb->select('re')
                ->from('AlbatrossCustomBundle:Recap', 're')
                ->leftJoin('re.customwave', 'cw')
                ->where('cw.id = :cwid');
        $recapEntityQb->setParameters(array(
            'cwid' => $cid
        ));
        $recapEntityQuery = $recapEntityQb->getQuery();
        $recapEntity = $recapEntityQuery->getResult();
        $selectedCountry = array();
        if (!empty($recapEntity)) {
            foreach ($recapEntity as $re) {
                $countryEntity = $re->getCountry()->toArray();
                foreach ($countryEntity as $ce) {
                    $selectedCountry[$ce->getId()] = $ce->getName();
                }
            }
        }
        return $selectedCountry;
    }

    protected function getAllPosCountry($cid) {
        $em = $this->getDoctrine()->getManager();
        $poslistdataEntityQb = $em->createQueryBuilder();
        $poslistdataEntityQb->select('pd')
                ->from('AlbatrossCustomBundle:Poslistdata', 'pd')
                ->leftJoin('pd.poslist', 'p')
                ->leftJoin('p.customwave', 'cw')
                ->where('cw.id = :cwid');
        $poslistdataEntityQb->setParameters(array(
            'cwid' => $cid
        ));
        $poslistdataEntityQuery = $poslistdataEntityQb->getQuery();
        $poslistdataEntity = $poslistdataEntityQuery->getResult();

        return $poslistdataEntity;
    }

    public function getGantChartAction($id, $bu, $project) {

        $em = $this->getDoctrine()->getManager();

        $qb = $em->createQueryBuilder();
        $qb->add('select', 'p, t')
                ->add('from', 'AlbatrossAceBundle:Task t')
                ->leftJoin('t.project', 'p')
                ->leftJoin('p.customwave', 'w')
                ->where('w.id = :customwave_id')
                ->andWhere('t.number BETWEEN 101 AND 116');
        $qb->setParameters(array(
            'customwave_id' => $id
        ));
        $tasks = $qb->getQuery()->getArrayResult();

        if (!empty($tasks)) {

            $buArr = $this->getBuArr();

            $dataArr = array();

            $projectSelect = '<select id="projectSelect">';
            $buSelect = '<select id="buSelect">';
            foreach ($tasks as $key => $ai) {
                $pid = $ai['project']['id'];
                $bid = (string) ($ai['number'] - 100);
                if (!isset($dataArr[$pid])) {
                    $projectSelect .= '<option value="' . $pid . '">' . $ai['project']['name'] . '</option>';
                }
                $dataArr[$pid][$bid]['start'] = $ai['fwstartdate'];
                $dataArr[$pid][$bid]['end'] = $ai['fwenddate'];

                if ($key == 0 && $project == '' && $bu == '') {
                    $project = $ai['project']['id'];
                }
            }

            $count = 0;
            foreach ($dataArr[$project] as $k => $d) {
                if ($count == 0 && ($bu == 'null' || $bu == '')) {
                    $bu = $k;
                }
                $buSelect .= '<option value="' . $k . '">' . $buArr[$k] . '</option>';
                $count++;
            }

            $start = $dataArr[$project][$bu]['start'];
            $end = $dataArr[$project][$bu]['end'];

            $data = $this->getGantChartData($start, $end, $id);

            $projectSelect .= '</select>';
            $buSelect .= '</select>';
        } else {
            $data = $this->getGantChartData('', '', $id);
            $projectSelect = '<select></select>';
            $buSelect = '<select></select>';
        }
        $gantti = new \Gantti($data, array(
            'title' => 'Gantt Chart',
            'cellwidth' => 10,
            'cellheight' => 20
        ));

        return $this->render('AlbatrossCustomBundle:Customproject:gantchart.html.twig', array(
                    'gantti' => $gantti,
                    'projectSelect' => $projectSelect,
                    'buSelect' => $buSelect,
                    'wid' => $id,
                    'proj' => $project,
                    'bu' => $bu
        ));
    }

    /**
     * Displays a form to edit an existing Customproject entity.
     *
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AlbatrossCustomBundle:Customproject')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Customproject entity.');
        }
        $scopeAndTypeOption = $this->container->getParameter('valuelist');
        $editForm = $this->createForm(new CustomprojectType(), $scopeAndTypeOption);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('AlbatrossCustomBundle:Customproject:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
                    'current' => 'custom_project',
                    'menu_bar' => 'custom',
                    'menu_cal_cur' => 'project',
        ));
    }

    /**
     * Edits an existing Customproject entity.
     *
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AlbatrossCustomBundle:Customproject')->find($id);
        $scopeAndTypeOption = $this->container->getParameter('valuelist');
        $form = $this->createForm(new CustomprojectType(), $scopeAndTypeOption);
        $form->bind($request);
        $data = $form->getData();

        //set name client name_type_scope
        $name = $data['customclient']->getName() . '_';
        $name .= $scopeAndTypeOption['type'][$data['type']] . '_';
        $name .= $scopeAndTypeOption['scope'][$data['scope']];

        $entity->setCustomclient($data['customclient']);
        $entity->setName($name);
        $entity->setScope($data['scope']);
        $entity->setType($data['type']);

        $em->persist($entity);
        $em->flush();
        return $this->redirect($this->generateUrl('customproject'));
    }

    //follow the project use Ajax
    public function followProjectAction($id){
        $user = $this->getCurUser();
        $em = $this->getDoctrine()->getManager();
        $customProjectEntity = $em->getRepository('AlbatrossCustomBundle:Customproject')->find($id);
        $user->addCustomproject($customProjectEntity);
        $em->persist($user);
        $em->flush();
        return new Response('Followed success');
    }
    /**
     * Deletes a Customproject entity.
     *
     */
    public function deleteAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AlbatrossCustomBundle:Customproject')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Customproject entity.');
        }
        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('customproject'));
    }

    public function checkWaveBindToProjectAction() {
        $em = $this->getDoctrine()->getManager();
        $id = $this->getRequest()->getContent();

        $customprojectEntity = $em->getRepository('AlbatrossCustomBundle:Customproject')->find($id);
        $customwaveEntity = $customprojectEntity->getCustomwave()->toArray();
        $count = count($customwaveEntity);
        return new Response($count);
    }

    /**
     * Creates a form to delete a Customproject entity by id.
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

    public function saverecapAction(Request $request) {
        $entity = new Recap();

        $form = $this->createForm(new RecapType(), $entity);

        $form->bindRequest($request);

        $secu = $this->container->get('security.context');
        $user = $secu->getToken()->getUser();

        $aolquestionnaire = $request->get('aolquestionnaire');
        $recapcountry = $request->get('recapcountry');

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->getActual()->setType('Actual');
            $entity->getPlanned()->setType('Planned');

            if (!empty($aolquestionnaire)) {
                foreach ($aolquestionnaire as $aol) {
                    $aolquestEntity = $em->getRepository('AlbatrossCustomBundle:Aolquestionnaire')->find($aol);
                    $entity->addAolquestionnaire($aolquestEntity);
                    $aolquestEntity->addRecap($entity);
                    $em->persist($aolquestEntity);
                }
            }

            if (!empty($recapcountry)) {
                foreach ($recapcountry as $country) {
                    $countryEntity = $em->getRepository('AlbatrossAceBundle:Country')->find($country);
                    $entity->addCountry($countryEntity);
                    $countryEntity->addRecap($entity);

                    $em->persist($countryEntity);
                }
            }

            $entity->setSubmittime(date('Y-m-d', time()));
            $entity->setUser($user);
            $em->persist($entity);
            $em->flush();

            if ($this->estimateCountry($entity->getCustomwave()->getId())) {
                $entity->setCountryType('0');
            } else {
                $entity->setCountryType('1');
            }
            $em->persist($entity);
            $em->flush();

            $referer = $this->getRequest()->headers->get('referer');
            return $this->redirect($referer);
        }

        return $this->redirect($this->generateUrl('customproject'));
    }

    protected function estimateCountry($cid) {
        $seletedCountry = $this->getRecapSeletedCountry($cid);
        $poslistdataEntities = $this->getAllPosCountry($cid);
        $countryArr = array();
        if (!empty($poslistdataEntities)) {
            foreach ($poslistdataEntities as $c) {
                $countryArr[$c->getCountry()->getId()] = $c->getCountry()->getName();
            }
        }
        if (count($seletedCountry) == count($countryArr)) {
            return true;
        } else {
            return false;
        }
    }

    public function recapformAction($projName) {
        $recapform = $this->createForm(new RecapType());
        return $this->render('AlbatrossCustomBundle:Customproject:recapform.html.twig', array(
                    'recapform' => $recapform->createView(),
                    'projname' => $projName
        ));
    }

    public function fieldformAction($projName) {
        $language = $this->container->getParameter('valuelist');
        $fieldform = $this->createForm(new CustomfieldType(), null, array('language' => $language));
        $render = 'AlbatrossCustomBundle:Customproject:fieldform.html.twig';
        return $this->render($render, array(
                    'fieldform' => $fieldform->createView(),
                    'projname' => $projName
        ));
    }

    public function saveFieldAction($type) {

        $secu = $this->container->get('security.context');
        $user = $secu->getToken()->getUser();

        $language = $this->container->getParameter('valuelist');
        $entity = new Customfield();

        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(new CustomfieldType(), $entity, array('language' => $language));

        $form->bindRequest($this->getRequest());

        $waveEntity = $form->getData()->getCustomwave();

        $referer = $this->getRequest()->headers->get('referer');
        if ($form->isValid()) {

            $this->checkLastKaWave($waveEntity);

            $entity->upload($type);
            $entity->setFieldtype($type);
            $entity->setSubmittime(date('Y-m-d'));
            $entity->setPath($entity->getWebPath($type));
            $entity->setUser($user);

            if ($type == 'questionnaire') {
                $entity->setQuestionStatus(1);

                $entity->setPath2($entity->getWebPath2($type));
                $entity->setPath3($entity->getWebPath3($type));
                $entity->setPath4($entity->getWebPath4($type));
            }
            if ($type == 'mm') {
                if ($entity->getAttendees()) {
                    foreach ($entity->getAttendees() as $attendees)
                        $attendees->setCustomfield($entity);
                }
            }
            $em->persist($entity);
            $em->flush();
            return $this->redirect($referer);
        }
    }

    protected function checkLastKaWave($waveEntity) {
        $fieldArr = $waveEntity->getCustomfield()->toArray();

        if (empty($fieldArr)) {
            $em = $this->getDoctrine()->getManager();
            $waveEntityArrInSameProject = $waveEntity->getCustomproject()->getCustomwave()->toArray();
            $checkPreviousLastWave = 0;
            foreach ($waveEntityArrInSameProject as $wave) {

                if ($waveEntity->getId() != $wave->getId()) {
                    $attach = $wave->getAttachments();
                    $field = $wave->getCustomfield()->toArray();
                    $invoice = $wave->getInvoice()->toArray();
                    $meetingRecap = $wave->getMeetingRecap();
                    if (!empty($attach) || !empty($field) || !empty($invoice) || !empty($meetingRecap)) {
                        if (($waveEntity->getYear() > $wave->getYear()) ||
                                (($waveEntity->getYear() == $wave->getYear()) && ($waveEntity->getMonth() > $wave->getMonth())) ||
                                (($waveEntity->getYear() == $wave->getYear()) && ($waveEntity->getMonth() == $wave->getMonth()) && $waveEntity->getWavenum() > $wave->getWavenum())) {
                            $wave->setLastStart(0);
                        } else {
                            $checkPreviousLastWave = 1;
                        }
                        $em->persist($wave);
                    }
                }
            }
            if ($checkPreviousLastWave != 1) {
                $waveEntity->setLastStart(1);
                $em->persist($waveEntity);
            }
            $em->flush();
        }

        return;
    }

    // public function moveProjectManagerToWaveFromReportDeliveryScheduleTableAction(){
    //     $em = $this->getDoctrine()->getManager();
    //     $reportDeliveryScheduleEntityArr = $em->getRepository('AlbatrossCustomBundle:ReportDeliverySchedule')->findAll();

    //     foreach ($reportDeliveryScheduleEntityArr as $rds) {
    //         $pmEntity = $rds->getProjectManager();
    //         if(!empty($pmEntity)){
    //             if(!is_object($rds->getCustomproject())){
    //                 var_dump($rds->getId());
    //                 exit();
    //             }
    //             $waveArr = $rds->getCustomproject()->getCustomwave();
    //             $haha = 0;
    //             foreach ($waveArr as $wave) {
    //                 if($wave->getLastStart() == 1){
    //                     $wave->setProjectManager($pmEntity);
    //                     $haha = 1;
    //                     $em->persist($wave);
    //                 }
    //             }
    //             if($haha == 0){
    //                 var_dump($rds->getCustomproject()->getId());
    //             }
    //         }
    //     }
    //     $em->flush();
    //     var_dump('done');
    //     exit();
    // }
    // protected function checkLastKaWavebasedOnProject($pEntity){
    //     $em = $this->getDoctrine()->getManager();
    //     $waveArr = $pEntity->getCustomwave()->toArray();
    //     foreach ($waveArr as $wave) {
    //         $attach = $wave->getAttachments();
    //         $field = $wave->getCustomfield()->toArray();
    //         $invoice = $wave->getInvoice();
    //         $meetingRecap = $wave->getMeetingRecap();
    //         if (!empty($attach) || !empty($field) || !empty($invoice) || !empty($meetingRecap)) {
    //             $hasFileArr[$pEntity->getId()][] = $wave;
    //         }
    //         $allArr[$pEntity->getId()][] = $wave;
    //     }

    //     $lastWaveArr = array();
    //     foreach ($allArr as $key => $all) {
    //         if (!empty($hasFileArr[$key])) {
    //             $checkBiggestNum = array('num' => 0, 'year' => 0, 'month' => 0);
    //             foreach ($hasFileArr[$key] as $k => $waveHasFile) {
    //                 if (($waveHasFile->getYear() > $checkBiggestNum['year']) ||
    //                         ( ($waveHasFile->getYear() == $checkBiggestNum['year']) && ($waveHasFile->getMonth() > $checkBiggestNum['month']) ) ||
    //                         ( ($waveHasFile->getYear() == $checkBiggestNum['year']) && ($waveHasFile->getMonth() == $checkBiggestNum['month']) && ($waveHasFile->getWavenum() > $checkBiggestNum['num']) )) {
    //                     $checkBiggestNum['num'] = $waveHasFile->getWavenum();
    //                     $checkBiggestNum['year'] = $waveHasFile->getYear();
    //                     $checkBiggestNum['month'] = $waveHasFile->getMonth();
    //                     $lastWaveArr[$key] = $waveHasFile;
    //                 }
    //             }
    //         }
    //     }
    //     foreach ($lastWaveArr as $k => $waveHaveToSave) {
    //         $waveHaveToSave->setLastStart(1);
    //         $em->persist($waveHaveToSave);
    //     }
    //     $em->flush();
    //     return;
    // }
    public function getFieldInfoAction() {
        $data = $this->getRequest()->getContent();
        $dataArr = explode(':', $data);
        $id = $dataArr[0];
        $type = $dataArr[1];

        $secu = $this->container->get('security.context');
        $user = $secu->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('f, c, u, w, p, a, aolquestionnaire')
                ->from('AlbatrossCustomBundle:Customfield', 'f')
                ->leftJoin('f.country', 'c')
                ->leftJoin('f.aolquestionnaire', 'aolquestionnaire')
                ->leftJoin('f.user', 'u')
                ->leftJoin('f.customwave', 'w')
                ->leftJoin('w.customproject', 'p')
                ->leftJoin('f.attendees', 'a')
                ->where('f.id = :fid');
        $qb->setParameters(array(
            'fid' => $id
        ));
        $query = $qb->getQuery();
        $resultArr = $query->getArrayResult();
        $result = $resultArr[0];
        $request = $this->getRequest();
        $html = '';
        $parammeter = $this->container->getParameter('valuelist');
        if ($type == 'report') {
            $reportTypeArr = array('Flash SPE', 'SPE', 'SPA');
            $reportType = $result['report_type'] ? $reportTypeArr[$result['report_type']] : 'Empty';
            $zone = $result['report_zone'] ? $result['report_zone'] : 'No Zone';
            $executive = $result['report_executive'] ? 'Yes' : 'No';
            $countryStr = '';
            if (!empty($result['country'])) {
                foreach ($result['country'] as $c) {
                    $countryStr .= $c['name'] . ', ';
                }
            } else {
                $countryStr = 'No Selected Country';
            }
            $countryStrTrim = trim($countryStr, ', ');
        } elseif ($type == 'brief') {
            $languageC = $this->container->getParameter('valuelist');
            $language = $languageC['language'];

            if ($result['brief_translation'] == null || $result['brief_translation'] == '')
                $lang = 'All';
            else
                $lang = $language[$result['brief_translation']];
        } elseif ($type == 'mm') {
            $attStr = '';
            if (!empty($result['attendees'])) {
                $attStr = '<ul>';
                foreach ($result['attendees'] as $a) {
                    if ($a['albatross_attendees'])
                        $attStr .= '<li>Albatross - Name:' . $a['name'] . ' Position:' . $a['position'] . '</li>';
                    else
                        $attStr .= '<li>Name:' . $a['name'] . ' Position:' . $a['position'] . '</li>';
                }
                $attStr .= '</ul>';
            }
        }elseif ($type == 'questionnaire') {
            $steplist = $parammeter['questionnaire'];
            $step = $result['question_status'];
            if ($step == 4) {
                if ($result['choosen_type'] == 1) {
                    $step = 10;
                } elseif ($result['choosen_type'] == 2) {
                    $step = 11;
                }
            }

            //check how many files uploaded
            $fileNum = array();

            $otherFile = '';
            $v1 = ($result['questionnaire_version_num_1'] == null) ? ' V1' : ' V' . $result['questionnaire_version_num_1'];
            $v2 = ($result['questionnaire_version_num_2'] == null) ? ' V1' : ' V' . $result['questionnaire_version_num_2'];
            $v3 = ($result['questionnaire_version_num_3'] == null) ? ' V1' : ' V' . $result['questionnaire_version_num_3'];
            $v4 = ($result['questionnaire_version_num_4'] == null) ? ' V1' : ' V' . $result['questionnaire_version_num_4'];
            if ($result['path']) {
                $fileNum = $fileNum + array($result['question_file1_label'] => 1);
            }
            if ($result['path_2'] != null) {
                $otherFile .= '<td><form class="question_down_file" action = "' . $request->getBaseUrl() . '/Customproject/downloadFile/' . $result['id'] . '/2"><input class="file-button-questionnaire-down" type="submit" title="' . $result['question_file2_label'] . $v2 . '" value="' . $result['question_file2_label'] . $v2 . '"/></form></td>';
                $fileNum = $fileNum + array($result['question_file2_label'] => 2);
            } else {
                $otherFile .= '<td></td>';
            }
            if ($result['path_3'] != null) {
                $otherFile .= '<td><form class="question_down_file" action = "' . $request->getBaseUrl() . '/Customproject/downloadFile/' . $result['id'] . '/3"><input class="file-button-questionnaire-down" type="submit" title="' . $result['question_file3_label'] . $v3 . '" value="' . $result['question_file3_label'] . $v3 . '"/></form></td>';
                $fileNum = $fileNum + array($result['question_file3_label'] => 3);
            } else {
                $otherFile .= '<td></td>';
            }
            if ($result['path_4'] != null) {
                $otherFile .= '<td><form class="question_down_file" action = "' . $request->getBaseUrl() . '/Customproject/downloadFile/' . $result['id'] . '/4"><input class="file-button-questionnaire-down" type="submit" title="' . $result['question_file4_label'] . $v4 . '" value="' . $result['question_file4_label'] . $v4 . '"/></form></td>';
                $fileNum = $fileNum + array($result['question_file4_label'] => 4);
            } else {
                $otherFile .= '<td></td>';
            }
            $otherFile .= '</tr></table>';
        } elseif ($type == 'dic') {
            $diccountryStr = '';
            if (!empty($result['country'])) {
                foreach ($result['country'] as $c) {
                    $diccountryStr .= $c['name'] . ', ';
                }
            } else {
                $diccountryStr = 'No Selected Country';
            }
            $diccountryStrTrim = trim($diccountryStr, ', ');
        } elseif ($type == 'material') {
            $materialname = $parammeter['brandmaterial'];
        }
        $titleStyle = 'font-size:20px; color:white; background-color:#2980b9; height: 37px';
        $closeButton = '<span id="fieldform_title_span"></span><span id="close_button" title="close" onclick="closedFieldInfo();" style=" position: absolute; right:0;"><img src="/images/close.png" height="30px" width="30px" style="margin-right: 5px;"></span>';
        switch ($type) {
            case 'report':
                $html = '<tr><th colspan="2" style="' . $titleStyle . '">Report Info' . $closeButton . '</th></tr>' .
                        '<tr><th>Project Name</th><td>' . $result['customwave']['customproject']['name'] . '</td></tr>' .
                        '<tr><th>Wave Name</th><td>' . $result['customwave']['name'] . '</td></tr>' .
                        '<tr><th>Submit Time</th><td>' . $result['submittime'] . '</td></tr>' .
                        '<tr><th>Submit User</th><td>' . $result['user']['username'] . '</td></tr>' .
                        '<tr><th>Report Type</th><td>' . $reportType . '</td></tr>' .
                        '<tr><th>Executive</th><td>' . $executive . '</td></tr>' .
                        '<tr><th>Zone</th><td>' . $zone . '</td></tr>' .
                        '<tr><th>Country</th><td>' . $countryStrTrim . '</td></tr>';
                if ($secu->isGranted('ROLE_ADMIN') || $secu->isGranted('ROLE_SENIOR_PROJECT_MANAGER') ||
                        $secu->isGranted('ROLE_PROJECT_MANAGER') || $secu->isGranted('ROLE_BU_MANAGER') ||
                        $user->getPosition()->getName() == 'Top Management' || $user->getPosition()->getName() == 'Market Research Dept') {
                    $html .= '<tr><td colspan="2"><form action = "' . $request->getBaseUrl() . '/Customproject/downloadFile/' . $result['id'] . '"><input type="submit" value="download"/></form></td></tr>';
                }
                break;
            case 'brief':
                $html = '<tr><th colspan="2" style="' . $titleStyle . '">SPE Brief' . $closeButton . '</th></tr>' .
                        '<tr><th>Project Name</th><td>' . $result['customwave']['customproject']['name'] . '</td></tr>' .
                        '<tr><th>Wave Name</th><td>' . $result['customwave']['name'] . '</td></tr>' .
                        '<tr><th>Submit Time</th><td>' . $result['submittime'] . '</td></tr>' .
                        '<tr><th>Submit User</th><td>' . $result['user']['username'] . '</td></tr>' .
                        '<tr><th>Main Brief</th><td>' . $result['main_brief'] . '</td></tr>' .
                        '<tr><th>Translation</th><td>' . $lang . '</td></tr>' .
                        '<tr><td colspan="2"><form action = "' . $request->getBaseUrl() . '/Customproject/downloadFile/' . $result['id'] . '"><input type="submit" value="download"/></form></td></tr>';
                break;
            case 'material':
                $html = '<tr><th colspan="2" style="' . $titleStyle . '">Brand Material Info' . $closeButton . '</th></tr>' .
                        '<tr><th>Project Name</th><td>' . $result['customwave']['customproject']['name'] . '</td></tr>' .
                        '<tr><th>Wave Name</th><td>' . $result['customwave']['name'] . '</td></tr>' .
                        '<tr><th>Submit Time</th><td>' . $result['submittime'] . '</td></tr>' .
                        '<tr><th>Submit User</th><td>' . $result['user']['username'] . '</td></tr>' .
                        '<tr><th>Brand Material Name</th><td>' . $materialname[$result['material_name']] . '</td></tr>' .
                        '<tr><td colspan="2"><form action = "' . $request->getBaseUrl() . '/Customproject/downloadFile/' . $result['id'] . '"><input type="submit" value="download"/></form></td></tr>';
                break;
            case 'mm':
                $html = '<tr><th colspan="2" style="' . $titleStyle . '">Meeting Minutes' . $closeButton . '</th></tr>' .
                        '<tr><th>Project Name</th><td>' . $result['customwave']['customproject']['name'] . '</td></tr>' .
                        '<tr><th>Wave Name</th><td>' . $result['customwave']['name'] . '</td></tr>' .
                        '<tr><th>Submit Time</th><td>' . $result['submittime'] . '</td></tr>' .
                        '<tr><th>Submit User</th><td>' . $result['user']['username'] . '</td></tr>' .
                        '<tr><th>Brand</th><td>' . $result['mm_brand'] . '</td></tr>' .
                        '<tr><th>Date</th><td>' . $result['mm_date'] . '</td></tr>' .
                        '<tr><th>Address</th><td>' . $result['mm_address'] . '</td></tr>' .
                        '<tr><th>Attendees</th><td>' . $attStr . '</td></tr>' .
                        '<tr><th>Purpose</th><td>' . $result['mm_purpose'] . '</td></tr>' .
                        '<tr><th>Next Step</th><td>' . $result['mm_nextstep'] . '</td></tr>' .
                        '<tr><th>Agenda of the meeting</th><td>' . $result['mm_agenda_of_the_meeting'] . '</td></tr>' .
                        '<tr><th>Client\'s feedback</th><td>' . $result['mm_clients_feedback'] . '</td></tr>' .
                        '<tr><th>Comments</th><td>' . $result['mm_comments'] . '</td></tr>';
                break;
            case 'questionnaire':
                $closeQuestionnaireButton = '<span id="fieldform_title_span"></span><span id="close_button" title="close" onclick="closedQuestionnaireFieldInfo();" style=" position: absolute; right:0;"><img src="/images/close.png" height="30px" width="30px" style="margin-right: 5px;"></span>';
                $language = array(
                    0 => '',
                    1 => 'French',
                    2 => 'Spanish',
                    3 => 'Italian',
                    4 => 'German',
                    5 => 'Korean',
                    6 => 'Japanese',
                    7 => 'Portuguese',
                    8 => 'Russian',
                    9 => 'traditional Chinese',
                    10 => 'Simplified Chinese',
                    11 => 'Thai'
                );
                $html = '<tr><th colspan="2" style="' . $titleStyle . '">Questionnaire' . $closeQuestionnaireButton . '</th></tr>' .
                        '<tr><th>Project Name</th><td>' . $result['customwave']['customproject']['name'] . '</td></tr>' .
                        '<tr><th>Wave Name</th><td>' . $result['customwave']['name'] . '</td></tr>' .
                        '<tr><th>Submit Time</th><td>' . $result['submittime'] . '</td></tr>' .
                        '<tr><th>Step</th><td>' . $steplist[$step] . '</td></tr>';
                //set three tick option value
                if ($result['questionnaire_new_tick']) {
                    $newTick = 'YES';
                } else {
                    $newTick = 'NO';
                }
                if ($result['questionnaire_modify_tick']) {
                    $modifyTick = 'YES';
                } else {
                    $modifyTick = 'NO';
                }
                if ($result['questionnaire_translation_tick']) {
                    $transelationTick = 'YES';
                } else {
                    $transelationTick = 'NO';
                }
                //set pm information html include in html
                if ($step > 2) {
                    $pmInfo = '<tr><th>PM Information</th><td><table id="pmInformation"><tr><th>PM Confirmation</th></tr><tr><td>'
                            . $result['pm_signature'] . '</td></tr><tr><th>Request for Questionnaire team description</th></tr><tr><td>'
                            . urldecode($result['questionnaire_team_description']) . '</td></tr><tr><th>Is it a new questionnaire ?</th></tr><tr><td id="new-questionnaire">'
                            . $newTick . '</td></tr>';
                    if ($newTick == 'NO') {
                        $formTextStr = '<table id="if-cloned-text-table">';
                        if (empty($fileNum)) {
                            $formTextStr .= $result['questionnaire_from_text'];
                        } else {
                            $formTextArr = explode('***', $result['questionnaire_from_text']);
                            foreach ($formTextArr as $everyForm) {
                                $formTextStr .= '<tr><td>';
                                $everyFormArr = explode('**', $everyForm);
                                if ($everyFormArr[0] == '1') {
                                    $formTextStr .= $result['question_file1_label'] . ':' . $everyFormArr[1];
                                }
                                if ($everyFormArr[0] == '2') {
                                    $formTextStr .= $result['question_file2_label'] . ':' . $everyFormArr[1];
                                }
                                if ($everyFormArr[0] == '3') {
                                    $formTextStr .= $result['question_file3_label'] . ':' . $everyFormArr[1];
                                }
                                if ($everyFormArr[0] == '4') {
                                    $formTextStr .= $result['question_file4_label'] . ':' . $everyFormArr[1];
                                }
                                $formTextStr .= '</td></tr>';
                            }
                        }
                        $formTextStr .= '</table>';
                        $pmInfo .= '<tr><th>If cloned, from wich questionnaire ?</th></tr>
                            <tr><td>' . $formTextStr . '</td></tr>
                            <tr><th>If cloned, is there any modification ?</th></tr>
                            <tr><td id="cloned-questionnaire">' . $modifyTick . '</td></tr>';
                    }
                    $pmInfo .= '<tr><th>Translation needed ?</th></tr>
                        <tr><td>' . $transelationTick . '</td></tr>';
                    if ($transelationTick == 'YES') {
                        $languageNumArr = explode(',', $result['questionnaire_language_num']);
                        $languageString = '';
                        foreach ($languageNumArr as $num) {
                            $languageString .= $language[$num] . ' ';
                        }
                        $pmInfo .= '<tr><th>Languages</th></tr>
                            <tr><td>' . $languageString . '</td></tr>';
                    }
                    $pmInfo .= '</table></td></tr>';
                }
                //set Proofreading information
                $proofInfo = '<tr><th>Proofreading Information</th><td><table id="pmInformation"><tr><th>Proofreading Confirmation</th></tr><tr><td>'
                        . $result['proofreading_signature'] . '</td></tr>';
                $proofInfo .= '</td></tr></table></td></tr>';
                //set questionnaire information
                if ($step > 4) {
                    $questionnaireInfo = '<tr><th>Upload or Clonage Information</th><td><table id="pmInformation"><tr><th>Upload or Clonage Confirmation</th></tr><tr><td>'
                            . $result['upload_waiting_clonage_signature'] . '</td></tr><tr><th>Aol Questionnaire</th></tr><tr><td>';
                    if (!empty($fileNum)) {
                        foreach ($fileNum as $key => $f) {
                            foreach ($result['aolquestionnaire'] as $aolquestionnaire) {
                                if ($aolquestionnaire['file_index'] == $f) {
                                    $questionnaireInfo .= $key . ':' . $aolquestionnaire['name'] . '.<br/>';
                                }
                            }
                        }
                    } else {
                        if (isset($result['aolquestionnaire']['0']))
                            $questionnaireInfo .= $result['aolquestionnaire']['0']['name'];
                    }
                    $questionnaireInfo .= '</td></tr></table></td></tr>';
                }
                if ($step == 2) {
                    $html .= '<tr><th>Client Confirmation</th><td>' . $result['client_signature'] . '</td></tr>';
                    $html .= '<tr><th>Request for Questionnaire team description</th><td>
                        <textarea id="albatross_custombundle_customfieldtype_questionnaire_team_description" 
                        name="albatross_custombundle_customfieldtype[questionnaire_team_description]"></textarea></td></tr>';
                    $html .= '<tr><th>Is it a new questionnaire ? YES/NO</th><td>
                        <input type="radio" value="0" onclick="questionnaireNewRadio(0);" name="albatross_custombundle_customfieldtype[questionnaire_new_tick]" checked="checked">YES
                        <input type="radio" value="1" onclick="questionnaireNewRadio(1);" name="albatross_custombundle_customfieldtype[questionnaire_new_tick]">NO</td></tr>';
                    $html .= '<tr><th>If cloned, from wich questionnaire ?</th><td><table>';
                    if ($result['question_file1_label'] != null || $result['question_file2_label'] != null || $result['question_file3_label'] != null || $result['question_file4_label'] != null) {
                        if ($result['question_file1_label'] != null) {
                            $html .= '<tr><td>' . $result['question_file1_label']
                                    . '</td><td>: <input class="clone-aol-questionnaire" type="text" placeholder="enter AOL Questionnaire" name="albatross_custombundle_customfieldtype_1"></td></tr>';
                        }
                        if ($result['question_file2_label'] != null) {
                            $html .= '<tr><td>' . $result['question_file2_label']
                                    . '</td><td>: <input class="clone-aol-questionnaire" type="text" placeholder="enter AOL Questionnaire" name="albatross_custombundle_customfieldtype_2"></td></tr>';
                        }
                        if ($result['question_file3_label'] != null) {
                            $html .= '<tr><td>' . $result['question_file3_label']
                                    . '</td><td>: <input class="clone-aol-questionnaire" type="text" placeholder="enter AOL Questionnaire" name="albatross_custombundle_customfieldtype_3"></td></tr>';
                        }
                        if ($result['question_file4_label'] != null) {
                            $html .= '<tr><td>' . $result['question_file4_label']
                                    . '</td><td>: <input class="clone-aol-questionnaire" type="text" placeholder="enter AOL Questionnaire" name="albatross_custombundle_customfieldtype_4"></td></tr>';
                        }
                        $html .= '<input id="is-nofile-which-questionnaire" type="text" value="1" style="display:none" />';
                    } else {
                        $html .= '<tr><td><textarea id="albatross_custombundle_customfieldtype_questionnaire_from_text" 
                        name="albatross_custombundle_customfieldtype[questionnaire_from_text]"></textarea></td></tr>';
                        $html .= '<input id="is-nofile-which-questionnaire" type="text" value="0" style="display:none" />';
                    }

                    $html .= '</table></td></tr>';
                    $html .= '<tr><th>If cloned, is there any modification ? YES/NO</th><td>
                        <input type="radio" value="0" name="albatross_custombundle_customfieldtype[questionnaire_modify_tick]">YES
                        <input type="radio" value="1" id="albatross_custombundle_customfieldtype_questionnaire_modify_tick_no" name="albatross_custombundle_customfieldtype[questionnaire_modify_tick]">NO</td></tr>';
                    $html .= '<tr><th>Translation needed ? YES/NO</th><td>
                        <input class="transelation-radio" onclick="questionnaireTranslationRadio(0);" type="radio" value="0" name="albatross_custombundle_customfieldtype[questionnaire_translation_tick]">YES
                        <input class="transelation-radio" onclick="questionnaireTranslationRadio(1);" type="radio" value="1" name="albatross_custombundle_customfieldtype[questionnaire_translation_tick]" checked="checked">NO</td></tr>';
                    $html .= '<tr><th>languages needed</th><td>
                        <select id="albatross_custombundle_customfieldtype_questionnaire_language_num" 
                        name="albatross_custombundle_customfieldtype[questionnaire_new_tick]" multiple="multiple">
                        <script type="text/javascript">useChosenAtLanguageSelect();</script>';
                    foreach ($language as $key => $l) {
                        $html .= '<option value="' . $key . '">' . $l . '</option>';
                    }
                    $html .= '</select></td></tr>';
                } else if ($step == 3) { //proof step
                    $html .= '<tr><th>Client Confirmation</th><td>' . $result['client_signature'] . '</td></tr>';
                    $html .= $pmInfo;
                    $html .= '<tr><th>Reject</th><td><input id="reject-tick" onclick="showRejectForm();" type="checkbox"></td></tr>';
                    $html .= '<tr id="reject-comment-tr" style="display:none"><th>Reject Comment</th><td><textarea id="reject-comment"></textarea></td></tr>';
                    $html .= '<tr id="reject-file-tr" style="display:none"><th>Reject File</th><td><input id="reject-file" type="file"><input type="button" onclick="updateRejectQuestionnaireFile(' . $result['id'] . ')" id="reject-file-button" value="Upload"></td></tr>';
                } else if ($step == 5) { //qc step
                    $html .= '<tr><th>Client Confirmation</th><td>' . $result['client_signature'] . '</td></tr>';
                    $html .= $pmInfo;
                    $html .= $proofInfo;
                    $html .= $questionnaireInfo;
                    $html .= '<tr><th>Test survey number</th><td><input type="text" id="test-survey-number"></td></tr>';
                } else if ($step == 6) { //test step
                    $html .= '<tr><th>Client Confirmation</th><td>' . $result['client_signature'] . '</td></tr>';
                    $html .= $pmInfo;
                    $html .= $proofInfo;
                    $html .= $questionnaireInfo;
                    $html .= '<tr><th>Quality Control Confirmation</th><td><table><tr><th>QC Signature</th><td>' . $result['quality_control_signature'] . '</td></tr>
                        <tr><th>Test Survey Number: </th><td>' . $result['test_survey_number'] . '</td></tr></table></td></tr>';
                    $html .= '<tr><th>Tested survey number</th><td><input type="text" id="tested-survey-number"></td></tr>';
                } else if ($step == 7) {
                    $html .= '<tr><th>Client Confirmation</th><td>' . $result['client_signature'] . '</td></tr>';
                    $html .= $pmInfo;
                    $html .= $proofInfo;
                    $html .= $questionnaireInfo;
                    $html .= '<tr><th>Quality Control Confirmation</th><td><table><tr><th>QC Signature</th><td>' . $result['quality_control_signature'] . '</td></tr>
                        <tr><th>Test Survey Number: </th><td>' . $result['test_survey_number'] . '</td></tr></table></td></tr>';
                    $html .= '<tr><th>Test Confirmation</th><td><table><tr><th>Testing Signature</th><td>' . $result['testing_signature'] . '</td></tr>
                        <tr><th>Tested Survey Number: </th><td>' . $result['tested_survey_number'] . '</td></tr></table></td></tr>';
                } else if ($step == 8) {
                    $html .= '<tr><th>Client Confirmation</th><td>' . $result['client_signature'] . '</td></tr>';
                    $html .= $pmInfo;
                    $html .= $proofInfo;
                    $html .= $questionnaireInfo;
                    if ($result['choosen_type'] == 2 && $result['questionnaire_modify_tick'] == 0) {
                        $html .= '<tr><th>Quality Control Confirmation</th><td>Skipped</td></tr>';
                        $html .= '<tr><th>Test Confirmation</th><td>Skipped</td></tr>';
                        $html .= '<tr><th>Translation Confirmation</th><td>Skipped</td></tr>';
                    } else {
                        $html .= '<tr><th>Quality Control Confirmation</th><td><table><tr><th>QC Signature</th><td>' . $result['quality_control_signature'] . '</td></tr>
                            <tr><th>Test Survey Number: </th><td>' . $result['test_survey_number'] . '</td></tr></table></td></tr>';
                        $html .= '<tr><th>Test Confirmation</th><td><table><tr><th>Testing Signature</th><td>' . $result['testing_signature'] . '</td></tr>
                            <tr><th>Tested Survey Number: </th><td>' . $result['tested_survey_number'] . '</td></tr></table></td></tr>';
                        if ($result['questionnaire_translation_tick'] == 1) {
                            $html .= '<tr><th>Translation Confirmation</th><td>' . $result['translation_signature'] . '</td></tr>';
                        } else {
                            $html .= '<tr><th>Translation Confirmation</th><td>Skipped</td></tr>';
                        }
                    }
                } else if ($step == 9) {
                    $html .= '<tr><th>Client Confirmation</th><td>' . $result['client_signature'] . '</td></tr>';
                    $html .= $pmInfo;
                    $html .= $proofInfo;
                    $html .= '<tr><th>Reject Comment</th><td>' . urldecode($result['rejected_comment']) . '</td></tr>';
                } else if ($step == 10) {
                    $html .= '<tr><th>Client Confirmation</th><td>' . $result['client_signature'] . '</td></tr>';
                    $html .= $pmInfo;
                    $html .= $proofInfo;
                } else if ($step == 11) {
                    $html .= '<tr><th>Client Confirmation</th><td>' . $result['client_signature'] . '</td></tr>';
                    $html .= $pmInfo;
                    $html .= $proofInfo;
                }
                if ($step == 9) {
                    $html .= '<tr><th>Download Reject File</th>';
                    $html .= '<td><form class="question_down_file" action = "'
                            . $request->getBaseUrl() . '/Customproject/downloadFile/' . $result['id']
                            . '/9"><input type="submit" class="reject-file-button-questionnaire-down" title="Rejected File" 
                                    value="Rejected File"/></form></td></tr>';
                } else {
                    $html .= '<tr id="signature_tr"><th id="signature_label">Signature</th><td id="signature_input"></td></tr>';
                }
                $html .= '<tr><th>DownLoad File:</th><td>';
                if ($result['path'] != null) {

                    if ($step == 10 || $step == 11) {
                        $html .= '<table><tr><td><form class="question_down_file" action = "'
                                . $request->getBaseUrl() . '/Customproject/downloadFile/' . $result['id']
                                . '"><input type="submit" class="file-button-questionnaire-down" title="'
                                . $result['question_file1_label'] . $v1 . '" value="' . $result['question_file1_label'] . $v1
                                . '"/></form><input placeholder="New AOL questionnaire name" class="aol_questionnaire_wide" name="aol_questionnaire_1" type="text" value=""><input type="text" id="check-new-questionnaire-type" value="0"></td></td></tr>';
                        if ($result['path_2'] != null) {
                            $html .= '<tr><td><form class="question_down_file" action = "'
                                    . $request->getBaseUrl() . '/Customproject/downloadFile/' . $result['id']
                                    . '/2"><input type="submit" class="file-button-questionnaire-down" title="'
                                    . $result['question_file2_label'] . $v2 . '" value="' . $result['question_file2_label'] . $v2
                                    . '"/></form><input placeholder="New AOL questionnaire name" class="aol_questionnaire_wide" name="aol_questionnaire_2" type="text" value=""></td></td></tr>';
                        }
                        if ($result['path_3'] != null) {
                            $html .= '<tr><td><form class="question_down_file" action = "'
                                    . $request->getBaseUrl() . '/Customproject/downloadFile/' . $result['id']
                                    . '/3"><input type="submit" class="file-button-questionnaire-down" title="'
                                    . $result['question_file3_label'] . $v3 . '" value="' . $result['question_file3_label'] . $v3
                                    . '"/></form><input placeholder="New AOL questionnaire name" class="aol_questionnaire_wide" name="aol_questionnaire_3" type="text" value=""></td></td></tr>';
                        }
                        if ($result['path_4'] != null) {
                            $html .= '<tr><td><form class="question_down_file" action = "'
                                    . $request->getBaseUrl() . '/Customproject/downloadFile/' . $result['id']
                                    . '/4"><input type="submit" class="file-button-questionnaire-down" title="'
                                    . $result['question_file4_label'] . $v4 . '" value="' . $result['question_file4_label'] . $v2
                                    . '"/></form><input placeholder="New AOL questionnaire name" class="aol_questionnaire_wide" name="aol_questionnaire_4" type="text" value=""></td></td></tr>';
                        }
                    } else {
                        $html .= '<table><tr><td><form class="question_down_file" action = "' . $request->getBaseUrl() . '/Customproject/downloadFile/' . $result['id'] . '"><input type="submit" class="file-button-questionnaire-down" title="' . $result['question_file1_label'] . $v1 . '" value="' . $result['question_file1_label'] . $v1 . '"/></form></td>' . $otherFile .
                                '<div class="clear_both"></div></td></tr>';
                    }
                } else {
                    if ($step == 10 || $step == 11) {
                        $html .= 'No File.<div class="clear_both"></div><textarea placeholder="New AOL questionnaire name" id="aol_questionnaire_wide_null" name="aol_questionnaire_null" type="text" value=""></textarea><input type="text" id="check-new-questionnaire-type" value="1"></td></tr>';
                    } else {
                        $html .= 'No File.<div class="clear_both"></div></td></tr>';
                    }
                }

                if ($step == 3) {
                    if ($result['questionnaire_upload_file_tick'] == 0 || $result['questionnaire_upload_file_tick'] == null) {
                        $updateFile = '<table id="update-file-table"><tr>';
                        foreach ($fileNum as $key => $f) {
                            $updateFile .= '<td><input style="display:none;" id="update-file-' . $f . '" type="file"  name="update_file[' . $f . ']" onchange="$(\'#update-file-button-' . $f . '\').val(\'Selected\'); $(\'#update-file-button-' . $f . '\').css(\'background\', \'#D35400\');">';
                            $updateFile .= '<input class="file-button-questionnaire-update" type="button" id="update-file-button-' . $f . '" onclick="$(\'#update-file-' . $f . '\').click();" value="Browse">';
                            $updateFile .= '</br><input type="button" class="update-file-button" onclick="updateQuestionnaireFile(' . $result['id'] . ', ' . $f . ');" value="Update"></td>';
                        }
                        $updateFile .= '</tr></table>';
                        $html .= '<tr id="upload-updated-version"><th>Upload updated version</th><td>' . $updateFile . '</td></tr>';
                    }
                }
                if ($step == 7) {
                    if ($result['questionnaire_language_num'] != 'null') {
                        $languageArr = explode(',', $result['questionnaire_language_num']);
                        foreach ($languageArr as $l) {
                            $html .= '<tr><th class="language-update-th">' . $language[$l] . ' Upload : </th><td><table class="update-language-file-table"><input id="upload-language-signature" style="display:none;" value="' . $result['translation_signature'] . '" ><tr>';
                            foreach ($fileNum as $key => $f) {
                                if ($translationEntity = $em->getRepository('AlbatrossCustomBundle:TranslationFile')->findLastOneByFileIndexLanguageNum($result['id'], $f, $l)) {
                                    $html .= '<td><input id="language_file_' . $f . '_' . $l . '" name="language_file[' . $f . '_' . $l . ']" type="file" onchange="languageFileSelected(' . $f . ', ' . $l . ')" class="language_file" style="display:none;">';
                                    $html .= '<input id="language_file_button' . $f . '_' . $l . '" type="button" value="Updated" style="background:#34495e;border:1px #2c3e50 solid" onclick="$(\'#language_file_' . $f . '_' . $l . '\').click();" class="language-file-update-button">';
                                    $html .= '</br><input type="button" id="language_file_submit' . $f . '_' . $l . '" class="update-language-file-submit" onclick="setTranslationFile(' . $result['id'] . ', ' . $f . ', ' . $l . ');" value="Update"></td>';
                                } else {
                                    $html .= '<td><input id="language_file_' . $f . '_' . $l . '" name="language_file[' . $f . '_' . $l . ']" type="file" onchange="languageFileSelected(' . $f . ', ' . $l . ')" class="language_file" style="display:none;">';
                                    $html .= '<input id="language_file_button' . $f . '_' . $l . '" type="button" value="Browse" onclick="$(\'#language_file_' . $f . '_' . $l . '\').click();" class="language-file-update-button">';
                                    $html .= '</br><input type="button" id="language_file_submit' . $f . '_' . $l . '" class="update-language-file-submit" onclick="setTranslationFile(' . $result['id'] . ', ' . $f . ', ' . $l . ');" value="Save"></td>';
                                }
                            }
                            $html .= '</tr></table></td></tr>';
                        }
                    }
                }
                $html .= '<tr><td colspan="3"><span id="submit_button_quest"></span></td></tr>';
                break;
            case 'dic':
                $html = '<tr><th colspan="2" style="' . $titleStyle . '">DIC Info' . $closeButton . '</th></tr>' .
                        '<tr><th>Project Name</th><td>' . $result['customwave']['customproject']['name'] . '</td></tr>' .
                        '<tr><th>Wave Name</th><td>' . $result['customwave']['name'] . '</td></tr>' .
                        '<tr><th>Submit Time</th><td>' . $result['submittime'] . '</td></tr>' .
                        '<tr><th>Submit User</th><td>' . $result['user']['username'] . '</td></tr>' .
                        '<tr><th>Label</th><td>' . $result['question_file1_label'] . '</td></tr>' .
                        '<tr><th>Country</th><td>' . $diccountryStrTrim . '</td></tr>' .
                        '<tr><td colspan="2"><form action = "' . $request->getBaseUrl() . '/Customproject/downloadFile/' . $result['id'] . '"><input type="submit" value="download"/></form></td></tr>';
                break;
        }
        return new Response($html);
    }

    public function updateFileAction($id, $index) {
        $content = $this->getRequest()->files->get('file0'); //to get file object        
        if (is_object($content)) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AlbatrossCustomBundle:Customfield')->find($id);

            $filename = $content->getClientOriginalName();
            $date = date('ymd');
            $path = 'Questionnaire/' . $date . '/' . $entity->getCustomwave()->getName() . '/' . $filename;
            $dir = $this->get('kernel')->getRootDir() . '/../web/Questionnaire/' . $date . '/' . $entity->getCustomwave()->getName() . '/';
            $content->move($dir, $filename);
            $version = 0;
            if ($index == 1) {
                $entity->setPath($path);
                $version = ($entity->getQuestionnaireVersionNum1() == null) ? 2 : ($entity->getQuestionnaireVersionNum1() + 1);
                $entity->setQuestionnaireVersionNum1($version);
            } else if ($index == 2) {
                $entity->setPath2($path);
                $version = ($entity->getQuestionnaireVersionNum2() == null) ? 2 : ($entity->getQuestionnaireVersionNum2() + 1);
                $entity->setQuestionnaireVersionNum2($version);
            } else if ($index == 3) {
                $entity->setPath3($path);
                $version = ($entity->getQuestionnaireVersionNum3() == null) ? 2 : ($entity->getQuestionnaireVersionNum3() + 1);
                $entity->setQuestionnaireVersionNum3($version);
            } else if ($index == 4) {
                $entity->setPath4($path);
                $version = ($entity->getQuestionnaireVersionNum4() == null) ? 2 : ($entity->getQuestionnaireVersionNum4() + 1);
                $entity->setQuestionnaireVersionNum4($version);
            }
            $em->persist($entity);
            $em->flush();
        }

        return new Response($version);
    }

    public function updateRejectFileAction($id) {
        $content = $this->getRequest()->files->get('file0'); //to get file object        
        if (is_object($content)) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AlbatrossCustomBundle:Customfield')->find($id);

            $filename = $content->getClientOriginalName();
            $date = date('ymd');
            $path = 'Questionnaire/' . $date . '/RejectFiles/' . $entity->getCustomwave()->getName() . '/' . $filename;
            $dir = $this->get('kernel')->getRootDir() . '/../web/Questionnaire/' . $date . '/RejectFiles/' . $entity->getCustomwave()->getName() . '/';
            $content->move($dir, $filename);
            $entity->setRejectedFilePath($path);
            $em->persist($entity);
            $em->flush();
        }

        return new Response('updated');
    }

    public function setTranslationFileAction($id, $index, $language) {
        $languageArr = array(
            0 => '',
            1 => 'French',
            2 => 'Spanish',
            3 => 'Italian',
            4 => 'German',
            5 => 'Korean',
            6 => 'Japanese',
            7 => 'Portuguese',
            8 => 'Russian',
            9 => 'traditional Chinese',
            10 => 'Simplified Chinese',
            11 => 'Thai'
        );
        $content = $this->getRequest()->files->get('file0'); //to get file object
        if (is_object($content)) {

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AlbatrossCustomBundle:Customfield')->find($id);

            $filename = $content->getClientOriginalName();
            $date = date('ymd');
            $path = 'QuestionnaireLanguage/' . $date . '/' . $entity->getCustomwave()->getName() . '/' . $languageArr[$language] . '/' . $filename;
            $dir = $this->get('kernel')->getRootDir() . '/../web/QuestionnaireLanguage/' . $date . '/' . $entity->getCustomwave()->getName() . '/' . $languageArr[$language] . '/';
            $content->move($dir, $filename);
            if (!$translationFileEntityArr = $em->getRepository('AlbatrossCustomBundle:TranslationFile')->findLastOneByFileIndexLanguageNum($id, $index, $language)) {
                $translationFileEntity = new TranslationFile();
                $translationFileEntity->setCustomfield($entity);
                $translationFileEntity->setFileIndex($index);
                $translationFileEntity->setLanguageIndex($language);
            } else {
                $translationFileEntity = $translationFileEntityArr[0];
            }
            $translationFileEntity->setPath($path);

            $em->persist($translationFileEntity);
            $em->flush();
            return new Response('Updated');
        }
        return new Response('');
    }

    public function setQuestionSignAction() {
        $data = $this->getRequest()->getContent();
        $dataArr = json_decode($data, true);

        $id = $dataArr['id'];
        $step = $dataArr['step'];
        $value = $dataArr['value'];
        if ($step == '2') {
            $description = $dataArr['description'];

            $fromtext = rtrim($dataArr['formtext'], '***');

            $new = $dataArr['new'];
            $modify = $dataArr['modify'];
            $translation = $dataArr['translation'];
            $language = $dataArr['language'];
        }
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AlbatrossCustomBundle:Customfield')->find($id);

        switch ($step) {
            case '1':
                $entity->setClientConfirmation(1);
                $entity->setClientSignature($value);
                $entity->setQuestionStatus(2);
                $entity->setClientConfirmationTime(date('Y-m-d H:i:s'));
                break;
            case '2':
                if ($new == 0) {
                    $entity->setQuestionnaireNewTick(1);
                } else if ($new == 1) {
                    $entity->setQuestionnaireNewTick(0);
                }

                if ($modify == 0) {
                    $entity->setQuestionnaireModifyTick(1);
                } else if ($modify == 1) {
                    $entity->setQuestionnaireModifyTick(0);
                }

                if ($translation == 0) {
                    $entity->setQuestionnaireTranslationTick(1);
                } else if ($translation == 1) {
                    $entity->setQuestionnaireTranslationTick(0);
                }

                $entity->setQuestionnaireTeamDescription($description);
                $entity->setQuestionnaireFromText($fromtext);
                $entity->setQuestionnaireLanguageNum($language);
                $entity->setPmConfirmation(1);
                $entity->setPmSignature($value);
                $entity->setQuestionStatus(3);
                break;
            case '3':
                $entity->setProofreading(1);
                $entity->setProofreadingSignature($value);
                if (isset($dataArr['choosen'])) {
                    $entity->setChoosenType($dataArr['choosen']);
                    $entity->setQuestionStatus(4);
                } else if (isset($dataArr['comment'])) {
                    $entity->setRejectedComment($dataArr['comment']);
                    $entity->setQuestionStatus(9);
                }
                break;
            case '5':
                $testSurveyNumber = $dataArr['testSurveyNumber'];
                $entity->setQualityControl(1);
                $entity->setQualityControlSignature($value);
                $entity->setTestSurveyNumber($testSurveyNumber);
                $entity->setQuestionStatus(6);
                break;
            case '6':
                $testedSurveyNumber = $dataArr['testedSurveyNumber'];
                $entity->setTesting(1);
                if ($entity->getQuestionnaireTranslationTick() == 0) {
                    $entity->setQuestionStatus(8);
                } else {
                    $entity->setQuestionStatus(7);
                }
                $entity->setTestingSignature($value);
                $entity->setTestedSurveyNumber($testedSurveyNumber);
                break;
            case '7':
                $entity->setTranslationSignature($value);
                $entity->setQuestionEndTime(date('Y-m-d'));
                if ($entity->getQuestionnaireTranslationTick()) {
                    $languageStr = $entity->getQuestionnaireLanguageNum();
                    $languageArr = explode(',', $languageStr);
                    $languageNum = count($languageArr);

                    $firstUpdateFileNum = 0;
                    if ($entity->getQuestionFile1Label())
                        $firstUpdateFileNum++;
                    if ($entity->getQuestionFile2Label())
                        $firstUpdateFileNum++;
                    if ($entity->getQuestionFile3Label())
                        $firstUpdateFileNum++;
                    if ($entity->getQuestionFile4Label())
                        $firstUpdateFileNum++;
                    $translationEntityArr = $entity->getTranslationFile()->toArray();
                    $updatedFileNum = count($translationEntityArr);

                    if (($languageNum * $firstUpdateFileNum) == $updatedFileNum) {
                        $entity->setQuestionStatus(8);
                    }
                } else {
                    $entity->setQuestionStatus(8);
                }
                break;
        }
        $em->persist($entity);
        $em->flush();
        return new Response('1');
    }

    public function setQuestionSign2Action() {
        $data = $this->getRequest()->getContent();
        $dataArr = explode('***', $data);
        $id = $dataArr[0];
        $value = $dataArr[2];
        $aolquestArrStr = explode('&&&', $dataArr[3]);
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AlbatrossCustomBundle:Customfield')->find($id);
        $entity->setUploadWaitingClonage(1);
        $entity->setUploadWaitingClonageSignature($value);

        // if is cloned (chosen value 2 is cloned , 1 is upload)
        // check modification tick value
        if ($entity->getChoosenType() == 2) {
            if ($entity->getQuestionnaireModifyTick() == 0) {
                $entity->setQuestionStatus(8);
            } else {
                $entity->setQuestionStatus(5);
            }
        } else {
            $entity->setQuestionStatus(5);
        }

        foreach ($aolquestArrStr as $aol) {
            if ($aol != '') {
                $aolquestEntity = new Aolquestionnaire();
                if (strpos($aol, '&&') !== false) {
                    $aolquestArr = explode('&&', $aol);
                    $aolquestEntity->setCustomfield($entity);
                    $aolquestEntity->setName($aolquestArr[0]);
                    $aolquestEntity->setFileIndex($aolquestArr[1]);
                } else {
                    $aolquestEntity->setCustomfield($entity);
                    $aolquestEntity->setName($aol);
                    $aolquestEntity->setFileIndex(null);
                }
                $em->persist($aolquestEntity);
            }
        }
        $em->persist($entity);
        $em->flush();
        return new Response('1');
    }

    public function downloadInvoiceFileAction($id) {
        $em = $this->getDoctrine()->getManager();
        $file_entity = $em->getRepository('AlbatrossCustomBundle:Invoice')->find($id);
        $file_dir = $file_entity->getPath();
        $file_arr = explode('/', $file_dir);
        $file_name = $file_arr['4'];
        if (!file_exists($file_dir)) {
            header("Content-type: text/html; charset=utf-8");
            echo "File not found!";
            exit;
        } else {
            $file = fopen($file_dir, "r");
            $file_size = filesize($file_dir);
            $header = $this->getRequest()->server->getHeaders();
            $ua = $header['USER_AGENT'];
            $encoded_filename_pre = urlencode($file_name);
            $encoded_filename = str_replace("+", "%20", $encoded_filename_pre);
            Header("Content-type: application/octet-stream");
            Header("Accept-Ranges: bytes");
            Header("Accept-Length: " . $file_size);
            if (preg_match("/MSIE/", $ua)) {
                header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
            } else if (preg_match("/Firefox/", $ua)) {
                header('Content-Disposition: attachment; filename*="utf8\'\'' . $file_name . '"');
            } else {
                header('Content-Disposition: attachment; filename="' . $file_name . '"');
            }
            $contents = fread($file, $file_size);
            echo $contents;
            fclose($file);
            exit();
        }
    }

    public function downloadFileAction($id, $filenum = null) {
        $em = $this->getDoctrine()->getManager();
        $file_entity = $em->getRepository('AlbatrossCustomBundle:Customfield')->find($id);
        if ($filenum == 2)
            $file_dir = $file_entity->getPath2();
        elseif ($filenum == 3)
            $file_dir = $file_entity->getPath3();
        elseif ($filenum == 4)
            $file_dir = $file_entity->getPath4();
        elseif ($filenum == 9)
            $file_dir = $file_entity->getRejectedFilePath();
        else
            $file_dir = $file_entity->getPath();
        $file_arr = explode('/', $file_dir);

        if ($file_entity->getFieldType() == 'questionnaire' && $filenum != 9)
            $file_name = $file_arr['3'];
        else if ($file_entity->getFieldType() == 'questionnaire' && $filenum == 9)
            $file_name = $file_arr['4'];
        else
            $file_name = $file_arr['2'];
        if (!file_exists($file_dir)) {
            header("Content-type: text/html; charset=utf-8");
            echo "File not found!";
            exit;
        } else {
            $file = fopen($file_dir, "r");
            $file_size = filesize($file_dir);
            $header = $this->getRequest()->server->getHeaders();
            $ua = $header['USER_AGENT'];
            $encoded_filename_pre = urlencode($file_name);
            $encoded_filename = str_replace("+", "%20", $encoded_filename_pre);
            Header("Content-type: application/octet-stream");
            Header("Accept-Ranges: bytes");
            Header("Accept-Length: " . $file_size);
            if (preg_match("/MSIE/", $ua)) {
                header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
            } else if (preg_match("/Firefox/", $ua)) {
                header('Content-Disposition: attachment; filename*="utf8\'\'' . $file_name . '"');
            } else {
                header('Content-Disposition: attachment; filename="' . $file_name . '"');
            }
            $contents = fread($file, $file_size);
            echo $contents;
            fclose($file);
            exit();
        }
    }

    protected function getBuArr() {
        $em = $this->getDoctrine()->getManager();
        $buEntities = $em->getRepository('AlbatrossAceBundle:Bu')->findAll();
        $buArr = array();
        foreach ($buEntities as $b) {
            $buArr[$b->getNumber()] = $b->getName();
        }

        return $buArr;
    }

    protected function getGantChartData($start, $end, $wid) {
        $em = $this->getDoctrine()->getManager();
        $secu = $this->container->get('security.context');

        $data = array();
        $style = 'style="color:#FFF"';

        //get all customfield belong to this customwave
        $qb = $em->createQueryBuilder();
        $qb->select('f')
                ->from('AlbatrossCustomBundle:Customfield', 'f')
                ->leftJoin('f.customwave', 'w')
                ->where('w.id = :wid');
        $qb->setParameters(array(
            'wid' => $wid
        ));
        $query = $qb->getQuery();
        $fieldEntity = $query->getArrayResult();

        //get iof(attachment) bind with this customwave
        $iofqb = $em->createQueryBuilder();
        $iofqb->select('a')
                ->from('AlbatrossAceBundle:Attachments', 'a')
                ->leftJoin('a.customwave', 'w')
                ->where('w.id = :wid');
        $iofqb->setParameters(array(
            'wid' => $wid
        ));
        $iofquery = $iofqb->getQuery();
        $iofEntity = $iofquery->getArrayResult();

        //get recap belong to this customwave
        $recapqb = $em->createQueryBuilder();
        $recapqb->select('r')
                ->from('AlbatrossCustomBundle:Recap', 'r')
                ->leftJoin('r.customwave', 'w')
                ->where('w.id = :wid')
                ->andWhere('r.countryType = 0');
        $recapqb->setParameters(array(
            'wid' => $wid
        ));

        $recapquery = $recapqb->getQuery();
        $recapEntity = $recapquery->getArrayResult();

        //get pos belong to this customwave

        $poslistqb = $em->createQueryBuilder();
        $poslistqb->select('p')
                ->from('AlbatrossCustomBundle:Poslist', 'p')
                ->leftJoin('p.customwave', 'w')
                ->where('w.id = :wid');
        $poslistqb->setParameters(array(
            'wid' => $wid
        ));

        $poslistquery = $poslistqb->getQuery();
        $poslistEntity = $poslistquery->getArrayResult();
        //set the estimate times
        if ($start == '' && $end == '') {
            $eContracts = '';
            $eContracte = '';
            $eDeposits = '';
            $eDeposite = '';
            $eQuestionnaires = '';
            $eQuestionnairee = '';
            $ePOSs = '';
            $ePOSe = '';
            $eStaffs = '';
            $eStaffe = '';
            $eShoppers = '';
            $eShoppere = '';
            $eGuideliness = '';
            $eGuidelinese = '';
            $eSPEs = '';
            $eSPEe = '';
            $eIOFs = '';
            $eIOFe = '';
            $eFieldworks = '';
            $eFieldworke = '';
            $eEditings = '';
            $eEditinge = '';
            $eQcs = '';
            $eQce = '';
            $eReports = '';
            $eReporte = '';
            $eBalances = '';
            $eBalancee = '';
        } else {
            $eContracts = '';
            $eContracte = '';
            $eDeposits = '';
            $eDeposite = '';
            $eQuestionnaires = date('Y-m-d', strtotime('-30 day', strtotime($start)));
            $eQuestionnairee = date('Y-m-d', strtotime('-29 day', strtotime($start)));
            $ePOSs = date('Y-m-d', strtotime('-30 day', strtotime($start)));
            $ePOSe = date('Y-m-d', strtotime('-29 day', strtotime($start)));
            $eStaffs = date('Y-m-d', strtotime('-30 day', strtotime($start)));
            $eStaffe = date('Y-m-d', strtotime('-29 day', strtotime($start)));
            $eShoppers = date('Y-m-d', strtotime('-30 day', strtotime($start)));
            $eShoppere = date('Y-m-d', strtotime('-29 day', strtotime($start)));
            $eGuideliness = date('Y-m-d', strtotime('-15 day', strtotime($start)));
            $eGuidelinese = date('Y-m-d', strtotime('-14 day', strtotime($start)));
            $eSPEs = date('Y-m-d', strtotime('-15 day', strtotime($start)));
            $eSPEe = date('Y-m-d', strtotime('-14 day', strtotime($start)));
            $eIOFs = date('Y-m-d', strtotime('-10 day', strtotime($start)));
            $eIOFe = date('Y-m-d', strtotime('-9 day', strtotime($start)));
            $eFieldworks = date('Y-m-d', strtotime($start));
            $eFieldworke = date('Y-m-d', strtotime($end));
            $eEditings = '';
            $eEditinge = '';
            $eQcs = date('Y-m-d', strtotime('+20 day', strtotime($end)));
            $eQce = date('Y-m-d', strtotime('+21 day', strtotime($end)));
            $eReports = date('Y-m-d', strtotime('+20 day', strtotime($end)));
            $eReporte = date('Y-m-d', strtotime('+21 day', strtotime($end)));
            $eBalances = date('Y-m-d', strtotime('+35 day', strtotime($end)));
            $eBalancee = date('Y-m-d', strtotime('+36 day', strtotime($end)));
        }
        //set the actuall times
        $questionnaireStart = '';
        $questionnaireEnd = '';
        $stafflistStart = '';
        $stafflistEnd = '';
        $shopperprofileStart = '';
        $shopperprofileEnd = '';
        $brandguidelinesStart = '';
        $brandguidelinesEnd = '';
        $spebriefStart = '';
        $spebriefEnd = '';
        $iofActualStart = '';
        $iofActualEnd = '';
        $questionendtime = '';
        $recapDateActual = '';
        $reportTimeStart = '';
        $reportTimeEnd = '';
        $posTimeStart = '';
        $posTimeEnd = '';
        $qcTimeStart = '';
        $qcTimeEnd = '';
        //from customfield
        foreach ($fieldEntity as $field) {
            if ($field['fieldtype'] == 'questionnaire') {
                if ($field['client_confirmation_time'] != null) {
                    $questionnaire = $field['client_confirmation_time'];
                    $questionnaireStart = date('Y-m-d', strtotime($questionnaire));
                    $questionnaireEnd = date('Y-m-d', strtotime('+1 day', strtotime($questionnaire)));
                }
                if ($field['question_status'] == 7 && $field['question_status'] != null) {
                    $questionendtime = $field['question_end_time'];
                }
            }
            if ($field['fieldtype'] == 'material') {
                if ($field['material_name'] == '1' && $field['submittime'] != null) {
                    $stafflist = $field['submittime'];
                    $stafflistStart = date('Y-m-d', strtotime($stafflist));
                    $stafflistEnd = date('Y-m-d', strtotime('+1 day', strtotime($stafflist)));
                } elseif ($field['material_name'] == '2' && $field['submittime'] != null) {
                    $shopperprofile = $field['submittime'];
                    $shopperprofileStart = date('Y-m-d', strtotime($shopperprofile));
                    $shopperprofileEnd = date('Y-m-d', strtotime('+1 day', strtotime($shopperprofile)));
                } elseif ($field['material_name'] == '3' && $field['submittime'] != null) {
                    $brandguidelines = $field['submittime'];
                    $brandguidelinesStart = date('Y-m-d', strtotime($brandguidelines));
                    $brandguidelinesEnd = date('Y-m-d', strtotime('+1 day', strtotime($brandguidelines)));
                }
            }
            if ($field['fieldtype'] == 'brief') {
                if ($field['main_brief'] == 1 && $field['submittime'] != null) {
                    $spebrief = $field['submittime'];
                    $spebriefStart = date('Y-m-d', strtotime($spebrief));
                    $spebriefEnd = date('Y-m-d', strtotime('+1 day', strtotime($spebrief)));
                }
            }
            if ($field['fieldtype'] == 'report' && $field['submittime'] != null) {
                if ($field['report_executive'] == 1) {
                    $reportTime = $field['submittime'];
                    $reportTimeStart = date('Y-m-d', strtotime($reportTime));
                    $reportTimeEnd = date('Y-m-d', strtotime('+1 day', strtotime($reportTime)));
                }
            }
            if ($field['fieldtype'] == 'dic' && $field['submittime'] != null) {
                $qcTime = $field['submittime'];
                $qcTimeStart = date('Y-m-d', strtotime($qcTime));
                $qcTimeEnd = date('Y-m-d', strtotime('+1 day', strtotime($qcTimeStart)));
            }
        }
        //from iof
        if (!empty($iofEntity) && ($iofEntity[0]['submitteddate'] != null)) {
            $dateObj = $iofEntity[0]['submitteddate'];
            $iofActual = $dateObj->format('Y-m-d');
            $iofActualStart = date('Y-m-d', strtotime($iofActual));
            $iofActualEnd = date('Y-m-d', strtotime('+1 day', strtotime($iofActual)));
        }
        //from recap
        if (!empty($recapEntity) && ($recapEntity[0]['submittime'] != null)) {
            $recapDate = $recapEntity[0]['submittime'];
            $recapDateActual = date('Y-m-d', strtotime($recapDate));
        }
        //from poslist
        if (!empty($poslistEntity) && ($poslistEntity[0]['submittime'] != null)) {
            $posTimeStart = $poslistEntity[0]['submittime'];
            $posTimeEnd = date('Y-m-d', strtotime('+1 day', strtotime($posTimeStart)));
        }
        $data[] = array(
            'label' => '<span class="estimated-contract-label" >Estimated Contract signed</span>',
            'start' => $eContracts,
            'end' => $eContracte,
            'class' => 'estimated-contract',
        );
        $data[] = array(
            'label' => '<span class="estimated-deposit-label" >Estimated Deposit invoiced</span>',
            'start' => $eDeposits,
            'end' => $eDeposite,
            'class' => 'estimated-deposit',
        );
        $data[] = array(
            'label' => '<span class="estimated-questionnaire-label" >Estimated Validate Questionnaire</span>',
            'start' => $eQuestionnaires,
            'end' => $eQuestionnairee,
            'class' => 'estimated-questionnaire',
        );
        $data[] = array(
            'label' => '<span class="estimated-pos-label" >Estimated POS list</span>',
            'start' => $ePOSs,
            'end' => $ePOSe,
            'class' => 'estimated-pos',
        );
        $data[] = array(
            'label' => '<span class="estimated-staff-label" >Estimated Staff list</span>',
            'start' => $eStaffs,
            'end' => $eStaffe,
            'class' => 'estimated-staff',
        );
        $data[] = array(
            'label' => '<span class="estimated-shopper-label" >Estimated Shopper profile</span>',
            'start' => $eShoppers,
            'end' => $eShoppere,
            'class' => 'estimated-shopper',
        );
        $data[] = array(
            'label' => '<span class="estimated-bm-label" >Estimated Brand Guidelines</span>',
            'start' => $eGuideliness,
            'end' => $eGuidelinese,
            'class' => 'estimated-bm',
        );
        $data[] = array(
            'label' => '<span class="estimated-msb-label" >Estimated SPE Brief</span>',
            'start' => $eSPEs,
            'end' => $eSPEe,
            'class' => 'estimated-msb',
        );
        if (!$secu->isGranted('ROLE_TYPE_CLIENT')) {
            $data[] = array(
                'label' => '<span class="estimated-iof-label" >Estimated IOF</span>',
                'start' => $eIOFs,
                'end' => $eIOFe,
                'class' => 'estimated-iof',
            );
            $data[] = array(
                'label' => '<span class="estimated-fw-end-label" >Estimated Fieldwork</span>',
                'start' => $eFieldworks,
                'end' => $eFieldworke,
                'class' => 'estimated-fw-end',
            );
            $data[] = array(
                'label' => '<span class="estimated-fw-end-label" >Estimated Editing</span>',
                'start' => $eEditings,
                'end' => $eEditinge,
                'class' => 'estimated-fw-end',
            );
            $data[] = array(
                'label' => '<span class="estimated-qc-label" >Estimated Quality Control</span>',
                'start' => $eQcs,
                'end' => $eQce,
                'class' => 'estimated-qc',
            );
        }
        $data[] = array(
            'label' => '<span class="estimated-report-label" >Estimated Report Delivery</span>',
            'start' => $eReports,
            'end' => $eReporte,
            'class' => 'estimated-report',
        );
        $data[] = array(
            'label' => '<span class="estimated-balance-label" >Estimated Balance invoiced</span>',
            'start' => $eBalances,
            'end' => $eBalancee,
            'class' => 'estimated-balance',
        );

        $data[] = array(
            'label' => '<span ' . $style . '>Actual Contract signed</span>',
            'start' => '',
            'end' => '',
            'class' => 'estimated-contract',
        );
        $data[] = array(
            'label' => '<span ' . $style . '>Actual Deposit invoiced</span>',
            'start' => '',
            'end' => '',
            'class' => 'estimated-deposit',
        );
        $data[] = array(
            'label' => '<span ' . $style . '>Actual Validate Questionnaire</span>',
            'start' => $questionnaireStart,
            'end' => $questionnaireEnd,
            'class' => 'estimated-questionnaire',
        );
        $data[] = array(
            'label' => '<span ' . $style . '>Actual POS list</span>',
            'start' => $posTimeStart,
            'end' => $posTimeEnd,
            'class' => 'estimated-pos',
        );
        $data[] = array(
            'label' => '<span ' . $style . '>Actual Staff list</span>',
            'start' => $stafflistStart,
            'end' => $stafflistEnd,
            'class' => 'estimated-staff',
        );
        $data[] = array(
            'label' => '<span ' . $style . '>Actual Shopper profile</span>',
            'start' => $shopperprofileStart,
            'end' => $shopperprofileEnd,
            'class' => 'estimated-shopper',
        );
        $data[] = array(
            'label' => '<span ' . $style . '>Actual Brand Guidelines</span>',
            'start' => $brandguidelinesStart,
            'end' => $brandguidelinesEnd,
            'class' => 'estimated-bm',
        );
        $data[] = array(
            'label' => '<span ' . $style . '>Actual SPE Brief</span>',
            'start' => $spebriefStart,
            'end' => $spebriefEnd,
            'class' => 'estimated-msb',
        );
        if (!$secu->isGranted('ROLE_TYPE_CLIENT')) {
            $data[] = array(
                'label' => '<span ' . $style . '>Actual IOF</span>',
                'start' => $iofActualStart,
                'end' => $iofActualEnd,
                'class' => 'estimated-iof',
            );
            $data[] = array(
                'label' => '<span ' . $style . '>Actual Fieldwork</span>',
                'start' => $questionendtime,
                'end' => $recapDateActual,
                'class' => 'estimated-fw-end',
            );
            $data[] = array(
                'label' => '<span ' . $style . '>Actual Editing</span>',
                'start' => '',
                'end' => '',
                'class' => 'estimated-fw-end',
            );
            $data[] = array(
                'label' => '<span ' . $style . '>Actual Quality Control</span>',
                'start' => $qcTimeStart,
                'end' => $qcTimeEnd,
                'class' => 'estimated-qc',
            );
        }
        $data[] = array(
            'label' => '<span ' . $style . '>Actual Report Delivery</span>',
            'start' => $reportTimeStart,
            'end' => $reportTimeEnd,
            'class' => 'estimated-report',
        );
        $data[] = array(
            'label' => '<span ' . $style . '>Actual Balance invoiced</span>',
            'start' => '',
            'end' => '',
            'class' => 'estimated-balance',
        );

        return $data;
    }

    public function uploadIofAction($wid) {//old version
        $attachmentsOption = $this->container->getParameter('valuelist');
        $attachmentsOption['attachments']['type'] = array('0' => 'IOF');
        $attachmentsForm = $this->createForm(new AttachmentsType(), $attachmentsOption);
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AlbatrossCustomBundle:Customwave')->find($wid);
        return $this->render('AlbatrossCustomBundle:Customproject:uploadIOF.html.twig', array(
                    'attachmentsForm' => $attachmentsForm->createView(),
                    'wid' => $entity->getId(),
                    'pid' => $entity->getCustomproject()->getId(),
                    'cid' => $entity->getCustomproject()->getCustomclient()->getId()
        ));
    }

    public function iofeditAction($id) {
        $em = $this->getDoctrine()->getManager();
        $IOFEntity = $em->getRepository('AlbatrossAceBundle:Attachments')->find($id);
        $wid = $IOFEntity->getCustomwave()->getId();
        $waveQb = $em->createQueryBuilder();
        $waveQb->select('wave', 'customproject', 'customclient')
                ->from('AlbatrossCustomBundle:Customwave', 'wave')
                ->leftJoin('wave.customproject', 'customproject')
                ->leftJoin('customproject.customclient', 'customclient')
                ->where('wave.id = :wid')
                ->setParameter('wid', $wid);
        $waveQuery = $waveQb->getQuery();
        $waveArr = $waveQuery->getArrayResult();

        $IOFFileEntity = $IOFEntity->getIoffile()->toArray();
        $IOFInfoEntity = $IOFEntity->getAttachinfo()->toArray();

        $result = array();

        foreach ($IOFFileEntity as $key => $files) {
            $result[$files->getFormindex()]['fileinfo'][$files->getFormindex2()]['file'][$key]['label'] = $files->getLabel();
            $result[$files->getFormindex()]['fileinfo'][$files->getFormindex2()]['file'][$key]['path'] = $files->getPath();
            $result[$files->getFormindex()]['fileinfo'][$files->getFormindex2()]['file'][$key]['fid'] = $files->getId();
            $result[$files->getFormindex()]['fileinfo'][$files->getFormindex2()]['message'] = $files->getIofmessage()->getMessage();
            $result[$files->getFormindex()]['fileinfo'][$files->getFormindex2()]['mid'] = $files->getIofmessage()->getId();
        }
        $selectedArr = array();
        foreach ($IOFInfoEntity as $key => $info) {
            $selectedArr[$info->getFormindex()][] = $info->getBu()->getName() . $info->getProject()->getId();
        }
        $iofHtml = '';
        foreach ($result as $key => $r) {
            $iofHtml .= $this->getIOFFormHead($key);
            foreach ($r['fileinfo'] as $key2 => $info) {
                $iofHtml .= $this->getIOFFormFileLabelHead($key2);
                $fileindex = 1;
                foreach ($info['file'] as $key3 => $file) {
                    if ($fileindex == 1) {
                        $iofHtml .= $this->getIOFFormFileLableBodyForEdit1($key, $key2, $key3, $file['label'], $file['fid']);
                    } else {
                        $iofHtml .= $this->getIOFFormFileLableBodyForEdit2($key, $key2, $key3, $file['label'], $file['fid']);
                    }
                    $fileindex++;
                }
                $iofHtml .= $this->getIOFFormFileLabelTailEdit($key, $key2, $info['message'], $info['mid']);
            }
            $iofHtml .= $this->getIOFFileMessageAddTr($key);
            $iofHtml .= $this->getIOFInfoHtmlEdit($wid, $key, $selectedArr);
            $iofHtml .= $this->getIOFFormTail();
        }
        $iofHtml .= $this->getIOFFormSubmit($wid);
        $pmid = $IOFEntity->getUser()->getId();
        if ($IOFEntity->getSubmitby()) {
            $submitby = $IOFEntity->getSubmitby();
            $user = $em->getRepository('AlbatrossUserBundle:User')->find($submitby);
        } else {
            $user = $IOFEntity->getUser();
        }

        $userSelect = $this->getUserSelectionHtml();
        return $this->render('AlbatrossCustomBundle:Customproject:IOFupload.html.twig', array(
                    'wave' => $waveArr[0],
                    'user' => $user,
                    'iofHtml' => $iofHtml,
                    'iofid' => $id,
                    'pmHtml' => $userSelect,
                    'type' => 'edit',
                    'pmid' => $pmid
        ));
    }

    public function iofuploadAction($wid) {
        $em = $this->getDoctrine()->getManager();
        $waveQb = $em->createQueryBuilder();
        $waveQb->select('wave', 'customproject', 'customclient')
                ->from('AlbatrossCustomBundle:Customwave', 'wave')
                ->leftJoin('wave.customproject', 'customproject')
                ->leftJoin('customproject.customclient', 'customclient')
                ->where('wave.id = :wid')
                ->setParameter('wid', $wid);
        $waveQuery = $waveQb->getQuery();
        $waveArr = $waveQuery->getArrayResult();

        $secu = $this->container->get('security.context');
        $user = $secu->getToken()->getUser();
        $iofHtml = $this->IOFStractureHtmlAction(1, $wid);
        $userSelect = $this->getUserSelectionHtml();
        return $this->render('AlbatrossCustomBundle:Customproject:IOFupload.html.twig', array(
                    'wave' => $waveArr[0],
                    'user' => $user,
                    'iofHtml' => $iofHtml,
                    'pmHtml' => $userSelect,
                    'type' => 'create'
        ));
    }

    protected function getUserSelectionHtml() {
        $em = $this->getDoctrine()->getManager();
        $userQb = $em->createQueryBuilder();
        $userQb->select('pm')
                ->from('AlbatrossUserBundle:User', 'pm')
                ->where('pm.status = :active');
        $userQb->setParameter('active', 'active');
        $userQuery = $userQb->getQuery();
        $users = $userQuery->getArrayResult();
        $result = '<select id="pm-select" name="pm">';
        foreach ($users as $user) {
            $result .= '<option value="' . $user['id'] . '">' . $user['username'] . '</option>';
        }
        return $result . '</select>';
    }

    public function IOFStractureHtmlAction($index = null, $wid = null) {
        if ($index == null) {
            $ajax = $this->getRequest()->getContent();
            $ajaxArr = json_decode($ajax, true);
            $index = $ajaxArr['index'];
            $wid = $ajaxArr['wid'];
        }

        $result = $this->getIOFFormHead($index);
        $result .= $this->getFileMessageHtmlAction($index, 1);
        $result .= $this->getIOFFileMessageAddTr($index);
        $result .= $this->getIOFInfoHtml($wid, $index);

        $result .= $this->getIOFFormTail();
        if ($index == 1) {
            $result .= $this->getIOFFormSubmit($wid);
        } else {
            return new Response($result);
        }
        return $result;
    }

    //first of IOF Form 1
    protected function getIOFFormHead($index) {
        $result = '<table id="iof-form-' . $index . '" class="iof-form"><tr><th class="form-title">FORM ' . $index . '</th></tr>';
        return $result;
    }

    //file label message part
    protected function getIOFFormFileLabelHead($index2) {
        $result = '<tr class="file-message-clone-' . $index2 . '"><td><table class="file-message-table"><tr>' .
                '<th id="file-message-' . $index2 . '" class="file-message-td">FILE&MESSAGE -' . $index2 . '</th></tr>' .
                '<tr><td><table class="file-iof-table">';
        return $result;
    }

    protected function getIOFFormFileLableBody($index, $index2) {
        $result = '';
        $result .= '<tr><th>FILE:</th><td>' .
                '<input type="file" onchange="adaptValue(this);" name="iof-file[' . $index . '][' . $index2 . '][]" />' .
                '</td><th>LABEL:</th><td><input type="text" class="iof-label" name="iof-label[' . $index . '][' . $index2 . '][]"></td>' .
                '<th><div id="' . $index2 . '-add-file-button-' . $index . '" onclick="addfile(this);" class="add-file-button"></div>' .
                '<div id="delete-file-button-' . $index . '" onclick="deletefile(this);" class="delete-file-button">' .
                '</div></th></tr>';
        return $result;
    }

    //for editpart first
    protected function getIOFFormFileLableBodyForEdit1($index, $index2, $key3, $label, $labelId) {
        $result = '';
        $result .= '<tr><th>FILE:</th><td>' .
                '<input type="file" onchange="adaptValue(this);" name="iof-file[' . $index . '][' . $index2 . '][' . $key3 . ']" />' .
                '</td><th>LABEL:</th><td><input type="text" class="iof-label" name="iof-label[' . $index . '][' . $index2 . '][' . $key3 . ']" value="' . $label . '">' .
                '<input type="text" style="display:none;" name="iof-label-id[' . $index . '][' . $index2 . '][' . $key3 . ']" value="' . $labelId . '"></td>' .
                '<th><div id="' . $index2 . '-add-file-button-' . $index . '" onclick="addfile(this);" class="add-file-button"></div>' .
                '<div id="delete-file-button-' . $index . '" onclick="deletefile(this);" class="delete-file-button">' .
                '</div></th></tr>';
        return $result;
    }

    //for editpart not first
    protected function getIOFFormFileLableBodyForEdit2($index, $index2, $key3, $label, $labelId) {
        $result = '';
        $result .= '<tr><th>FILE:</th><td>' .
                '<input type="file" onchange="adaptValue(this);" name="iof-file[' . $index . '][' . $index2 . '][' . $key3 . ']" />' .
                '</td><th>LABEL:</th><td><input type="text" class="iof-label" name="iof-label[' . $index . '][' . $index2 . '][' . $key3 . ']" value="' . $label . '">' .
                '<input type="text" style="display:none;" name="iof-label-id[' . $index . '][' . $index2 . '][' . $key3 . ']" value="' . $labelId . '"></td>' .
                '<th></th></tr>';
        return $result;
    }

    protected function getIOFFormFileLabelTail($index, $index2) { // message part
        $result = '</table></td></tr>' .
                '<tr><td><fieldset class="message-fieldset"><legend>MESSAGE</legend><textarea name="iof-text[' .
                $index . '][' . $index2 . ']"></textarea></fieldset></td></tr></table></td></tr>';
        return $result . '<script>CKEDITOR.replace("iof-text[' . $index . '][' . $index2 . ']");</script>';
    }

    protected function getIOFFormFileLabelTailEdit($index, $index2, $value, $messageId) { // message part
        $result = '</table></td></tr>' .
                '<tr><td><fieldset class="message-fieldset"><legend>MESSAGE</legend><textarea name="iof-text[' .
                $index . '][' . $index2 . ']">' . $value . '</textarea></fieldset>' .
                '<input type="text" style="display:none;" name="iof-text-id[' . $index . '][' . $index2 . ']" value="' . $messageId . '">' .
                '</td></tr></table></td></tr>';
        return $result . '<script>CKEDITOR.replace("iof-text[' . $index . '][' . $index2 . ']");</script>';
    }

    //file label message part end
    //file label message add 
    protected function getIOFFileMessageAddTr($index) {
        $result = '<tr><td class="add-index2-td" id="add-index2-td-' . $index .
                '" onmouseover="addFileMessageOver(this)" onmouseout="addFileMessageOut(this)" onclick="addFileMessageClick(this)">' .
                '<div id="add-index2-button">' .
                '<img height="20px" width="20px" src="/images/add_index.png" />' .
                '<span class="message-add-index2">ADD A NEW FILE AND MESSAGE FORM</span></div></td></tr><tr><td class="iof-info-td" id="iof-info-td-' . $index . '">';
        return $result;
    }

    //file label message add End
    protected function getIOFInfoHtml($wid, $index) {
        $iofInfo = $this->IOFTaskInfo($wid);
        $key = 0;
        $result = '';
        foreach ($iofInfo as $iof) {
            foreach ($iof as $i) {
                $result .= '<table class="form-info-table"><tr><th>BU</th><td>' . $i['bu'] .
                        '<input type="text" style="display:none;" value="' . $i['bu'] . '" name="iof-info['
                        . $index . '][bu][]" disabled="disabled"></td><th>ACE</th><td>'
                        . $i['project'] . '<input type="text" style="display:none;" value="' . $i['pid'] . '" name="iof-info['
                        . $index . '][project][]" disabled="disabled"></td></tr>';
                $result .= '<tr><th>Scope</th><td><input type="text" name="iof-info['
                        . $index . '][scope][]" value="' . $i['scope'] . '" disabled="disabled"></td>' .
                        '<th>Report Due Date</th><td><input type="text" class="calender reportinput reportdate" name="iof-info['
                        . $index . '][report][]" value="' . $i['due'] . '" disabled="disabled">' .
                        '<input type="text" class="reportinput reporttext" name="iof-info['
                        . $index . '][reporttext][]" value="" placeholder="Type Text" style="display:none;" disabled="disabled">' .
                        '<input type="text" name="iof-info['
                        . $index . '][reporttype][]" value="0" style="display:none;" disabled="disabled">' .
                        '<div class="change-date-report" onclick="changeReportDueType(this);" title="Exchange Date and Text"></div></td></tr>';
                $result .= '<tr><th>FW Start Date</th><td><input type="text" class="calender" name="iof-info['
                        . $index . '][fwstart][]" value="' . $i['fws'] . '" disabled="disabled"></td>' .
                        '<th>FW End Date</th><td><input type="text" class="calender" name="iof-info['
                        . $index . '][fwend][]" value="' . $i['fwe'] . '" disabled="disabled"></td></tr>';
                $result .= '<tr><th>Comment</th><td><input type="text" name="iof-info['
                        . $index . '][comment][]" value="' . $i['comment'] . '" disabled="disabled"></td>' .
                        '<th>Select this BU</th><td><input class="iof-info-checkbox" type="checkbox" onclick="changeInfoBack(this);" name="' . $key . '-iof-check-'
                        . $index . '[]" id="' . $key . '-iof-check-' . $index . '" /></td></tr></table>';
                $key++;
            }
        }
        return $result;
    }

    protected function getIOFInfoHtmlEdit($wid, $index, $selectedArr) {
        $iofInfo = $this->IOFEditInfo($wid);
        $key = 0;
        $result = '';
        foreach ($iofInfo as $iof) {
            foreach ($iof as $i) {
                $result .= '<table class="form-info-table"><tr><th>BU</th><td>' . $i['bu'] .
                        '<input type="text" style="display:none;" value="' . $i['bu'] . '" name="iof-info['
                        . $index . '][bu][]" disabled="disabled"></td><th>ACE</th><td>'
                        . $i['project'] . '<input type="text" style="display:none;" value="' . $i['pid'] . '" name="iof-info['
                        . $index . '][project][]" disabled="disabled"></td></tr>';
                $result .= '<tr><th>Scope</th><td><input type="text" name="iof-info['
                        . $index . '][scope][]" value="' . $i['scope'] . '" disabled="disabled"></td>';
                if ($i['type'] == 'date') {
                    $result .= '<th>Report Due Date</th><td><input type="text" class="calender reportinput reportdate" name="iof-info['
                            . $index . '][report][]" value="' . $i['due'] . '" disabled="disabled">' .
                            '<input type="text" class="reportinput reporttext" name="iof-info['
                            . $index . '][reporttext][]" value="" placeholder="Type Text" style="display:none;" disabled="disabled">' .
                            '<input type="text" class="reporttype" name="iof-info['
                            . $index . '][reporttype][]" value="0" style="display:none;" disabled="disabled">' .
                            '<div class="change-date-report" onclick="changeReportDueType(this);" title="Exchange Date and Text">' .
                            '</div></td></tr>';
                } else if ($i['type'] == 'text') {
                    $result .= '<th>Report Due Date</th><td><input type="text" class="calender reportinput reportdate" name="iof-info['
                            . $index . '][report][]" value="' . $i['due'] . '" disabled="disabled" style="display:none;">' .
                            '<input type="text" class="reportinput reporttext" name="iof-info['
                            . $index . '][reporttext][]" value="' . $i['duetext'] . '" placeholder="Type Text" disabled="disabled">' .
                            '<input type="text" class="reporttype" name="iof-info['
                            . $index . '][reporttype][]" value="1" style="display:none;" disabled="disabled">' .
                            '<div class="change-date-report" onclick="changeReportDueType(this);" title="Exchange Date and Text">' .
                            '</div></td></tr>';
                }
                $result .= '<tr><th>FW Start Date</th><td><input type="text" class="calender" name="iof-info['
                        . $index . '][fwstart][]" value="' . $i['fws'] . '" disabled="disabled"></td>' .
                        '<th>FW End Date</th><td><input type="text" class="calender" name="iof-info['
                        . $index . '][fwend][]" value="' . $i['fwe'] . '" disabled="disabled"></td></tr>';
                $result .= '<tr><th>Comment</th><td><input type="text" name="iof-info['
                        . $index . '][comment][]" value="' . $i['comment'] . '" disabled="disabled"></td>';
                if (in_array($i['bu'] . $i['pid'], $selectedArr[$index])) {
                    $result .= '<th>Select this BU</th><td><input class="iof-info-checkbox" type="checkbox" checked="checked" onclick="changeInfoBack(this);" name="' . $key . '-iof-check-'
                            . $index . '[]" id="' . $key . '-iof-check-' . $index . '" /></td></tr></table>' .
                            '<script>changeInfoBackForEdit("' . $key . '-iof-check-' . $index . '")</script>';
                } else {
                    $result .= '<th>Select this BU</th><td><input class="iof-info-checkbox" type="checkbox" onclick="changeInfoBack(this);" name="' . $key . '-iof-check-'
                            . $index . '[]" id="' . $key . '-iof-check-' . $index . '" /></td></tr></table>';
                }
                $key++;
            }
        }
        return $result . '<script>formShow("' . $index . '");iofInfoSelected("' . $index . '");</script>';
    }

    protected function getIOFFormTail() {
        $result = '</td></tr></table>';
        return $result;
    }

    protected function getIOFFormSubmit($wid) {
        $result = '<input style="display:none;" type="text" value="' . $wid . '" id="iof-form-waveid" />';
        $result .= '<input type="submit" id="iof-submit-button" value="SUBMIT" /><input type="button" value="" onclick="addIOFBigForm();" id="iof-form-add-big-button" />';
        return $result;
    }

    public function getFileMessageHtmlAction($index = null, $index2 = null) {
        $result = '';
        if ($index2 == null) {
            $indexStr = $this->getRequest()->getContent();
            $indexArr = json_decode($indexStr, true);
            $index = $indexArr['index1'];
            $index2 = $indexArr['index2'];
            $returnWay = 'ajax';
        } else {
            $returnWay = 'normal';
        }
        $result .= $this->getIOFFormFileLabelHead($index2);
        $result .= $this->getIOFFormFileLableBody($index, $index2);
        $result .= $this->getIOFFormFileLabelTail($index, $index2);
        if ($returnWay == 'normal') {
            return $result;
        } else {
            return new Response($result);
        }
    }

    protected function IOFEditInfo($wid) {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->add('select', 'info')
                ->add('from', 'AlbatrossAceBundle:Attachinfo info')
                ->leftJoin('info.attachments', 'iof')
                ->leftJoin('iof.customwave', 'w')
                ->where('w.id = :cw');
        $qb->setParameters(array(
            'cw' => $wid
        ));
        $query = $qb->getQuery();
        $result = $query->getResult();

        $resultArr = $this->IOFTaskInfo($wid);
        foreach ($result as $re) {
            if (isset($resultArr[$re->getProject()->getId()][$re->getBu()->getName()])) {
                $resultArr[$re->getProject()->getId()][$re->getBu()->getName()]['scope'] = $re->getScope();
                $resultArr[$re->getProject()->getId()][$re->getBu()->getName()]['fws'] = $re->getFwstartdate()->format('Y-m-d');
                $resultArr[$re->getProject()->getId()][$re->getBu()->getName()]['fwe'] = $re->getFwenddate()->format('Y-m-d');
                $resultArr[$re->getProject()->getId()][$re->getBu()->getName()]['comment'] = $re->getComment();
                $resultArr[$re->getProject()->getId()][$re->getBu()->getName()]['bu'] = $re->getBu()->getName();
                $resultArr[$re->getProject()->getId()][$re->getBu()->getName()]['project'] = $re->getProject()->getName();
                $resultArr[$re->getProject()->getId()][$re->getBu()->getName()]['pid'] = $re->getProject()->getId();
                $resultArr[$re->getProject()->getId()][$re->getBu()->getName()]['due'] = $re->getReportduedate()->format('Y-m-d');
                $resultArr[$re->getProject()->getId()][$re->getBu()->getName()]['duetext'] = ($re->getReportduedatetext() != null) ? $re->getReportduedatetext() : '';

                if (!$re->getReporttype()) {
                    $resultArr[$re->getProject()->getId()][$re->getBu()->getName()]['type'] = 'date';
                } else {
                    $resultArr[$re->getProject()->getId()][$re->getBu()->getName()]['type'] = 'text';
                }
            }
        }
        return $resultArr;
    }

    protected function IOFTaskInfo($wid) {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->add('select', 't, p')
                ->add('from', 'AlbatrossAceBundle:Task t')
                ->leftJoin('t.project', 'p')
                ->leftJoin('p.customwave', 'w')
                ->where('t.number BETWEEN 101 AND 116 OR t.number = 600')
                ->andWhere('w.id = :cw');
        $qb->setParameters(array(
            'cw' => $wid
        ));
        $query = $qb->getQuery();
        $result = $query->getArrayResult();

        $buArr = $this->getBuArr();
        $dueArr = array();
        foreach ($result as $list) {
            if ($list['number'] == 600) {
                $dueArr[$list['project']['id']] = $list['reportduedate'];
            }
        }
        $resultArr = array();
        foreach ($result as $list) {
            if ($list['number'] >= 101 && $list['number'] <= 116) {
                $resultArr[$list['project']['id']][$buArr[$list['number'] - 100]]['scope'] = $list['scope'] ? strip_tags($list['scope']) : '0';
                $resultArr[$list['project']['id']][$buArr[$list['number'] - 100]]['fws'] = $list['fwstartdate'];
                $resultArr[$list['project']['id']][$buArr[$list['number'] - 100]]['fwe'] = $list['fwenddate'];
                $resultArr[$list['project']['id']][$buArr[$list['number'] - 100]]['comment'] = '';
                $resultArr[$list['project']['id']][$buArr[$list['number'] - 100]]['due'] = $dueArr[$list['project']['id']];
                $resultArr[$list['project']['id']][$buArr[$list['number'] - 100]]['bu'] = $buArr[$list['number'] - 100];
                $resultArr[$list['project']['id']][$buArr[$list['number'] - 100]]['project'] = $list['project']['name'];
                $resultArr[$list['project']['id']][$buArr[$list['number'] - 100]]['pid'] = $list['project']['id'];
                $resultArr[$list['project']['id']][$buArr[$list['number'] - 100]]['type'] = 'date';
                $resultArr[$list['project']['id']][$buArr[$list['number'] - 100]]['duetext'] = '';
            }
        }

        return $resultArr;
    }

    public function IOFFileUploadAction($wid) {
        $data = $this->getRequest('post')->request->all();
        $files = $this->getRequest('post')->files->all();
        $date = date('ymd');
        $datetime = date('Y-m-d H:i:s');

        $em = $this->getDoctrine()->getManager();

        $secu = $this->container->get('security.context');
        $user = $secu->getToken()->getUser();
        $pm = $em->getRepository('AlbatrossUserBundle:User')->find($data['pm']);
        $waveEntity = $em->getRepository('AlbatrossCustomBundle:Customwave')->find($wid);
        $this->checkLastKaWave($waveEntity);
        //
        //iof Entity save part
        //
        $iofEntity = new Attachments();
        $iofEntity->setCustomwave($waveEntity);
        $iofEntity->setUser($pm);
        $iofEntity->setSubmitby($user->getId());
        $iofEntity->setChildren(0);
        $iofEntity->setStatus('approved');
        $iofEntity->setSubmitteddate(new \DateTime($datetime));
        $iofEntity->setType('0');
        $em->persist($iofEntity);
        //
        //iof Entity save part end
        //
        if ($files['iof-file'] != null) {
            foreach ($files['iof-file'] as $index1 => $fileArr1) {
                $infoLength = count($data['iof-info'][$index1]['bu']);

                //
                //iof information save part
                for ($infoIndex = 0; $infoIndex < $infoLength; $infoIndex++) {
                    $iofInfoEntity = new Attachinfo();
                    $buEntity = $em->getRepository('AlbatrossAceBundle:Bu')->findByName($data['iof-info'][$index1]['bu'][$infoIndex]);
                    $projectEntity = $em->getRepository('AlbatrossAceBundle:Project')->find($data['iof-info'][$index1]['project'][$infoIndex]);

                    $iofInfoEntity->setBu($buEntity[0]);
                    $iofInfoEntity->setProject($projectEntity);
                    $iofInfoEntity->setAttachments($iofEntity);
                    $iofInfoEntity->setComment($data['iof-info'][$index1]['comment'][$infoIndex]);
                    $iofInfoEntity->setFwstartdate(new \DateTime($data['iof-info'][$index1]['fwstart'][$infoIndex]));
                    $iofInfoEntity->setFwenddate(new \DateTime($data['iof-info'][$index1]['fwend'][$infoIndex]));
                    $iofInfoEntity->setFormindex($index1);
                    if ($data['iof-info'][$index1]['reporttype'][$infoIndex] == 0) {
                        $iofInfoEntity->setReporttype(0);
                    } else if ($data['iof-info'][$index1]['reporttype'][$infoIndex] == 1) {
                        $iofInfoEntity->setReporttype(1);
                    }
                    $iofInfoEntity->setReportduedate(new \DateTime($data['iof-info'][$index1]['report'][$infoIndex]));
                    $iofInfoEntity->setReportduedatetext($data['iof-info'][$index1]['reporttext'][$infoIndex]);
                    $iofInfoEntity->setScope($data['iof-info'][$index1]['scope'][$infoIndex]);
                    $em->persist($iofInfoEntity);
                    //iof information save part end
                //
                }

                foreach ($fileArr1 as $index2 => $fileArr2) {
                    //
                    //iof message save part
                    //
                    $messageEntity = new IOFMessage();
                    $messageEntity->setFormindex($index1);
                    $messageEntity->setFormindex2($index2);
                    $messageEntity->setMessage($data['iof-text'][$index1][$index2]);
                    $messageEntity->setAttachments($iofEntity);
                    $em->persist($messageEntity);
                    //
                    //iof message save part end
                    //
                    foreach ($fileArr2 as $index3 => $file) {
                        //
                        //iof file save part
                        //
                        $filename = $file->getClientOriginalName();
                        $file->move($this->get('kernel')->getRootDir() . '/../web/projectFiles/' . $date . '/', $filename);
                        //get path info
                        $path = 'projectFiles/' . $date . '/' . $filename;
                        $fileEntity = new IOFFile();
                        $fileEntity->setAttachments($iofEntity);
                        $fileEntity->setIofmessage($messageEntity);
                        $fileEntity->setFormindex($index1);
                        $fileEntity->setFormindex2($index2);
                        $fileEntity->setPath($path);
                        $fileEntity->setLabel($data['iof-label'][$index1][$index2][$index3]);
                        $em->persist($fileEntity);
                        //
                        //iof file save part end
                    //
                    }
                }
            }
        }
        $em->flush();

        return $this->redirect($this->generateUrl('viewiof', array(
                            'id' => $iofEntity->getId(),
                            'status' => $iofEntity->getStatus(),
        )));
    }

    public function IOFFileEditAction($id) {
        $data = $this->getRequest('post')->request->all();
        $files = $this->getRequest('post')->files->all();
        $date = date('ymd');
        $datetime = date('Y-m-d H:i:s');
        $em = $this->getDoctrine()->getManager();

        $secu = $this->container->get('security.context');
        $user = $secu->getToken()->getUser();
        $pm = $em->getRepository('AlbatrossUserBundle:User')->find($data['pm']);
        //
        //iof Entity save part
        $iofEntity = $em->getRepository('AlbatrossAceBundle:Attachments')->find($id);
        $iofEntity->setUser($pm);
        $iofEntity->setSubmitby($user->getId());
        $iofEntity->setSubmitteddate(new \DateTime($datetime));
        $em->persist($iofEntity);
        //iof Entity save part end
        //
        $this->deleteAttachInfoByAttachmentId($iofEntity->getId());
        foreach ($files['iof-file'] as $index1 => $fileArr1) {
            $infoLength = count($data['iof-info'][$index1]['bu']);
            //
            //iof information save part
            for ($infoIndex = 0; $infoIndex < $infoLength; $infoIndex++) {
                $iofInfoEntity = new Attachinfo();
                $buEntity = $em->getRepository('AlbatrossAceBundle:Bu')->findByName($data['iof-info'][$index1]['bu'][$infoIndex]);
                $projectEntity = $em->getRepository('AlbatrossAceBundle:Project')->find($data['iof-info'][$index1]['project'][$infoIndex]);

                $iofInfoEntity->setBu($buEntity[0]);
                $iofInfoEntity->setProject($projectEntity);
                $iofInfoEntity->setAttachments($iofEntity);
                $iofInfoEntity->setComment($data['iof-info'][$index1]['comment'][$infoIndex]);
                $iofInfoEntity->setFwstartdate(new \DateTime($data['iof-info'][$index1]['fwstart'][$infoIndex]));
                $iofInfoEntity->setFwenddate(new \DateTime($data['iof-info'][$index1]['fwend'][$infoIndex]));
                $iofInfoEntity->setFormindex($index1);
                if ($data['iof-info'][$index1]['reporttype'][$infoIndex] == 0) {
                    $iofInfoEntity->setReporttype(0);
                } else if ($data['iof-info'][$index1]['reporttype'][$infoIndex] == 1) {
                    $iofInfoEntity->setReporttype(1);
                }
                $iofInfoEntity->setReportduedate(new \DateTime($data['iof-info'][$index1]['report'][$infoIndex]));
                $iofInfoEntity->setReportduedatetext($data['iof-info'][$index1]['reporttext'][$infoIndex]);
                $iofInfoEntity->setScope($data['iof-info'][$index1]['scope'][$infoIndex]);
                $em->persist($iofInfoEntity);
            }
            //iof information save part end
            //
            //================================================================//
            foreach ($fileArr1 as $index2 => $fileArr2) {
                //iof message save part
                if (isset($data['iof-text-id'][$index1][$index2])) {
                    $messageEntity = $em->getRepository('AlbatrossAceBundle:IOFMessage')->find($data['iof-text-id'][$index1][$index2]);
                } else {
                    $messageEntity = new IOFMessage();
                    $messageEntity->setFormindex($index1);
                    $messageEntity->setFormindex2($index2);
                    $messageEntity->setAttachments($iofEntity);
                }
                $messageEntity->setMessage($data['iof-text'][$index1][$index2]);
                $em->persist($messageEntity);
                //iof message save part end
                foreach ($fileArr2 as $index3 => $file) {
                    //iof file save part
                    if (isset($data['iof-label-id'][$index1][$index2][$index3])) {
                        $fileEntity = $em->getRepository('AlbatrossAceBundle:IOFFile')->find($data['iof-label-id'][$index1][$index2][$index3]);
                    } else {
                        $fileEntity = new IOFFile();
                        $fileEntity->setAttachments($iofEntity);
                        $fileEntity->setIofmessage($messageEntity);
                        $fileEntity->setFormindex($index1);
                        $fileEntity->setFormindex2($index2);
                    }
                    if ($file != null) {
                        $filename = $file->getClientOriginalName();
                        $file->move($this->get('kernel')->getRootDir() . '/../web/projectFiles/' . $date . '/', $filename);
                        //get path info
                        $path = 'projectFiles/' . $date . '/' . $filename;
                        $fileEntity->setPath($path);
                    }
                    $fileEntity->setLabel($data['iof-label'][$index1][$index2][$index3]);
                    $em->persist($fileEntity);
                    //iof file save part end
                }
            }
        }
        $em->flush();

        return $this->redirect($this->generateUrl('viewiof', array(
                            'id' => $iofEntity->getId(),
                            'status' => $iofEntity->getStatus(),
        )));
    }

    //when edit first delete all the attachinfo belong to this attachment
    protected function deleteAttachInfoByAttachmentId($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AlbatrossAceBundle:Attachinfo')->findByAttachments($id);
        foreach ($entity as $e) {
            $em->remove($e);
        }
        $em->flush();
        return;
    }

    public function saveFileAndMessageForPreAction() {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('iof')
                ->from('AlbatrossAceBundle:Attachments', 'iof')
                ->where('iof.type = 0');
        $query = $qb->getQuery();
        $entities = $query->getResult();
        foreach ($entities as $entity) {
            $infos = $entity->getAttachinfo()->toArray();
            foreach ($infos as $info) {
                $info->setFormindex('1');
                $em->persist($info);
            }
            $message = new IOFMessage();
            $message->setAttachments($entity);
            $message->setFormindex('1');
            $message->setFormindex2('1');
            $message->setMessage($entity->getMessage());
            $em->persist($message);
            $file = new IOFFile();
            $file->setAttachments($entity);
            $file->setFormindex('1');
            $file->setFormindex2('1');
            $file->setLabel($entity->getLabel());
            $file->setPath($entity->getPath());
            $file->setIofmessage($message);
            $em->persist($file);
        }
        $em->flush();
        var_dump('finish');
        exit();
    }

    public function showposlistAction() {
        $id = $this->getRequest()->getContent();
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();

        $qb->select('pd')
                ->from('AlbatrossCustomBundle:Poslistdata', 'pd')
                ->leftJoin('pd.poslist', 'p')
                ->leftJoin('p.customwave', 'c')
                ->where('c.id = :cid');
        $qb->setParameters(array(
            'cid' => $id
        ));

        $query = $qb->getQuery();
        $entity = $query->getArrayResult();

        return $this->render('AlbatrossCustomBundle:Customproject:poslist.html.twig', array(
                    'entities' => $entity
        ));
    }

    public function downloadPOSAction($wid) {
        $em = $this->getDoctrine()->getManager();
        $file_entity = $em->getRepository('AlbatrossCustomBundle:Poslist')->findOneByCustomwave($wid);

        $file_dir = $file_entity->getPath();
        $file_arr = explode('/', $file_dir);

        $file_name = str_replace(' ', '%20', $file_arr['3']);
        if (!file_exists($file_dir)) {
            header("Content-type: text/html; charset=utf-8");
            echo "File not found!";
            exit;
        } else {
            $file = fopen($file_dir, "r");
            $file_size = filesize($file_dir);
            Header("Content-type: application/octet-stream");
            Header("Accept-Ranges: bytes");
            Header("Accept-Length: " . $file_size);
            Header("Content-Disposition: attachment; filename=" . $file_name);
            $contents = fread($file, $file_size);
            echo $contents;
            fclose($file);
            exit();
        }
    }

    public function checkProjectNameAction() {
        $data = $this->getRequest()->getContent();
        $dataArr = json_decode($data, true);
        $name = $dataArr['client'] . '_' . $dataArr['type'] . '_' . $dataArr['scope'];
        $name2 = $dataArr['client'] . '_' . lcfirst($dataArr['type']) . '_' . $dataArr['scope'];
        $em = $this->getDoctrine()->getManager();
        if (($em->getRepository('AlbatrossCustomBundle:Customproject')->findOneByName($name) == null) && ($em->getRepository('AlbatrossCustomBundle:Customproject')->findOneByName($name2) == null)) {
            return new Response('1');
        } else {
            return new Response('0');
        }
    }

}