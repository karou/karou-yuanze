<?php

namespace Albatross\ProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Albatross\ProjectBundle\Form\CustomprojectType;

class DefaultController extends Controller {

    public function indexAction() {
        $searchArr = array('client' => '', 'type' => '', 'bu' => '', 'project_manager' => '');

        $scopeAndTypeOption = $this->container->getParameter('valuelist');
        $busArr = $scopeAndTypeOption['scope'];
        $arrType = $scopeAndTypeOption['type'];

        $client_id = '';
        if (isset($_POST['client']) and is_array($_POST['client'])) {
            $client_id = implode(',', $_POST['client']);
            $searchArr['client'] = $client_id;
        }
        $bu_id = '';
        if (isset($_POST['bu']) and is_array($_POST['bu'])) {
            $bu_id = implode(',', $_POST['bu']);
            $searchArr['bu'] = $bu_id;
        }
        if (isset($_POST['type']) and is_array($_POST['type'])) {
            $type = implode(',', $_POST['type']);
            $searchArr['type'] = $type;
        }

        $list_bu = $list_type = $list_pm = '';
        if (is_array($busArr) and count($busArr) > 0) {
            foreach ($busArr as $key => $bu) {
                $sel = '';
                if (isset($_POST['bu']) and is_array($_POST['bu']) and in_array($key, $_POST['bu']))
                    $sel = ' selected="selected"';
                $list_bu .= '<option value="' . $key . '" ' . $sel . '>' . $bu . '</option>';
            }
        }
        foreach ($arrType as $keyType => $type) {
            $sel = '';
            if (isset($_POST['type']) and is_array($_POST['type']) and in_array($keyType, $_POST['type']))
                $sel = ' selected="selected"';
            $list_type .= '<option value="' . $keyType . '" ' . $sel . '>' . $type . '</option>';
        }
        $project_manager = $this->getProjectManager();
        if (!empty($project_manager)) {
            foreach ($project_manager as $pm) {
                $sel = '';
                if (isset($_POST['pm']) and is_array($_POST['pm']) and in_array($pm['id'], $_POST['client']))
                    $sel = ' selected="selected"';
                $list_pm .= '<option value="' . $pm['id'] . '" ' . $sel . '>' . $pm['fullname'] . '</option>';
            }
        }

        return $this->render('ProjectBundle:Default:index.html.twig', array(
                    'list_bu' => $list_bu,
                    'list_type' => $list_type,
                    'list_pm' => $list_pm,
                    'searchArr' => $searchArr
        ));
    }

    public function newAction() {
        $scopeAndTypeOption = $this->container->getParameter('valuelist');

        $form = $this->createForm(new CustomprojectType(), $scopeAndTypeOption);

        return $this->render('ProjectBundle:Default:new.html.twig', array(
                    'form' => $form->createView(),
        ));
    }

    public function viewAction() {
        if (!isset($_REQUEST['id']) or $_REQUEST['id'] == '') {
            return $this->redirect($this->generateUrl('list_project'), 301);
        }

        $em = $this->getDoctrine()->getManager();

        $id = $_REQUEST['id'];

        $parammeter = $this->container->getParameter('valuelist');
        $language = $parammeter['language'];
        $step = $parammeter['questionnaire'];
        $material = $parammeter['brandmaterial'];

        $ROOT_PATH = 'D:/wamp/www/roleplayapp1/';

        //////for meeting minutes form///////
        if (isset($_POST['action']) and $_POST['action'] == 'meetingMinutes') {
            $connection = $em->getConnection();

            /////////check if already added//////////
            $sql = 'select id from customfield where customwave_id="' . $_POST['wave_id'] . '" and user_id="' . $this->getUser()->getId() . '" and fieldtype="mm"';
            $statement = $connection->prepare($sql);
            $statement->execute();
            $resultMM = $statement->fetchAll();
            $countTotal = count($resultMM);

            if ($countTotal > 0) {
                $sql = 'update customfield set mm_brand="' . addslashes($_POST['mm_brand']) . '",mm_date="' . addslashes($_POST['mm_date']) . '",mm_address="' . addslashes($_POST['mm_address']) . '",mm_purpose="' . addslashes($_POST['mm_purpose']) . '",mm_nextstep="' . addslashes($_POST['mm_nextstep']) . '",mm_agenda_of_the_meeting="' . addslashes($_POST['mm_agenda_of_the_meeting']) . '",mm_clients_feedback="' . addslashes($_POST['mm_clients_feedback']) . '",mm_comments="' . addslashes($_POST['mm_comments']) . '" where id="' . $resultMM[0]['id'] . '"';
            } else {
                $sql = 'insert into customfield(customwave_id,user_id,mm_brand,mm_date,mm_address,mm_purpose,mm_nextstep,mm_agenda_of_the_meeting,mm_clients_feedback,mm_comments,fieldtype,submittime) values(' . $_POST['wave_id'] . ',' . $this->getUser()->getId() . ',"' . addslashes($_POST['mm_brand']) . '","' . addslashes($_POST['mm_date']) . '","' . addslashes($_POST['mm_address']) . '","' . addslashes($_POST['mm_purpose']) . '","' . addslashes($_POST['mm_nextstep']) . '","' . addslashes($_POST['mm_agenda_of_the_meeting']) . '","' . addslashes($_POST['mm_clients_feedback']) . '","' . addslashes($_POST['mm_comments']) . '","mm","' . date('Y-m-d') . '")';
            }
            $statement = $connection->prepare($sql);
            $statement->execute();

            return $this->redirect($this->generateUrl('view_project') . '?id=' . $id . '&msg=success&type=mm', 301);
        }

        //////for brand material form///////
        if (isset($_POST['action']) and $_POST['action'] == 'brand') {
            $connection = $em->getConnection();
            //echo '<pre>';print_r($_POST);print_r($_FILES);exit;
            $path = $updatePathQry = '';
            if (isset($_FILES['fileBrand'])) {
                if (!is_dir($ROOT_PATH . "web/Material/" . date('ymd'))) {
                    mkdir($ROOT_PATH . "web/Material/" . date('ymd'), 0777);
                }

                $this->save_image($_FILES['fileBrand']['name'], "fileBrand", $ROOT_PATH . "web/Material/" . date('ymd') . "/");

                $path = "Material/" . date('ymd') . "/" . str_replace(' ', '_', $_FILES['fileBrand']['name']);
                $updatePathQry = ',path="' . $path . '"';
            }

            /////////check if already added//////////
            $sql = 'select id from customfield where customwave_id="' . $_POST['wave_id'] . '" and user_id="' . $this->getUser()->getId() . '" and fieldtype="material"';
            $statement = $connection->prepare($sql);
            $statement->execute();
            $resultM = $statement->fetchAll();
            $countTotal = count($resultM);

            if ($countTotal > 0) {
                $sql = 'update customfield set material_name="' . addslashes($_POST['material_name']) . '"' . $updatePathQry . ' where id="' . $resultM[0]['id'] . '"';
            } else {
                $sql = 'insert into customfield(customwave_id,user_id,material_name,path,fieldtype,submittime) values(' . $_POST['wave_id'] . ',' . $this->getUser()->getId() . ',"' . addslashes($_POST['material_name']) . '","' . addslashes($path) . '","material","' . date('Y-m-d') . '")';
            }
            $statement = $connection->prepare($sql);
            $statement->execute();

            return $this->redirect($this->generateUrl('view_project') . '?id=' . $id . '&msg=success&type=brand', 301);
        }

        //////for kick off meeting form///////
        if (isset($_POST['action']) and $_POST['action'] == 'kick_off_meeting') {
            $connection = $em->getConnection();

            /////////check if already added//////////
            $sql = 'select id from meeting_recap where customwave_id="' . $_POST['wave_id'] . '"';
            $statement = $connection->prepare($sql);
            $statement->execute();
            $resultM = $statement->fetchAll();
            $countTotal = count($resultM);

            if ($countTotal > 0) {
                $sql = 'update meeting_recap set pm_attendee="' . addslashes($_POST['pm_attendee']) . '",op_attendee="' . addslashes($_POST['op_attendee']) . '",va_attendee="' . addslashes($_POST['va_attendee']) . '",qc_attendee="' . addslashes($_POST['qc_attendee']) . '",report_attendee="' . addslashes($_POST['report_attendee']) . '",text_1="' . addslashes($_POST['text_1']) . '",text_2="' . addslashes($_POST['text_2']) . '",text_3="' . addslashes($_POST['text_3']) . '",text_4="' . addslashes($_POST['text_4']) . '",text_5="' . addslashes($_POST['text_5']) . '",text_6="' . addslashes($_POST['text_6']) . '",text_7="' . addslashes($_POST['text_7']) . '",text_8="' . addslashes($_POST['text_8']) . '",text_9="' . addslashes($_POST['text_9']) . '",text_10="' . addslashes($_POST['text_10']) . '",text_11="' . addslashes($_POST['text_11']) . '",text_12="' . addslashes($_POST['text_12']) . '" where id="' . $resultM[0]['id'] . '"';
            } else {
                $sql = 'insert into meeting_recap(customwave_id,pm_attendee,op_attendee,va_attendee,qc_attendee,report_attendee,text_1,text_2,text_3,text_4,text_5,text_6,text_7,text_8,text_9,text_10,text_11,text_12) values(' . $_POST['wave_id'] . ',"' . addslashes($_POST['pm_attendee']) . '","' . addslashes($_POST['op_attendee']) . '","' . addslashes($_POST['va_attendee']) . '","' . addslashes($_POST['qc_attendee']) . '","' . addslashes($_POST['report_attendee']) . '","' . addslashes($_POST['text_1']) . '","' . addslashes($_POST['text_2']) . '","' . addslashes($_POST['text_3']) . '","' . addslashes($_POST['text_4']) . '","' . addslashes($_POST['text_5']) . '","' . addslashes($_POST['text_6']) . '","' . addslashes($_POST['text_7']) . '","' . addslashes($_POST['text_8']) . '","' . addslashes($_POST['text_9']) . '","' . addslashes($_POST['text_10']) . '","' . addslashes($_POST['text_11']) . '","' . addslashes($_POST['text_12']) . '")';
            }
            $statement = $connection->prepare($sql);
            $statement->execute();

            return $this->redirect($this->generateUrl('view_project') . '?id=' . $id . '&msg=success&type=kick_off', 301);
        }

        //////for questionnaire form///////
        if (isset($_POST['action']) and $_POST['action'] == 'questionnaire') {
            $connection = $em->getConnection();

            $wave_name_url = str_replace(' ', '_', $_POST['wave_name']);
            $wave_name_url = str_replace('&', 'n', $wave_name_url);
            //echo '<pre>';print_r($_POST);print_r($_FILES);exit;
            $path = $path_2 = $path_3 = $path_4 = $updatePathQry = '';
            $addNum = '';
            for ($x = 1; $x <= 4; $x++) {
                if ($x > 1)
                    $addNum = '_' . $x;

                if (isset($_FILES['path' . $addNum])) {
                    if (!is_dir($ROOT_PATH . "web/Questionnaire/" . date('ymd'))) {
                        mkdir($ROOT_PATH . "web/Questionnaire/" . date('ymd'), 0777);
                    }

                    if (!is_dir($ROOT_PATH . "web/Questionnaire/" . date('ymd') . "/" . $wave_name_url)) {
                        mkdir($ROOT_PATH . "web/Questionnaire/" . date('ymd') . "/" . $wave_name_url, 0777);
                    }

                    $this->save_image($_FILES['path' . $addNum]['name'], "path" . $addNum, $ROOT_PATH . "web/Questionnaire/" . date('ymd') . "/" . $wave_name_url . "/");

                    $path = "Questionnaire/" . date('ymd') . "/" . $wave_name_url . "/" . str_replace(' ', '_', $_FILES['path' . $addNum]['name']);
                    $updatePathQry .= ',path' . $addNum . '="' . $path . '"';
                }
            }

            /////////check if already added//////////
            $sql = 'select id from customfield where customwave_id="' . $_POST['wave_id'] . '" and user_id="' . $this->getUser()->getId() . '" and fieldtype="questionnaire"';
            $statement = $connection->prepare($sql);
            $statement->execute();
            $resultQ = $statement->fetchAll();
            $countTotal = count($resultQ);

            if ($countTotal > 0) {
                $sql = 'update customfield set customwave_id="' . addslashes($_POST['wave_id']) . '"' . $updatePathQry . ' where id="' . $resultQ[0]['id'] . '"';
            } else {
                $sql = 'insert into customfield(customwave_id,user_id,path,path_2,path_3,path_4,question_file1_label,question_file2_label,question_file3_label,question_file4_label,fieldtype,submittime) values(' . $_POST['wave_id'] . ',' . $this->getUser()->getId() . ',"' . addslashes($path) . '","' . addslashes($path_2) . '","' . addslashes($path_3) . '","' . addslashes($path_4) . '","' . addslashes($_POST['question_file1_label']) . '","' . addslashes($_POST['question_file2_label']) . '","' . addslashes($_POST['question_file3_label']) . '","' . addslashes($_POST['question_file4_label']) . '","questionnaire","' . date('Y-m-d') . '")';
            }
            $statement = $connection->prepare($sql);
            $statement->execute();

            return $this->redirect($this->generateUrl('view_project') . '?id=' . $id . '&msg=success&type=questionnaire', 301);
        }

        //////for pos file list form///////
        if (isset($_POST['action']) and $_POST['action'] == 'pos_file') {
            $connection = $em->getConnection();
            //echo '<pre>';print_r($_POST);print_r($_FILES);exit;
            $path = $updatePathQry = '';
            if (isset($_FILES['pos_list_file'])) {
                $wave_name_url = str_replace(' ', '_', $_POST['wave_name']);
                $wave_name_url = str_replace('&', 'n', $wave_name_url);

                if (!is_dir($ROOT_PATH . "web/PosList/" . date('ymd'))) {
                    mkdir($ROOT_PATH . "web/PosList/" . date('ymd'), 0777);
                }
                if (!is_dir($ROOT_PATH . "web/PosList/" . date('ymd') . "/" . $wave_name_url)) {
                    mkdir($ROOT_PATH . "web/PosList/" . date('ymd') . "/" . $wave_name_url, 0777);
                }

                $this->save_image($_FILES['pos_list_file']['name'], "pos_list_file", $ROOT_PATH . "web/PosList/" . date('ymd') . "/" . $wave_name_url . "/");

                $path = "PosList/" . date('ymd') . "/" . $wave_name_url . "/" . str_replace(' ', '_', $_FILES['pos_list_file']['name']);
                $updatePathQry .= ',path="' . $path . '"';
            }

            /////////check if already added//////////
            $sql = 'select id from poslist where customwave_id="' . $_POST['wave_id'] . '"';
            $statement = $connection->prepare($sql);
            $statement->execute();
            $resultPos = $statement->fetchAll();
            $countTotal = count($resultPos);

            if ($countTotal > 0) {
                $sql = 'update poslist set customwave_id="' . addslashes($_POST['wave_id']) . '"' . $updatePathQry . ' where id="' . $resultPos[0]['id'] . '"';
            } else {
                $sql = 'insert into poslist(customwave_id,path,submittime) values(' . $_POST['wave_id'] . ',"' . addslashes($path) . '","' . date('Y-m-d') . '")';
            }
            $statement = $connection->prepare($sql);
            $statement->execute();

            return $this->redirect($this->generateUrl('view_project') . '?id=' . $id . '&msg=success&type=poslist', 301);
        }

        //////for spe brief form///////
        if (isset($_POST['action']) and $_POST['action'] == 'brief') {
            $connection = $em->getConnection();
            //echo '<pre>';print_r($_POST);print_r($_FILES);exit;
            $path = $updatePathQry = '';
            if (isset($_FILES['spePath'])) {
                if (!is_dir($ROOT_PATH . "web/Brief/" . date('ymd'))) {
                    mkdir($ROOT_PATH . "web/Brief/" . date('ymd'), 0777);
                }

                $this->save_image($_FILES['spePath']['name'], "spePath", $ROOT_PATH . "web/Brief/" . date('ymd') . "/");

                $path = "Brief/" . date('ymd') . "/" . str_replace(' ', '_', $_FILES['spePath']['name']);
                $updatePathQry .= ',path="' . $path . '"';
            }

            $main_brief = 0;
            if (isset($_POST['main_brief']))
                $main_brief = 1;

            /////////check if already added//////////
            $sql = 'select id from customfield where customwave_id="' . $_POST['wave_id'] . '" and user_id="' . $this->getUser()->getId() . '" and fieldtype="brief"';
            $statement = $connection->prepare($sql);
            $statement->execute();
            $resultB = $statement->fetchAll();
            $countTotal = count($resultB);

            if ($countTotal > 0) {
                $sql = 'update customfield set main_brief="' . addslashes($main_brief) . '",brief_translation="' . $_POST['brief_translation'] . '"' . $updatePathQry . ' where id="' . $resultB[0]['id'] . '"';
            } else {
                $sql = 'insert into customfield(customwave_id,user_id,path,main_brief,brief_translation,fieldtype,submittime) values(' . $_POST['wave_id'] . ',' . $this->getUser()->getId() . ',"' . addslashes($path) . '","' . addslashes($main_brief) . '","' . addslashes($_POST['brief_translation']) . '","brief","' . date('Y-m-d') . '")';
            }
            $statement = $connection->prepare($sql);
            $statement->execute();

            return $this->redirect($this->generateUrl('view_project') . '?id=' . $id . '&msg=success&type=brief', 301);
        }

        //////for brand material form///////
        if (isset($_POST['action']) and $_POST['action'] == 'dic') {
            $connection = $em->getConnection();
            //echo '<pre>';print_r($_POST);print_r($_FILES);exit;
            $path = $updatePathQry = '';
            if (isset($_FILES['dic_file'])) {
                $wave_name_url = str_replace(' ', '_', $_POST['wave_name']);
                $wave_name_url = str_replace('&', 'n', $wave_name_url);

                if (!is_dir($ROOT_PATH . "web/DIC/" . date('ymd'))) {
                    mkdir($ROOT_PATH . "web/DIC/" . date('ymd'), 0777);
                }
                if (!is_dir($ROOT_PATH . "web/DIC/" . date('ymd') . "/" . $wave_name_url)) {
                    mkdir($ROOT_PATH . "web/DIC/" . date('ymd') . "/" . $wave_name_url, 0777);
                }

                $this->save_image($_FILES['dic_file']['name'], "dic_file", $ROOT_PATH . "web/DIC/" . date('ymd') . "/" . $wave_name_url . "/");

                $path = "DIC/" . date('ymd') . "/" . $wave_name_url . "/" . str_replace(' ', '_', $_FILES['dic_file']['name']);
                $updatePathQry .= ',path="' . $path . '"';
            }

            /////////check if already added//////////
            $sql = 'select id from customfield where customwave_id="' . $_POST['wave_id'] . '" and user_id="' . $this->getUser()->getId() . '" and fieldtype="dic"';
            $statement = $connection->prepare($sql);
            $statement->execute();
            $resultD = $statement->fetchAll();
            $countTotal = count($resultD);

            if ($countTotal > 0) {
                $sql = 'update customfield set question_file1_label="' . addslashes($_POST['dic_label']) . '"' . $updatePathQry . ' where id="' . $resultD[0]['id'] . '"';
            } else {
                $sql = 'insert into customfield(customwave_id,user_id,path,question_file1_label,mm_address,fieldtype,submittime) values(' . $_POST['wave_id'] . ',' . $this->getUser()->getId() . ',"' . addslashes($path) . '","' . addslashes($_POST['dic_label']) . '","' . addslashes($_POST['dic_country']) . '","dic","' . date('Y-m-d') . '")';
            }
            $statement = $connection->prepare($sql);
            $statement->execute();

            return $this->redirect($this->generateUrl('view_project') . '?id=' . $id . '&msg=success&type=dic', 301);
        }

        //////for report form///////
        if (isset($_POST['action']) and $_POST['action'] == 'report') {
            $connection = $em->getConnection();
            //echo '<pre>';print_r($_POST);print_r($_FILES);exit;
            $path = $updatePathQry = '';
            if (isset($_FILES['report_file'])) {
                if (!is_dir($ROOT_PATH . "web/Report/" . date('ymd'))) {
                    mkdir($ROOT_PATH . "web/Report/" . date('ymd'), 0777);
                }

                $this->save_image($_FILES['report_file']['name'], "report_file", $ROOT_PATH . "web/Report/" . date('ymd') . "/");

                $path = "Report/" . date('ymd') . "/" . str_replace(' ', '_', $_FILES['report_file']['name']);
                $updatePathQry .= ',path="' . $path . '"';
            }

            $report_executive = 0;
            if (isset($_POST['report_executive']))
                $report_executive = 1;

            /////////check if already added//////////
            $sql = 'select id from customfield where customwave_id="' . $_POST['wave_id'] . '" and user_id="' . $this->getUser()->getId() . '" and fieldtype="report"';
            $statement = $connection->prepare($sql);
            $statement->execute();
            $resultR = $statement->fetchAll();
            $countTotal = count($resultR);

            if ($countTotal > 0) {
                $sql = 'update customfield set mm_address="' . addslashes($_POST['report_country']) . '",report_type="' . addslashes($_POST['report_type']) . '",report_executive="' . addslashes($report_executive) . '",report_zone="' . addslashes($_POST['report_zone']) . '",' . $updatePathQry . ' where id="' . $resultR[0]['id'] . '"';
            } else {
                $sql = 'insert into customfield(customwave_id,user_id,path,mm_address,report_type,report_executive,report_zone,fieldtype,submittime) values(' . $_POST['wave_id'] . ',' . $this->getUser()->getId() . ',"' . addslashes($path) . '","' . addslashes($_POST['report_country']) . '","' . addslashes($_POST['report_type']) . '","' . addslashes($report_executive) . '","' . addslashes($_POST['report_zone']) . '","report","' . date('Y-m-d') . '")';
            }
            $statement = $connection->prepare($sql);
            $statement->execute();

            return $this->redirect($this->generateUrl('view_project') . '?id=' . $id . '&msg=success&type=report', 301);
        }

        $successMsg = '';
        ////////for displaying success message///////////////
        if (isset($_GET['msg']) and $_GET['msg'] == 'success') {
            if ($_GET['type'] == 'mm')
                $successMsg = 'Meeting minutes form updated successfully.';
            else if ($_GET['type'] == 'brand')
                $successMsg = 'Brand material form updated successfully.';
            else if ($_GET['type'] == 'kick_off')
                $successMsg = 'Kick off meeting form updated successfully';
            else if ($_GET['type'] == 'questionnaire')
                $successMsg = 'Questionnaire form updated successfully.';
            else if ($_GET['type'] == 'poslist')
                $successMsg = 'POS list form updated successfully.';
            else if ($_GET['type'] == 'brief')
                $successMsg = 'SPE brief form updated successfully';
            else if ($_GET['type'] == 'dic')
                $successMsg = 'DIC form updated successfully';
            else if ($_GET['type'] == 'report')
                $successMsg = 'Report form updated successfully.';
        }
        /////////////////////////////////////////////////////

        $projectqb = $em->createQueryBuilder();
        $projectqb->select('p,c')
                ->from('AlbatrossCustomBundle:Customproject', 'p')
                ->leftJoin('p.customclient', 'c')
                ->where('p.id=:pid')
                ->setParameter('pid', $_REQUEST['id']);

        $projectquery = $projectqb->getQuery();
        $projectArr = $projectquery->getArrayResult();

        $waveqb = $em->createQueryBuilder();
        $waveqb->select('c,com,p,pm,ca,q')
                ->from('AlbatrossCustomBundle:Customwave', 'c')
                ->leftJoin('c.comment', 'com')
                ->leftJoin('c.customproject', 'p')
//                ->leftJoin('c.reportKa', 'rk')
                ->leftJoin('c.project_manager', 'pm')
                ->leftJoin('c.campaign', 'ca')
                ->leftJoin('ca.questionnaire', 'q')
                ->where('p.id=:pid')
                ->setParameter('pid', $_REQUEST['id']);

        $wavequery = $waveqb->getQuery();
        $waves = $wavequery->getResult();

        //echo '<pre>';print_r($projectArr);print_r($waves);exit;

        $arrWaveTaskData = $arrWaveData = array();
        foreach ($waves as $wave) {
            $taskqb = $em->createQueryBuilder();
            $taskqb->select('t,p')
                    ->from('AlbatrossAceBundle:Task', 't')
                    ->leftJoin('t.project', 'p')
                    ->where('p.customwave=:wid')
                    ->setParameter('wid', $wave->getId())
                    ->andWhere('t.number > 100 and t.number < 117');

            $taskquery = $taskqb->getQuery();
            $tasks = $taskquery->getArrayResult();
            $tasks['campaign'] = $wave->getCampaign();

            $arrWaveTaskData[$wave->getId()] = $tasks;

            /////////for getting information for meeting minutes form/////////
            $mmqb = $em->createQueryBuilder();
            $mmqb->select('c,u')
                    ->from('AlbatrossCustomBundle:Customfield', 'c')
                    ->leftJoin('c.customwave', 'cw')
                    ->leftJoin('c.user', 'u')
                    ->where('cw.id=:wid')
                    ->setParameter('wid', $wave->getId())
                    ->andWhere('c.fieldtype=:ftype')
                    ->setParameter('ftype', 'mm');

            $mmquery = $mmqb->getQuery();
            $mm = $mmquery->getArrayResult();
            $arrWaveData[$wave->getId()]['mm'] = (isset($mm[0])) ? $mm[0] : '';

            /////////for getting information for brand material form/////////
            $brandqb = $em->createQueryBuilder();
            $brandqb->select('c,u')
                    ->from('AlbatrossCustomBundle:Customfield', 'c')
                    ->leftJoin('c.customwave', 'cw')
                    ->leftJoin('c.user', 'u')
                    ->where('cw.id=:wid')
                    ->setParameter('wid', $wave->getId())
                    ->andWhere('c.fieldtype=:ftype')
                    ->setParameter('ftype', 'material');

            $brandquery = $brandqb->getQuery();
            $brand = $brandquery->getArrayResult();
            $arrWaveData[$wave->getId()]['brand'] = (isset($brand[0])) ? $brand[0] : '';

            /////////for getting information for Questionnaire form/////////
            $quesqb = $em->createQueryBuilder();
            $quesqb->select('c,u')
                    ->from('AlbatrossCustomBundle:Customfield', 'c')
                    ->leftJoin('c.customwave', 'cw')
                    ->leftJoin('c.user', 'u')
                    ->where('cw.id=:wid')
                    ->setParameter('wid', $wave->getId())
                    ->andWhere('c.fieldtype=:ftype')
                    ->setParameter('ftype', 'questionnaire');

            $quesquery = $quesqb->getQuery();
            $questionnaire = $quesquery->getArrayResult();
            $arrWaveData[$wave->getId()]['questionnaire'] = (isset($questionnaire[0])) ? $questionnaire[0] : '';

            /////////for getting information for SpeBrief form/////////
            $briefqb = $em->createQueryBuilder();
            $briefqb->select('c,u')
                    ->from('AlbatrossCustomBundle:Customfield', 'c')
                    ->leftJoin('c.customwave', 'cw')
                    ->leftJoin('c.user', 'u')
                    ->where('cw.id=:wid')
                    ->setParameter('wid', $wave->getId())
                    ->andWhere('c.fieldtype=:ftype')
                    ->setParameter('ftype', 'brief');

            $briefquery = $briefqb->getQuery();
            $brief = $briefquery->getArrayResult();
            $arrWaveData[$wave->getId()]['brief'] = (isset($brief[0])) ? $brief[0] : '';

            /////////for getting information for Report form/////////
            $reportqb = $em->createQueryBuilder();
            $reportqb->select('c,u')
                    ->from('AlbatrossCustomBundle:Customfield', 'c')
                    ->leftJoin('c.customwave', 'cw')
                    ->leftJoin('c.user', 'u')
                    ->where('cw.id=:wid')
                    ->setParameter('wid', $wave->getId())
                    ->andWhere('c.fieldtype=:ftype')
                    ->setParameter('ftype', 'report');

            $reportquery = $reportqb->getQuery();
            $report = $reportquery->getArrayResult();
            $arrWaveData[$wave->getId()]['report'] = (isset($report[0])) ? $report[0] : '';

            /////////for getting information for DIC form/////////
            $dicqb = $em->createQueryBuilder();
            $dicqb->select('c,u')
                    ->from('AlbatrossCustomBundle:Customfield', 'c')
                    ->leftJoin('c.customwave', 'cw')
                    ->leftJoin('c.user', 'u')
                    ->where('cw.id=:wid')
                    ->setParameter('wid', $wave->getId())
                    ->andWhere('c.fieldtype=:ftype')
                    ->setParameter('ftype', 'dic');

            $dicquery = $dicqb->getQuery();
            $dic = $dicquery->getArrayResult();
            $arrWaveData[$wave->getId()]['dic'] = (isset($dic[0])) ? $dic[0] : '';

            /////////for getting information for Kick-off Meeting form/////////
            $meetingqb = $em->createQueryBuilder();
            $meetingqb->select('m')
                    ->from('AlbatrossCustomBundle:KickOffMeetingRecap', 'm')
                    ->leftJoin('m.customwave', 'cw')
                    ->where('cw.id=:wid')
                    ->setParameter('wid', $wave->getId());

            $meetingquery = $meetingqb->getQuery();
            $meeting = $meetingquery->getArrayResult();
            $arrWaveData[$wave->getId()]['meeting'] = (isset($meeting[0])) ? $meeting[0] : '';

            /////////for getting information for POS list form/////////
            $poslistqb = $em->createQueryBuilder();
            $poslistqb->select('p')
                    ->from('AlbatrossCustomBundle:Poslist', 'p')
                    ->leftJoin('p.customwave', 'cw')
                    ->where('cw.id=:wid')
                    ->setParameter('wid', $wave->getId());

            $poslistquery = $poslistqb->getQuery();
            $poslist = $poslistquery->getArrayResult();
            $arrWaveData[$wave->getId()]['poslist'] = (isset($poslist[0])) ? $poslist[0] : '';
        }
        //echo '<pre>';print_r($arrWaveData);exit;
        if (!empty($waves)) {
            $operation = $this->getProjectOperation($waves);
            $operation_2 = $this->getProjectOperation2($waves);
            $operationInformation = $this->combineTwoArr($operation, $operation_2);
        } else {
            $operationInformation = '';
        }

        /////////for getting countries/////////
        $countryqb = $em->createQueryBuilder();
        $countryqb->select('c')->from('AlbatrossAceBundle:Country', 'c');

        $countryquery = $countryqb->getQuery();
        $countryArr = $countryquery->getArrayResult();

        ////////for getting month year list/////////
        $month_year_list = '';
        for ($x = 0; $x < 12; $x++) {
            $monthNum = date('m', mktime(0, 0, 0, date("m") + $x, date("d"), date("Y")));
            $month = date('M', mktime(0, 0, 0, date("m") + $x, date("d"), date("Y")));
            $year = date('Y', mktime(0, 0, 0, date("m") + $x, date("d"), date("Y")));
            $month_year_list .= '<option value="' . $monthNum . '-' . $year . '">' . $month . ' ' . $year . '</option>';
        }

        /////////for getting users/////////
        $userqb = $em->createQueryBuilder();
        $userqb->select('u')->from('AlbatrossUserBundle:User', 'u')->where('u.fullname is not null');

        $userquery = $userqb->getQuery();
        $userArr = $userquery->getArrayResult();

        //echo '<pre>';print_r($operationInformation);exit;
        return $this->render('ProjectBundle:Default:project_view.html.twig', array(
                    'project' => $projectArr[0],
                    'waveArr' => $waves,
                    'taskArr' => $arrWaveTaskData,
                    'operation' => $operationInformation,
                    'formArr' => $arrWaveData,
                    'language' => $language,
                    'qstep' => $step,
                    'material' => $material,
                    'countryArr' => $countryArr,
                    'month_year_list' => $month_year_list,
                    'userArr' => $userArr,
                    'successMsg' => $successMsg
        ));
    }

    function save_image($imageName, $fname, $dir) {
        /* $allowed_file_types = array('.jpg','.jpeg','.gif','.png','.doc','.docx','.xls','.xlsx','.csv','.ppt','.pptx','.pdf');

          $correctType = 0;
          foreach($allowed_file_types as $type)
          {
          $pos = strpos(strtolower($_FILES[$fname]['name']),strtolower($type));

          if($pos!==false)
          {
          $correctType = 1;
          break;
          }
          } */
        $correctType = 1;

        if (isset($_FILES[$fname]["error"]) and $correctType == 1) {
            if ($_FILES[$fname]["error"] == UPLOAD_ERR_OK) {
                $str = $_FILES[$fname]['name'];

                $the_file = $_FILES[$fname]["tmp_name"];
                $imageName = str_replace(' ', '_', $imageName);
                $to_file = "$dir/$imageName";

                move_uploaded_file($the_file, $to_file) or die('unable to upload');
                chmod($to_file, 0777);
            }
        }
    }

    //filter for project manager
    protected function getProjectManager() {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('u')
                ->from('AlbatrossUserBundle:User', 'u')
                ->leftJoin('u.identity', 'i')
                ->where("i.name in ('Project Manager', 'Senior Project Manager', 'BU manager')")
                ->andWhere("u.status = :active")
                ->setParameters(array('active' => 'active'));
        $query = $qb->getQuery();
        $result = $query->getArrayResult();
        return $result;
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
                if ($task['number'] == 500) {
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
                            ['number'] = $t['projectNumber'];
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
                            $final[$waveid][$s->getLocation()->getCountry()->getBu()->getName()]['validation'] ++;
                            $final[$waveid][$s->getLocation()->getCountry()->getBu()->getName()]['fwdone'] ++;
                            $final[$waveid][$s->getLocation()->getCountry()->getBu()->getName()]['assign'] ++;
                            $final[$waveid][$s->getLocation()->getCountry()->getBu()->getName()]['total'] ++;
                        } else if (in_array($s->getSurveyStatusName(), $statusFWdone)) {
                            $final[$waveid][$s->getLocation()->getCountry()->getBu()->getName()]['fwdone'] ++;
                            $final[$waveid][$s->getLocation()->getCountry()->getBu()->getName()]['assign'] ++;
                            $final[$waveid][$s->getLocation()->getCountry()->getBu()->getName()]['total'] ++;
                        } else if (in_array($s->getSurveyStatusName(), $status_Assigned)) {
                            $final[$waveid][$s->getLocation()->getCountry()->getBu()->getName()]['assign'] ++;
                            $final[$waveid][$s->getLocation()->getCountry()->getBu()->getName()]['total'] ++;
                        } else if (in_array($s->getSurveyStatusName(), $status_Total)) {
                            $final[$waveid][$s->getLocation()->getCountry()->getBu()->getName()]['total'] ++;
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

    protected function getBuArr() {
        $em = $this->getDoctrine()->getManager();
        $buEntities = $em->getRepository('AlbatrossAceBundle:Bu')->findAll();
        $buArr = array();
        foreach ($buEntities as $b) {
            $buArr[$b->getNumber()] = $b->getName();
        }

        return $buArr;
    }

}
