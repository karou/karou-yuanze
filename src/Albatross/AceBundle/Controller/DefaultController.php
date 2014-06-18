<?php

namespace Albatross\AceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Albatross\AceBundle\Entity\Project;
use Albatross\AceBundle\Entity\Task;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use \SplFileObject;
use Albatross\AceBundle\Form\FileUploadType;
use Albatross\AceBundle\Entity\Attachments;
use Albatross\AceBundle\Form\AttachmentsType;
use Albatross\AceBundle\Entity\Attachinfo;
use Albatross\AceBundle\Form\AttachinfoType;
use Albatross\AceBundle\Form\IofsearchType;
use Albatross\AceBundle\Form\ForecastsearchType;
use Albatross\AceBundle\Form\ForecastType;
use Albatross\AceBundle\Entity\Forecast;
use Albatross\AceBundle\Entity\ForecastScope;
use Albatross\AceBundle\Entity\Aolsurvey;
use Albatross\AceBundle\Entity\Billing;
use Albatross\AceBundle\Entity\Campaign;
use Albatross\AceBundle\Entity\Location;
use Albatross\AceBundle\Entity\Questionnaire;
use Albatross\AceBundle\Entity\Workflow;
use Symfony\Component\HttpFoundation\ServerBag;

class DefaultController extends Controller {

    private $guid;

    public function indexAction($name) {
        return $this->render('AlbatrossAceBundle:Default:index.html.twig', array('name' => $name));
    }

    protected function login() {
        $url = "http://api.aceproject.com/?fct=login&accountid=albatross&username=API&password=12345@&format=JSON";
        $res = json_decode(file_get_contents($url), true);
        if (!(isset($res['status']) && $res['status'] == "ok"))
            throw $this->createNotFoundException('login() failed.');
        $this->guid = $res['results'][0]['GUID'];
    }

    public function projectAction($current, $filter) {
        $fileForm = $this->createForm(new FileUploadType());
        $em = $this->getDoctrine()->getManager();

        //get post search key word.
        $request = $this->getRequest();
        $post_id = $request->get('srh_id');
        $post_name = $request->get('srh_name');
        $post_percent_a = $request->get('srh_percent_a');
        $post_percent_b = $request->get('srh_percent_b');

        $max_percent = $post_percent_b;
        if (!$post_percent_b)
            $max_percent = 100;

        if ($post_id != '' || $post_name != '' || $post_percent_a != '' || $post_percent_b != '') {
            $qb = $em->createQueryBuilder();
            $qb->add('select', 'p')
                    ->add('from', 'AlbatrossAceBundle:Project p')
                    ->where('p.aceId LIKE :ace_id')
                    ->andWhere('p.name LIKE :ace_name')
                    ->andWhere('p.percent BETWEEN :post_percent_a AND :post_percent_b')
            ;
//            $qb = $em->createQuery('SELECT p from AlbatrossAceBundle:Project p WHERE p.aceId like :ace_id AND p.name like :ace_name AND p.percent BETWEEN :post_percent_a AND :post_percent_b' );

            $qb->setParameters(array(
                'ace_id' => '%' . $post_id . '%',
                'ace_name' => '%' . $post_name . '%',
                'post_percent_a' => $post_percent_a,
                'post_percent_b' => $max_percent,
            ));
            $query = $qb->getQuery();
//            $query = $qb->getResult();
        } else {
            $qb = $em->createQueryBuilder();
            $qb->select('p')
                    ->from('AlbatrossAceBundle:Project', 'p')
                    ->orderBy('p.id');
            $query = $qb->getQuery();
//            $query = $em->getRepository('AlbatrossAceBundle:Project')->findBy(array(), array('id' => 'asc'));
        }

        if ($filter == "noAolPercent") {
            $qb = $em->createQueryBuilder();
            $qb->select('p')
                    ->from('AlbatrossAceBundle:Project', 'p')
                    ->innerJoin('p.tasks', 't')
                    ->where('t.aolPercent IS NULL')
                    ->andWhere("(Regexp(p.description, '^[0-9]+$') = 0) AND (p.description is not null) AND (p.description != '')")
                    ->orderBy('p.id');
            $query = $qb->getQuery();
        } elseif ($filter == "aolPercent") {
            $qb = $em->createQueryBuilder();
            $qb->select('p')
                    ->from('AlbatrossAceBundle:Project', 'p')
                    ->innerJoin('p.tasks', 't')
                    ->where('t.aolPercent IS NOT NULL')
                    ->orderBy('p.id');

            $query = $qb->getQuery();
        }

        $count = count($query->getResult());
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $query, $this->get('request')->query->get('page', 1), 50/* page number */
        );

        return $this->render('AlbatrossAceBundle:Default:project.html.twig', array(
                    'pagination' => $pagination,
                    'current' => $current,
                    'post_id' => $post_id,
                    'post_name' => $post_name,
                    'post_percent_a' => $post_percent_a,
                    'post_percent_b' => $post_percent_b,
                    'count' => $count,
                    'fileForm' => $fileForm->createView()
        ));
    }

    public function fileUploadAction() {
        $content = $this->getRequest()->files->get('file0'); //to get file object
        $filename = $content->getClientOriginalName();
        $dir = date('ymd');
        $file_ext = $content->getClientOriginalExtension();
        if ($file_ext != 'csv') {
            return $this->redirect($this->generateUrl('project'));
        } else {
            $content->move(
                    $this->get('kernel')->getRootDir() . '/../web/aolExport/' . $dir . '/', $filename
            );
            $path = $this->get('kernel')->getRootDir() . '/../web/aolExport/' . $dir . '/' . $filename;
            $return = $this->saveAolsurvey($path, 0);
            return new Response($return);
        }
    }

//==============================================================================
    public function saveAolSurveyByAjaxAction() {
        $path_num = $this->getRequest()->getContent();
        $path_num_arr = json_decode($path_num, true);        
        $return = $this->saveAolsurvey($path_num_arr['path'], $path_num_arr['number']);
        return new Response($return);
    }

    protected function saveAolsurvey($path = null, $curNum = null) {
        $em = $this->getDoctrine()->getManager();
        $csv = new SplFileObject($path);
        $excelArr = array();
        while (!$csv->eof()) {
            $excelArr[] = $csv->fgetcsv();
        }
        $titleLine = '';
        foreach ($excelArr as $key => $line) {
            if ($line[0] == 'SurveyInstanceID') {
                $titleLine = $key;
                break;
            }
        }
        $titleArr = array();
        $neededArr = array('SurveyInstanceID', 'WorkflowStepID', 'WorkflowStatus', 'Survey', 'Client', 'LocStoreID',
            'LocName', 'LocCountryCode', 'RFAsOpen', 'RFAsClosed', 'Campaign', 'isReturnedToShopper',
            'isNoDecline', 'SurveyStatusName', 'BillingRate', 'PrecalcBillingItemsSum', 'PrecalcBillingItemsCount',
            'BillingCurr', 'PayRate', 'PrecalcPayrollItemsSum', 'PrecalcPayrollItemsCount', 'PayrollCurr', 'Date', 'MailboxName');
        foreach ($excelArr[$titleLine] as $k => $t) {
            if (in_array($t, $neededArr)) {
                $titleArr[$t] = $k;
            }
        }
        $index = 0;
        $count = count($excelArr);
        foreach ($excelArr as $key => $line) {
            if ($key > $curNum) {
                $index++;
                if (($line[0] != null) && ($line[0] != '')) {
                    //campaign and questionnaire
                    $campaign = str_replace(' ', '', $line[$titleArr['Campaign']]);
                    $questionnaire = $line[$titleArr['Survey']];
                    if (!$campaign_entity = $em->getRepository('AlbatrossAceBundle:Campaign')->findOneByNameAndQuestionnaire($campaign, $questionnaire)) {
                        $campaign_entity = new Campaign();
                        $campaign_entity->setName($campaign);
                        if (!$questionnaire_entity = $em->getRepository('AlbatrossAceBundle:Questionnaire')->findOneByName($questionnaire)) {
                            $questionnaire_entity = new Questionnaire();
                            $questionnaire_entity->setName($questionnaire);
                            $em->persist($questionnaire_entity);
                        }
                        $campaign_entity->setQuestionnaire($questionnaire_entity);
                        $em->persist($campaign_entity);
                        $em->flush();
                    }

                    if (!$workflow_entity = $em->getRepository('AlbatrossAceBundle:Workflow')->findByIdAndStatus($line[$titleArr['WorkflowStepID']], $line[$titleArr['WorkflowStatus']])) {

                        $workflow_entity = new Workflow();
                        $workflow_entity->setWorkflowStatus($line[$titleArr['WorkflowStatus']]);
                        $workflow_entity->setWorkflowStepID($line[$titleArr['WorkflowStepID']]);
                        $em->persist($workflow_entity);
                        $em->flush();
                    }
                    //location
                    $loc_name = mb_check_encoding($line[$titleArr['LocName']], 'UTF-8') ? $line[$titleArr['LocName']] : utf8_encode($line[$titleArr['LocName']]);
                    $loc_store_id = mb_check_encoding($line[$titleArr['LocStoreID']], 'UTF-8') ? $line[$titleArr['LocStoreID']] : utf8_encode($line[$titleArr['LocStoreID']]);
                    $loc_country_code = mb_check_encoding($line[$titleArr['LocCountryCode']], 'UTF-8') ? $line[$titleArr['LocCountryCode']] : utf8_encode($line[$titleArr['LocCountryCode']]);
                    $loc_country_code_trimed = trim($loc_country_code);
                    if (!$location_entity = $em->getRepository('AlbatrossAceBundle:Location')->findByLocationInfo($loc_name, $loc_store_id, $loc_country_code_trimed)) {
                        $location_entity = new Location();
                        $location_entity->setLocCountryCode($loc_country_code_trimed);
                        $location_entity->setLocName($loc_name);
                        $location_entity->setLocStoreID($loc_store_id);
                        $country_entity = $em->getRepository('AlbatrossAceBundle:Country')->findOneByCode($loc_country_code_trimed);
                        $location_entity->setCountry($country_entity);
                        $em->persist($location_entity);
                    }
                    //aolsurvey
                    if (!$aol_survey_entity = $em->getRepository('AlbatrossAceBundle:Aolsurvey')->findOneBySurveyInstanceID($line[$titleArr['SurveyInstanceID']])) {
                        $aol_survey_entity = new Aolsurvey();
                        $billing_entity = new Billing();
                        $aol_survey_entity->setSurveyInstanceID($line[$titleArr['SurveyInstanceID']]);
                        $aol_survey_entity->setBilling($billing_entity);
                        $aol_survey_entity->setCampaign($campaign_entity);
                        $aol_survey_entity->setLocation($location_entity);
                        $aol_survey_entity->setWorkflow($workflow_entity);
                        $billing_entity->setAolsurvey($aol_survey_entity);
                    } else {
                        $billing_entity = $em->getRepository('AlbatrossAceBundle:Billing')->findOneBySurveyId($aol_survey_entity->getId());
                    }
                    $aol_survey_entity->setRFAsOpen($line[$titleArr['RFAsOpen']]);
                    $aol_survey_entity->setRFAsClosed($line[$titleArr['RFAsClosed']]);
                    $aol_survey_entity->setIsReturnedToShopper($line[$titleArr['isReturnedToShopper']]);
                    $aol_survey_entity->setIsNoDecline($line[$titleArr['isNoDecline']]);
                    $aol_survey_entity->setSurveyStatusName($line[$titleArr['SurveyStatusName']]);
                    $aol_survey_entity->setDate($line[$titleArr['Date']]);
                    $aol_survey_entity->setClient($line[$titleArr['Client']]);
                    $aol_survey_entity->setSubmittime(new \DateTime(date('Y-m-d H:i:s')));
                    $aol_survey_entity->setMailboxName($line[$titleArr['MailboxName']]);
                    //billing
                    $billing_entity->setBillingCurr($line[$titleArr['BillingCurr']]);
                    $billing_entity->setBillingRate($line[$titleArr['BillingRate']]);
                    $billing_entity->setPayRate($line[$titleArr['PayRate']]);
                    $billing_entity->setPayrollCurr($line[$titleArr['PayrollCurr']]);
                    $billing_entity->setPrecalcBillingItemsSum($line[$titleArr['PrecalcBillingItemsSum']]);
                    $billing_entity->setPrecalcBillingItemsCount($line[$titleArr['PrecalcBillingItemsCount']]);
                    $billing_entity->setPrecalcPayrollItemsSum($line[$titleArr['PrecalcPayrollItemsSum']]);
                    $billing_entity->setPrecalcPayrollItemsCount($line[$titleArr['PrecalcPayrollItemsCount']]);
                    $em->persist($aol_survey_entity);
                    $em->persist($billing_entity);
                }
            }
            if ($index >= 500) {
                $em->flush();
                $lineNum = $curNum + $index;
                $percent = ceil(($lineNum/$count)*100);
                $return = '{"percent":"'.$percent.'","number":"'.$lineNum.'","path":"'.$path.'"}';
                return $return;
            }
        }
        $em->flush();
        return 'finish';
    }

    public function projectSyncAction() {
        $this->login();
        $url = "http://api.aceproject.com/?fct=getprojects&guid=" . $this->guid . "&projectid=NULL&masterprojectid=NULL&filterprojectstatusid=NULL&isgroupingprojectstatus=NULL&filterprojecttypeid=NULL&filterprojectpriorityid=NULL&filtercompletedproject=NULL&filterclientid=NULL&includeinactivetemplates=True&filtercreatoruserid=NULL&filterassigneduserid=NULL&filtermarkedonly=False&filterfirstdate=NULL&filterfirstdateoperator=NULL&filterfirstdatevalue=NULL&filterseconddate=NULL&filterseconddateoperator=NULL&filterseconddatevalue=NULL&texttosearch=NULL&sortorder=NULL&useshowhide=False&assignedonly=False&forgantt=False&ispmview=NULL&forcombo=False&pagenumber=NULL&rowsperpage=NULL&deletedprojects=False&format=JSON";
        $res = json_decode(file_get_contents($url), true);
        if (!(isset($res['status']) && $res['status'] == "ok"))
            throw $this->createNotFoundException('getProjects() failed.');
        $results = $res['results'];

        $em = $this->getDoctrine()->getManager();
        $aceIds = array();
        foreach ($results as $val) {
            if (isset($val['PROJECT_ID'])) {
                if (!$project = $em->getRepository('AlbatrossAceBundle:Project')->findOneByAceId($val['PROJECT_ID'])) {
                    $project = new Project();
                    $project->setAceId($val['PROJECT_ID']);
                    $project->setNumber($val['PROJECT_NUMBER']);
                }
                $project->setName($val['PROJECT_NAME']);
                $project->setPercent($val['POURCENTAGE']);
                $project->setDescription($val['PROJECT_DESC']);
                $em->persist($project);
                $aceIds[] = $val['PROJECT_ID'];
            }
        }
        $em->flush();

        $projects = $em->getRepository('AlbatrossAceBundle:Project')->findAll();
        foreach ($projects as $p) {
            if (!in_array($p->getAceId(), $aceIds))
                $em->remove($p);
        }
        $em->flush();

        return $this->redirect($this->generateUrl('project'));
    }

    public function taskSyncAction() {
        $this->login();
//get ajax data
        $content = $this->getRequest()->getContent();
        $data = explode('=', $content);
        $pagenum = intval($data[1]);
//set the number of rows per page.
        $rows = 1000;
        $url = "http://api.aceproject.com/?fct=gettasks&guid=" . $this->guid . "&projectid=NULL&taskid=NULL&assignedprojectsonly=False&includeinactivetemplates=True&filtertaskgroupid=NULL&filtertaskstatusid=NULL&isgroupingtaskstatus=NULL&filtertaskcompleted=NULL&filtertasktypeid=NULL&filtertaskpriorityid=NULL&filtercreatoruserid=NULL&filterassigneduserid=NULL&filterassignedusergroupid=NULL&filterrevieweruserid=NULL&filteronuseridmustmeetall=True&filterprojecttypeid=NULL&filterclientid=NULL&filterfirstdate=NULL&filterfirstdateoperator=NULL&filterfirstdatevalue=NULL&filterseconddate=NULL&filterseconddateoperator=NULL&filterseconddatevalue=NULL&filtertaskassigned=NULL&filtertaskreviewers=NULL&filtermarkedonly=False&filtersoontodoonly=False&filtersoondueonly=False&filteroverdueonly=False&filteroverduerecent=False&filterrecurrencynumber=NULL&filtertasksreadytostartonly=False&texttosearch=NULL&getplaintextvalues=False&sortorder=NULL&useshowhide=False&forlist=False&pagenumber=" . $pagenum . "&rowsperpage=" . $rows . "&format=JSON";
        $res = json_decode(file_get_contents($url), true);
        if (!(isset($res['status']) && $res['status'] == "ok"))
            throw $this->createNotFoundException('getProjects() failed.');
        $results = $res['results'];

        $totalnum = $results[0]['TOTAL_ROW_COUNT'];
        $totalpage = ceil($totalnum / $rows);


        $em = $this->getDoctrine()->getManager();
        foreach ($results as $val) {
            if (isset($val['TASK_ID'])) {
                $isNew = 0;
                if (!$task = $em->getRepository('AlbatrossAceBundle:Task')->findOneByAceId($val['TASK_ID'])) {
                    $task = new Task();
                    $isNew = 1;
                }
                $projects = $em->getRepository('AlbatrossAceBundle:Project')->findOneByAceId($val['PROJECT_ID']);
                if (isset($projects)) {
                    $task->setProject($projects);
                } else {
                    continue;
                }
                //fieldwork task
                if ($val['TASK_NUMBER'] >= 101 && $val['TASK_NUMBER'] <= 116) {
                    $fwsdate = $this->operateAceDate($val['DATE_EXPECTED_START_TASK']);
                    $fwedate = $this->operateAceDate($val['DATE_EXPECTED_END_TASK']);
                    if($isNew == 1){
                        $task->setCreatedDate(new \DateTime(date("Y-m-d H:i:s")));
                    }elseif($task->getFwstartdate() != $fwsdate || 
                            $task->getFwenddate() != $fwedate || 
                            $task->getProjectnumber() != $val['PROJECT_NUMBER'] ||
                            $task->getScope() != strip_tags($val['TASK_DESC_CREATOR'])){
                        $task->setCreatedDate(new \DateTime(date("Y-m-d H:i:s")));
                    }
                    $task->setProjectnumber($val['PROJECT_NUMBER']);
                    $task->setScope(strip_tags($val['TASK_DESC_CREATOR']));
                    $task->setFwstartdate($fwsdate);
                    $task->setFwenddate($fwedate);
                }
                if ($val['TASK_NUMBER'] == 600) {
                    $duedate = $this->operateAceDate($val['DATE_EXPECTED_END_TASK']);
                    if($isNew == 1){
                        $task->setCreatedDate(new \DateTime(date("Y-m-d H:i:s")));
                    }elseif($task->getReportduedate() != $duedate){
                        $task->setCreatedDate(new \DateTime(date("Y-m-d H:i:s")));
                    }
                    $task->setReportduedate($duedate);
                    $task->setPm($val['ASSIGNED']);
                    $em = $this->saveDelieveryDateToWaveFromTask($task, $em);
                }
                $task->setResume($val['TASK_RESUME']);
                $task->setNumber($val['TASK_NUMBER']);
                $task->setStatus($val['STATUS_TYPE_NAME']);
                $task->setPercentageDone($val['POURCENTAGE_DONE']);
                $task->setActualPercentageDone($val['ACTUAL_PERCENT_DONE']);
                $task->setAceId($val['TASK_ID']);
                $task->setStatusId($val['STATUS_TYPE_ID']);
                $task->setUpdatedaol(0);
                $task->setUpdated(1);
                $em->persist($task);
            }
        }
        $em->flush();

        $prg_pct = ceil(748 * ($pagenum / $totalpage));
        $pagenum++;
        if ($pagenum <= $totalpage) {
            $pn_pct = '{"pagenum":"' . $pagenum . '","percent":"' . $prg_pct . '"}';
            return (new Response($pn_pct));
        } else {
            if($this->clearTask()){
                $pn_pct = '{"pagenum":"stop","percent":"' . $prg_pct . '"}';
                return (new Response($pn_pct));
            }
        }
    }
    protected function saveDelieveryDateToWaveFromTask($task, $em) {
        $project = $task->getProject();
        $waveEntity = $project->getCustomwave();
        if(is_object($waveEntity)) {
            if( ($waveEntity->getWaveStep() == 'ACE' || $waveEntity->getWaveStep() == '') 
                    && (strtotime($waveEntity->getDeliveryDate()) < strtotime($task->getReportduedate()) || $waveEntity->getDeliveryDate() == '-' || !$waveEntity->getDeliveryDate())){
                $waveEntity->setDeliveryDate($task->getReportduedate());
                $waveEntity->setWaveStep('ACE');
                $em->persist($waveEntity);
            }
        }
        return $em;
    }
    protected function clearTask() {
        $em = $this->getDoctrine()->getManager();
//        $sql_delete_task = "DELETE MyProject\Model\User u WHERE u.id = 4";
        $qb = $em->createQueryBuilder();
        $qb->select('t')
                ->from('AlbatrossAceBundle:Task', 't')
                ->where('t.updated = 0');
        $query = $qb->getQuery();
        $result = $query->getResult();
        foreach($result as $re){
            $em->remove($re);
        }
        $em->flush();
//        $em->getConnection()->exec($sql_delete_task);
        $sql_update_task = "UPDATE task SET task.updated = 0 WHERE 1";
        $em->getConnection()->exec($sql_update_task);
        return true;
    }

    public function operateAceDate($date) { //date is like /Date('10000000000')/
        $date = str_replace('Date(', '', $date);
        $date = str_replace(')', '', $date);
        $date = str_replace('/', '', $date);
        $date = $date / 1000;
        $date = date('Y-m-d', $date);
        return $date;
    }

    public function getTasksAction($projectId) {
        $secu = $this->container->get('security.context');
        $tasks = $this->getTaskByProject($projectId);
        $result = '<li><table style="width: 100%"><thead><tr><th>Task</th><th>Status</th><th>Percent -> New Percent</th></tr></thead>';
        foreach ($tasks as $v) {
            $percentDone = $v['percentageDone'] * 10;

            if ($v['updatedaol'] == 1) {
                $checked = 'checked="checked"';
            } else {
                $checked = '';
            }
            if ($v['aolPercent'] == null) {
                $aolPercent = 'No changes';
            } else {
                $aolPercent = $v['aolPercent'] . '%';
            }
            if ($secu->isGranted('ROLE_ADMIN')) {
                $result .= '<tr><td><form><input type="checkbox" class="checkbox" onclick="updateStatus(this)" name="' . $v['id'] . '" ' . $checked . '></form></td><td>'
                        . $v['resume'] . '</td><td>' . $v['status'] . '</td><td>' . $percentDone . '% -> ' . $aolPercent . '</td></tr>';
            } else {
                $result .= '<tr><td>'
                        . $v['resume'] . '</td><td>' . $v['status'] . '</td><td>' . $percentDone . '% -> ' . $aolPercent . '</td></tr>';
            }
        };
        $result .= '</table></li>';
        return (new Response($result));
    }

//get tasks from selected project.
    public function getTaskByProject($projectId) {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->add('select', 't')
                ->add('from', 'AlbatrossAceBundle:Task t')
                ->leftJoin('t.project', 'p')
                ->where('p.id = :project_id')
                ->andWhere('t.number BETWEEN 101 and 399');
        $qb->setParameters(array(
            'project_id' => $projectId
        ));
        $tasks = $qb->getQuery()->getArrayResult();
        return $tasks;
    }

    public function backupTasksAction() {
        $em = $this->getDoctrine()->getManager();
        $test = $em->getConnection();
        $result = $test->fetchAll("show tables like 'task_backup' ");
        if (!empty($result)) {
//clear the backup table
            $sql_delete = "DELETE FROM task_backup";
            $em->getConnection()->exec($sql_delete);
//copy the task data from task table to task_backup table
            $sql_insert = "INSERT INTO task_backup SELECT * FROM task";
        } else {
//if there is no table named task_backup 
//create task_backup table and copy data from task
            $sql_insert = "CREATE TABLE task_backup SELECT * FROM task";
        }
        if ($em->getConnection()->exec($sql_insert)) {
            return $this->redirect($this->generateUrl('project'));
        } else {
            return $this->redirect($this->generateUrl('project'));
        }
    }

//restore tasks from last backup
    public function restoreTasksAction() {
        $em = $this->getDoctrine()->getManager();
        $test = $em->getConnection();
        $result = $test->fetchAll("show tables like 'task_backup' ");
        if (!empty($result)) {
            $sql_delete_task = "DELETE FROM task";
            $em->getConnection()->exec($sql_delete_task);
//copy the task data from task_backup table to task table
            $sql_insert = "INSERT INTO task SELECT * FROM task_backup";
            $em->getConnection()->exec($sql_insert);
            return $this->redirect($this->generateUrl('project'));
        } else {
            return $this->redirect($this->generateUrl('project'));
        }
    }

    protected function initArrayStatus() {
        $bu = array('UK', 'US', 'SW', 'NE', 'RU', 'ME', 'CN', 'TW', 'JP', 'KR', 'HK', 'SG', 'IN', 'AU', 'TH', 'LA');
        $array = array();

        foreach ($bu as $b) {
            $array[$b]['Declined'] = 0;
            $array[$b]['Open Opportunities - No Applications'] = 0;
            $array[$b]['Open Opportunities - With Applications'] = 0;
            $array[$b]['Assigned - Completed not yet submitted'] = 0;
            $array[$b]['Assigned - In "Working" status'] = 0;
            $array[$b]['Assigned - Returned Completely'] = 0;
            $array[$b]['Assigned (Accepted where Acceptance is Required)'] = 0;
            $array[$b]['Validation - After Return'] = 0;
            $array[$b]['Validation - In Progress'] = 0;
            $array[$b]['Validation - Pending'] = 0;
            $array[$b]['On Hold - General'] = 0;
            $array[$b]['Completed - Pending Export'] = 0;
            $array[$b]['Completed - RFA(s) closed'] = 0;
            $array[$b]['Completed - RFA(s) open'] = 0;
            $array[$b]['Completed Exported'] = 0;
            $array[$b]['Hide from Reports; Hide from Client Survey Explorer'] = 0;
            $array[$b]['Hide from Reports; OK for Client Survey Explorer'] = 0;
            $array[$b]['Completed - Export Failed'] = 0;
            $array[$b]['max'] = 0;
        }
        return $array;
    }

    protected function saveAolPercent($array, $proj) {
        $em = $this->getDoctrine()->getManager();
        /* get tasks */
        $tasks = $em->getRepository('AlbatrossAceBundle:Task')->findByProject($proj);

        foreach ($tasks as $task) {
            $bu = null;
            switch ($task->getNumber()) {
                case 101:
                    $bu = 'UK';
                    if ($array[$bu]['max'] > 0) {
                        $task->setAolPercent(round(($array[$bu]['Validation - After Return'] + $array[$bu]['Validation - In Progress'] + $array[$bu]['Validation - Pending'] + $array[$bu]['On Hold - General'] + $array[$bu]['Completed - Pending Export'] + $array[$bu]['Completed - RFA(s) closed'] + $array[$bu]['Completed Exported'] + $array[$bu]['Hide from Reports; Hide from Client Survey Explorer'] + $array[$bu]['Hide from Reports; OK for Client Survey Explorer'] + $array[$bu]['Completed - Export Failed']) / $array[$bu]['max'] * 100));
                        $task->setUpdatedaol(1);
                    }
                    break;
                case 102:
                    $bu = 'US';
                    if ($array[$bu]['max'] > 0) {
                        $task->setAolPercent(round(($array[$bu]['Validation - After Return'] + $array[$bu]['Validation - In Progress'] + $array[$bu]['Validation - Pending'] + $array[$bu]['On Hold - General'] + $array[$bu]['Completed - Pending Export'] + $array[$bu]['Completed - RFA(s) closed'] + $array[$bu]['Completed Exported'] + $array[$bu]['Hide from Reports; Hide from Client Survey Explorer'] + $array[$bu]['Hide from Reports; OK for Client Survey Explorer'] + $array[$bu]['Completed - Export Failed']) / $array[$bu]['max'] * 100));
                        $task->setUpdatedaol(1);
                    }
                    break;
                case 103:
                    $bu = 'SW';
                    if ($array[$bu]['max'] > 0) {
                        $task->setAolPercent(round(($array[$bu]['Validation - After Return'] + $array[$bu]['Validation - In Progress'] + $array[$bu]['Validation - Pending'] + $array[$bu]['On Hold - General'] + $array[$bu]['Completed - Pending Export'] + $array[$bu]['Completed - RFA(s) closed'] + $array[$bu]['Completed Exported'] + $array[$bu]['Hide from Reports; Hide from Client Survey Explorer'] + $array[$bu]['Hide from Reports; OK for Client Survey Explorer'] + $array[$bu]['Completed - Export Failed']) / $array[$bu]['max'] * 100));
                        $task->setUpdatedaol(1);
                    }
                    break;
                case 104:
                    $bu = 'NE';
                    if ($array[$bu]['max'] > 0) {
                        $task->setAolPercent(round(($array[$bu]['Validation - After Return'] + $array[$bu]['Validation - In Progress'] + $array[$bu]['Validation - Pending'] + $array[$bu]['On Hold - General'] + $array[$bu]['Completed - Pending Export'] + $array[$bu]['Completed - RFA(s) closed'] + $array[$bu]['Completed Exported'] + $array[$bu]['Hide from Reports; Hide from Client Survey Explorer'] + $array[$bu]['Hide from Reports; OK for Client Survey Explorer'] + $array[$bu]['Completed - Export Failed']) / $array[$bu]['max'] * 100));
                        $task->setUpdatedaol(1);
                    }
                    break;
                case 105:
                    $bu = 'RU';
                    if ($array[$bu]['max'] > 0) {
                        $task->setAolPercent(round(($array[$bu]['Validation - After Return'] + $array[$bu]['Validation - In Progress'] + $array[$bu]['Validation - Pending'] + $array[$bu]['On Hold - General'] + $array[$bu]['Completed - Pending Export'] + $array[$bu]['Completed - RFA(s) closed'] + $array[$bu]['Completed Exported'] + $array[$bu]['Hide from Reports; Hide from Client Survey Explorer'] + $array[$bu]['Hide from Reports; OK for Client Survey Explorer'] + $array[$bu]['Completed - Export Failed']) / $array[$bu]['max'] * 100));
                        $task->setUpdatedaol(1);
                    }
                    break;
                case 106:
                    $bu = 'ME';
                    if ($array[$bu]['max'] > 0) {
                        $task->setAolPercent(round(($array[$bu]['Validation - After Return'] + $array[$bu]['Validation - In Progress'] + $array[$bu]['Validation - Pending'] + $array[$bu]['On Hold - General'] + $array[$bu]['Completed - Pending Export'] + $array[$bu]['Completed - RFA(s) closed'] + $array[$bu]['Completed Exported'] + $array[$bu]['Hide from Reports; Hide from Client Survey Explorer'] + $array[$bu]['Hide from Reports; OK for Client Survey Explorer'] + $array[$bu]['Completed - Export Failed']) / $array[$bu]['max'] * 100));
                        $task->setUpdatedaol(1);
                    }
                    break;
                case 107:
                    $bu = 'CN';
                    if ($array[$bu]['max'] > 0) {
                        $task->setAolPercent(round(($array[$bu]['Validation - After Return'] + $array[$bu]['Validation - In Progress'] + $array[$bu]['Validation - Pending'] + $array[$bu]['On Hold - General'] + $array[$bu]['Completed - Pending Export'] + $array[$bu]['Completed - RFA(s) closed'] + $array[$bu]['Completed Exported'] + $array[$bu]['Hide from Reports; Hide from Client Survey Explorer'] + $array[$bu]['Hide from Reports; OK for Client Survey Explorer'] + $array[$bu]['Completed - Export Failed']) / $array[$bu]['max'] * 100));
                        $task->setUpdatedaol(1);
                    }
                    break;
                case 108:
                    $bu = 'TW';
                    if ($array[$bu]['max'] > 0) {
                        $task->setAolPercent(round(($array[$bu]['Validation - After Return'] + $array[$bu]['Validation - In Progress'] + $array[$bu]['Validation - Pending'] + $array[$bu]['On Hold - General'] + $array[$bu]['Completed - Pending Export'] + $array[$bu]['Completed - RFA(s) closed'] + $array[$bu]['Completed Exported'] + $array[$bu]['Hide from Reports; Hide from Client Survey Explorer'] + $array[$bu]['Hide from Reports; OK for Client Survey Explorer'] + $array[$bu]['Completed - Export Failed']) / $array[$bu]['max'] * 100));
                        $task->setUpdatedaol(1);
                    }
                    break;
                case 109:
                    $bu = 'JP';
                    if ($array[$bu]['max'] > 0) {
                        $task->setAolPercent(round(($array[$bu]['Validation - After Return'] + $array[$bu]['Validation - In Progress'] + $array[$bu]['Validation - Pending'] + $array[$bu]['On Hold - General'] + $array[$bu]['Completed - Pending Export'] + $array[$bu]['Completed - RFA(s) closed'] + $array[$bu]['Completed Exported'] + $array[$bu]['Hide from Reports; Hide from Client Survey Explorer'] + $array[$bu]['Hide from Reports; OK for Client Survey Explorer'] + $array[$bu]['Completed - Export Failed']) / $array[$bu]['max'] * 100));
                        $task->setUpdatedaol(1);
                    }
                    break;
                case 110:
                    $bu = 'KR';
                    if ($array[$bu]['max'] > 0) {
                        $task->setAolPercent(round(($array[$bu]['Validation - After Return'] + $array[$bu]['Validation - In Progress'] + $array[$bu]['Validation - Pending'] + $array[$bu]['On Hold - General'] + $array[$bu]['Completed - Pending Export'] + $array[$bu]['Completed - RFA(s) closed'] + $array[$bu]['Completed Exported'] + $array[$bu]['Hide from Reports; Hide from Client Survey Explorer'] + $array[$bu]['Hide from Reports; OK for Client Survey Explorer'] + $array[$bu]['Completed - Export Failed']) / $array[$bu]['max'] * 100));
                        $task->setUpdatedaol(1);
                    }
                    break;
                case 111:
                    $bu = 'HK';
                    if ($array[$bu]['max'] > 0) {
                        $task->setAolPercent(round(($array[$bu]['Validation - After Return'] + $array[$bu]['Validation - In Progress'] + $array[$bu]['Validation - Pending'] + $array[$bu]['On Hold - General'] + $array[$bu]['Completed - Pending Export'] + $array[$bu]['Completed - RFA(s) closed'] + $array[$bu]['Completed Exported'] + $array[$bu]['Hide from Reports; Hide from Client Survey Explorer'] + $array[$bu]['Hide from Reports; OK for Client Survey Explorer'] + $array[$bu]['Completed - Export Failed']) / $array[$bu]['max'] * 100));
                        $task->setUpdatedaol(1);
                    }
                    break;
                case 112:
                    $bu = 'SG';
                    if ($array[$bu]['max'] > 0) {
                        $task->setAolPercent(round(($array[$bu]['Validation - After Return'] + $array[$bu]['Validation - In Progress'] + $array[$bu]['Validation - Pending'] + $array[$bu]['On Hold - General'] + $array[$bu]['Completed - Pending Export'] + $array[$bu]['Completed - RFA(s) closed'] + $array[$bu]['Completed Exported'] + $array[$bu]['Hide from Reports; Hide from Client Survey Explorer'] + $array[$bu]['Hide from Reports; OK for Client Survey Explorer'] + $array[$bu]['Completed - Export Failed']) / $array[$bu]['max'] * 100));
                        $task->setUpdatedaol(1);
                    }
                    break;
                case 113:
                    $bu = 'IN';
                    if ($array[$bu]['max'] > 0) {
                        $task->setAolPercent(round(($array[$bu]['Validation - After Return'] + $array[$bu]['Validation - In Progress'] + $array[$bu]['Validation - Pending'] + $array[$bu]['On Hold - General'] + $array[$bu]['Completed - Pending Export'] + $array[$bu]['Completed - RFA(s) closed'] + $array[$bu]['Completed Exported'] + $array[$bu]['Hide from Reports; Hide from Client Survey Explorer'] + $array[$bu]['Hide from Reports; OK for Client Survey Explorer'] + $array[$bu]['Completed - Export Failed']) / $array[$bu]['max'] * 100));
                        $task->setUpdatedaol(1);
                    }
                    break;
                case 114:
                    $bu = 'AU';
                    if ($array[$bu]['max'] > 0) {
                        $task->setAolPercent(round(($array[$bu]['Validation - After Return'] + $array[$bu]['Validation - In Progress'] + $array[$bu]['Validation - Pending'] + $array[$bu]['On Hold - General'] + $array[$bu]['Completed - Pending Export'] + $array[$bu]['Completed - RFA(s) closed'] + $array[$bu]['Completed Exported'] + $array[$bu]['Hide from Reports; Hide from Client Survey Explorer'] + $array[$bu]['Hide from Reports; OK for Client Survey Explorer'] + $array[$bu]['Completed - Export Failed']) / $array[$bu]['max'] * 100));
                        $task->setUpdatedaol(1);
                    }
                    break;
                case 115:
                    $bu = 'TH';
                    if ($array[$bu]['max'] > 0) {
                        $task->setAolPercent(round(($array[$bu]['Validation - After Return'] + $array[$bu]['Validation - In Progress'] + $array[$bu]['Validation - Pending'] + $array[$bu]['On Hold - General'] + $array[$bu]['Completed - Pending Export'] + $array[$bu]['Completed - RFA(s) closed'] + $array[$bu]['Completed Exported'] + $array[$bu]['Hide from Reports; Hide from Client Survey Explorer'] + $array[$bu]['Hide from Reports; OK for Client Survey Explorer'] + $array[$bu]['Completed - Export Failed']) / $array[$bu]['max'] * 100));
                        $task->setUpdatedaol(1);
                    }
                    break;
                case 116:
                    $bu = 'LA';
                    if ($array[$bu]['max'] > 0) {
                        $task->setAolPercent(round(($array[$bu]['Validation - After Return'] + $array[$bu]['Validation - In Progress'] + $array[$bu]['Validation - Pending'] + $array[$bu]['On Hold - General'] + $array[$bu]['Completed - Pending Export'] + $array[$bu]['Completed - RFA(s) closed'] + $array[$bu]['Completed Exported'] + $array[$bu]['Hide from Reports; Hide from Client Survey Explorer'] + $array[$bu]['Hide from Reports; OK for Client Survey Explorer'] + $array[$bu]['Completed - Export Failed']) / $array[$bu]['max'] * 100));
                        $task->setUpdatedaol(1);
                    }
                    break;
                case 201:
                    $bu = 'UK';
                    if ($array[$bu]['max'] > 0) {
                        $task->setAolPercent(round(($array[$bu]['On Hold - General'] + $array[$bu]['Completed - Pending Export'] + $array[$bu]['Completed - RFA(s) closed'] + $array[$bu]['Completed Exported'] + $array[$bu]['Hide from Reports; Hide from Client Survey Explorer'] + $array[$bu]['Hide from Reports; OK for Client Survey Explorer'] + $array[$bu]['Completed - Export Failed']) / $array[$bu]['max'] * 100));
                        $task->setUpdatedaol(1);
                    }
                    break;
                case 202:
                    $bu = 'US';
                    if ($array[$bu]['max'] > 0) {
                        $task->setAolPercent(round(($array[$bu]['On Hold - General'] + $array[$bu]['Completed - Pending Export'] + $array[$bu]['Completed - RFA(s) closed'] + $array[$bu]['Completed Exported'] + $array[$bu]['Hide from Reports; Hide from Client Survey Explorer'] + $array[$bu]['Hide from Reports; OK for Client Survey Explorer'] + $array[$bu]['Completed - Export Failed']) / $array[$bu]['max'] * 100));
                        $task->setUpdatedaol(1);
                    }
                    break;
                case 203:
                    $bu = 'SW';
                    if ($array[$bu]['max'] > 0) {
                        $task->setAolPercent(round(($array[$bu]['On Hold - General'] + $array[$bu]['Completed - Pending Export'] + $array[$bu]['Completed - RFA(s) closed'] + $array[$bu]['Completed Exported'] + $array[$bu]['Hide from Reports; Hide from Client Survey Explorer'] + $array[$bu]['Hide from Reports; OK for Client Survey Explorer'] + $array[$bu]['Completed - Export Failed']) / $array[$bu]['max'] * 100));
                        $task->setUpdatedaol(1);
                    }
                    break;
                case 204:
                    $bu = 'NE';
                    if ($array[$bu]['max'] > 0) {
                        $task->setAolPercent(round(($array[$bu]['On Hold - General'] + $array[$bu]['Completed - Pending Export'] + $array[$bu]['Completed - RFA(s) closed'] + $array[$bu]['Completed Exported'] + $array[$bu]['Hide from Reports; Hide from Client Survey Explorer'] + $array[$bu]['Hide from Reports; OK for Client Survey Explorer'] + $array[$bu]['Completed - Export Failed']) / $array[$bu]['max'] * 100));
                        $task->setUpdatedaol(1);
                    }
                    break;
                case 205:
                    $bu = 'RU';
                    if ($array[$bu]['max'] > 0) {
                        $task->setAolPercent(round(($array[$bu]['On Hold - General'] + $array[$bu]['Completed - Pending Export'] + $array[$bu]['Completed - RFA(s) closed'] + $array[$bu]['Completed Exported'] + $array[$bu]['Hide from Reports; Hide from Client Survey Explorer'] + $array[$bu]['Hide from Reports; OK for Client Survey Explorer'] + $array[$bu]['Completed - Export Failed']) / $array[$bu]['max'] * 100));
                        $task->setUpdatedaol(1);
                    }
                    break;
                case 206:
                    $bu = 'ME';
                    if ($array[$bu]['max'] > 0) {
                        $task->setAolPercent(round(($array[$bu]['On Hold - General'] + $array[$bu]['Completed - Pending Export'] + $array[$bu]['Completed - RFA(s) closed'] + $array[$bu]['Completed Exported'] + $array[$bu]['Hide from Reports; Hide from Client Survey Explorer'] + $array[$bu]['Hide from Reports; OK for Client Survey Explorer'] + $array[$bu]['Completed - Export Failed']) / $array[$bu]['max'] * 100));
                        $task->setUpdatedaol(1);
                    }
                    break;
                case 207:
                    $bu = 'CN';
                    if ($array[$bu]['max'] > 0) {
                        $task->setAolPercent(round(($array[$bu]['On Hold - General'] + $array[$bu]['Completed - Pending Export'] + $array[$bu]['Completed - RFA(s) closed'] + $array[$bu]['Completed Exported'] + $array[$bu]['Hide from Reports; Hide from Client Survey Explorer'] + $array[$bu]['Hide from Reports; OK for Client Survey Explorer'] + $array[$bu]['Completed - Export Failed']) / $array[$bu]['max'] * 100));
                        $task->setUpdatedaol(1);
                    }
                    break;
                case 208:
                    $bu = 'TW';
                    if ($array[$bu]['max'] > 0) {
                        $task->setAolPercent(round(($array[$bu]['On Hold - General'] + $array[$bu]['Completed - Pending Export'] + $array[$bu]['Completed - RFA(s) closed'] + $array[$bu]['Completed Exported'] + $array[$bu]['Hide from Reports; Hide from Client Survey Explorer'] + $array[$bu]['Hide from Reports; OK for Client Survey Explorer'] + $array[$bu]['Completed - Export Failed']) / $array[$bu]['max'] * 100));
                        $task->setUpdatedaol(1);
                    }
                    break;
                case 209:
                    $bu = 'JP';
                    if ($array[$bu]['max'] > 0) {
                        $task->setAolPercent(round(($array[$bu]['On Hold - General'] + $array[$bu]['Completed - Pending Export'] + $array[$bu]['Completed - RFA(s) closed'] + $array[$bu]['Completed Exported'] + $array[$bu]['Hide from Reports; Hide from Client Survey Explorer'] + $array[$bu]['Hide from Reports; OK for Client Survey Explorer'] + $array[$bu]['Completed - Export Failed']) / $array[$bu]['max'] * 100));
                        $task->setUpdatedaol(1);
                    }
                    break;
                case 210:
                    $bu = 'KR';
                    if ($array[$bu]['max'] > 0) {
                        $task->setAolPercent(round(($array[$bu]['On Hold - General'] + $array[$bu]['Completed - Pending Export'] + $array[$bu]['Completed - RFA(s) closed'] + $array[$bu]['Completed Exported'] + $array[$bu]['Hide from Reports; Hide from Client Survey Explorer'] + $array[$bu]['Hide from Reports; OK for Client Survey Explorer'] + $array[$bu]['Completed - Export Failed']) / $array[$bu]['max'] * 100));
                        $task->setUpdatedaol(1);
                    }
                    break;
                case 211:
                    $bu = 'HK';
                    if ($array[$bu]['max'] > 0) {
                        $task->setAolPercent(round(($array[$bu]['On Hold - General'] + $array[$bu]['Completed - Pending Export'] + $array[$bu]['Completed - RFA(s) closed'] + $array[$bu]['Completed Exported'] + $array[$bu]['Hide from Reports; Hide from Client Survey Explorer'] + $array[$bu]['Hide from Reports; OK for Client Survey Explorer'] + $array[$bu]['Completed - Export Failed']) / $array[$bu]['max'] * 100));
                        $task->setUpdatedaol(1);
                    }
                    break;
                case 212:
                    $bu = 'SG';
                    if ($array[$bu]['max'] > 0) {
                        $task->setAolPercent(round(($array[$bu]['On Hold - General'] + $array[$bu]['Completed - Pending Export'] + $array[$bu]['Completed - RFA(s) closed'] + $array[$bu]['Completed Exported'] + $array[$bu]['Hide from Reports; Hide from Client Survey Explorer'] + $array[$bu]['Hide from Reports; OK for Client Survey Explorer'] + $array[$bu]['Completed - Export Failed']) / $array[$bu]['max'] * 100));
                        $task->setUpdatedaol(1);
                    }
                    break;
                case 213:
                    $bu = 'IN';
                    if ($array[$bu]['max'] > 0) {
                        $task->setAolPercent(round(($array[$bu]['On Hold - General'] + $array[$bu]['Completed - Pending Export'] + $array[$bu]['Completed - RFA(s) closed'] + $array[$bu]['Completed Exported'] + $array[$bu]['Hide from Reports; Hide from Client Survey Explorer'] + $array[$bu]['Hide from Reports; OK for Client Survey Explorer'] + $array[$bu]['Completed - Export Failed']) / $array[$bu]['max'] * 100));
                        $task->setUpdatedaol(1);
                    }
                    break;
                case 214:
                    $bu = 'AU';
                    if ($array[$bu]['max'] > 0) {
                        $task->setAolPercent(round(($array[$bu]['On Hold - General'] + $array[$bu]['Completed - Pending Export'] + $array[$bu]['Completed - RFA(s) closed'] + $array[$bu]['Completed Exported'] + $array[$bu]['Hide from Reports; Hide from Client Survey Explorer'] + $array[$bu]['Hide from Reports; OK for Client Survey Explorer'] + $array[$bu]['Completed - Export Failed']) / $array[$bu]['max'] * 100));
                        $task->setUpdatedaol(1);
                    }
                    break;
                case 215:
                    $bu = 'TH';
                    if ($array[$bu]['max'] > 0) {
                        $task->setAolPercent(round(($array[$bu]['On Hold - General'] + $array[$bu]['Completed - Pending Export'] + $array[$bu]['Completed - RFA(s) closed'] + $array[$bu]['Completed Exported'] + $array[$bu]['Hide from Reports; Hide from Client Survey Explorer'] + $array[$bu]['Hide from Reports; OK for Client Survey Explorer'] + $array[$bu]['Completed - Export Failed']) / $array[$bu]['max'] * 100));
                        $task->setUpdatedaol(1);
                    }
                    break;
                case 216:
                    $bu = 'LA';
                    if ($array[$bu]['max'] > 0) {
                        $task->setAolPercent(round(($array[$bu]['On Hold - General'] + $array[$bu]['Completed - Pending Export'] + $array[$bu]['Completed - RFA(s) closed'] + $array[$bu]['Completed Exported'] + $array[$bu]['Hide from Reports; Hide from Client Survey Explorer'] + $array[$bu]['Hide from Reports; OK for Client Survey Explorer'] + $array[$bu]['Completed - Export Failed']) / $array[$bu]['max'] * 100));
                        $task->setUpdatedaol(1);
                    }
                    break;
                case 301:
                    $bu = 'UK';
                    if ($array[$bu]['max'] > 0) {
                        $task->setAolPercent(round(($array[$bu]['Completed - Pending Export'] + $array[$bu]['Completed - RFA(s) closed'] + $array[$bu]['Completed Exported'] + $array[$bu]['Hide from Reports; Hide from Client Survey Explorer'] + $array[$bu]['Hide from Reports; OK for Client Survey Explorer'] + $array[$bu]['Completed - Export Failed']) / $array[$bu]['max'] * 100));
                        $task->setUpdatedaol(1);
                    }
                    break;
                case 302:
                    $bu = 'US';
                    if ($array[$bu]['max'] > 0) {
                        $task->setAolPercent(round(($array[$bu]['Completed - Pending Export'] + $array[$bu]['Completed - RFA(s) closed'] + $array[$bu]['Completed Exported'] + $array[$bu]['Hide from Reports; Hide from Client Survey Explorer'] + $array[$bu]['Hide from Reports; OK for Client Survey Explorer'] + $array[$bu]['Completed - Export Failed']) / $array[$bu]['max'] * 100));
                        $task->setUpdatedaol(1);
                    }
                    break;
                case 303:
                    $bu = 'SW';
                    if ($array[$bu]['max'] > 0) {
                        $task->setAolPercent(round(($array[$bu]['Completed - Pending Export'] + $array[$bu]['Completed - RFA(s) closed'] + $array[$bu]['Completed Exported'] + $array[$bu]['Hide from Reports; Hide from Client Survey Explorer'] + $array[$bu]['Hide from Reports; OK for Client Survey Explorer'] + $array[$bu]['Completed - Export Failed']) / $array[$bu]['max'] * 100));
                        $task->setUpdatedaol(1);
                    }
                    break;
                case 304:
                    $bu = 'NE';
                    if ($array[$bu]['max'] > 0) {
                        $task->setAolPercent(round(($array[$bu]['Completed - Pending Export'] + $array[$bu]['Completed - RFA(s) closed'] + $array[$bu]['Completed Exported'] + $array[$bu]['Hide from Reports; Hide from Client Survey Explorer'] + $array[$bu]['Hide from Reports; OK for Client Survey Explorer'] + $array[$bu]['Completed - Export Failed']) / $array[$bu]['max'] * 100));
                        $task->setUpdatedaol(1);
                    }
                    break;
                case 305:
                    $bu = 'RU';
                    if ($array[$bu]['max'] > 0) {
                        $task->setAolPercent(round(($array[$bu]['Completed - Pending Export'] + $array[$bu]['Completed - RFA(s) closed'] + $array[$bu]['Completed Exported'] + $array[$bu]['Hide from Reports; Hide from Client Survey Explorer'] + $array[$bu]['Hide from Reports; OK for Client Survey Explorer'] + $array[$bu]['Completed - Export Failed']) / $array[$bu]['max'] * 100));
                        $task->setUpdatedaol(1);
                    }
                    break;
                case 306:
                    $bu = 'ME';
                    if ($array[$bu]['max'] > 0) {
                        $task->setAolPercent(round(($array[$bu]['Completed - Pending Export'] + $array[$bu]['Completed - RFA(s) closed'] + $array[$bu]['Completed Exported'] + $array[$bu]['Hide from Reports; Hide from Client Survey Explorer'] + $array[$bu]['Hide from Reports; OK for Client Survey Explorer'] + $array[$bu]['Completed - Export Failed']) / $array[$bu]['max'] * 100));
                        $task->setUpdatedaol(1);
                    }
                    break;
                case 307:
                    $bu = 'CN';
                    if ($array[$bu]['max'] > 0) {
                        $task->setAolPercent(round(($array[$bu]['Completed - Pending Export'] + $array[$bu]['Completed - RFA(s) closed'] + $array[$bu]['Completed Exported'] + $array[$bu]['Hide from Reports; Hide from Client Survey Explorer'] + $array[$bu]['Hide from Reports; OK for Client Survey Explorer'] + $array[$bu]['Completed - Export Failed']) / $array[$bu]['max'] * 100));
                        $task->setUpdatedaol(1);
                    }
                    break;
                case 308:
                    $bu = 'TW';
                    if ($array[$bu]['max'] > 0) {
                        $task->setAolPercent(round(($array[$bu]['Completed - Pending Export'] + $array[$bu]['Completed - RFA(s) closed'] + $array[$bu]['Completed Exported'] + $array[$bu]['Hide from Reports; Hide from Client Survey Explorer'] + $array[$bu]['Hide from Reports; OK for Client Survey Explorer'] + $array[$bu]['Completed - Export Failed']) / $array[$bu]['max'] * 100));
                        $task->setUpdatedaol(1);
                    }
                    break;
                case 309:
                    $bu = 'JP';
                    if ($array[$bu]['max'] > 0) {
                        $task->setAolPercent(round(($array[$bu]['Completed - Pending Export'] + $array[$bu]['Completed - RFA(s) closed'] + $array[$bu]['Completed Exported'] + $array[$bu]['Hide from Reports; Hide from Client Survey Explorer'] + $array[$bu]['Hide from Reports; OK for Client Survey Explorer'] + $array[$bu]['Completed - Export Failed']) / $array[$bu]['max'] * 100));
                        $task->setUpdatedaol(1);
                    }
                    break;
                case 310:
                    $bu = 'KR';
                    if ($array[$bu]['max'] > 0) {
                        $task->setAolPercent(round(($array[$bu]['Completed - Pending Export'] + $array[$bu]['Completed - RFA(s) closed'] + $array[$bu]['Completed Exported'] + $array[$bu]['Hide from Reports; Hide from Client Survey Explorer'] + $array[$bu]['Hide from Reports; OK for Client Survey Explorer'] + $array[$bu]['Completed - Export Failed']) / $array[$bu]['max'] * 100));
                        $task->setUpdatedaol(1);
                    }
                    break;
                case 311:
                    $bu = 'HK';
                    if ($array[$bu]['max'] > 0) {
                        $task->setAolPercent(round(($array[$bu]['Completed - Pending Export'] + $array[$bu]['Completed - RFA(s) closed'] + $array[$bu]['Completed Exported'] + $array[$bu]['Hide from Reports; Hide from Client Survey Explorer'] + $array[$bu]['Hide from Reports; OK for Client Survey Explorer'] + $array[$bu]['Completed - Export Failed']) / $array[$bu]['max'] * 100));
                        $task->setUpdatedaol(1);
                    }
                    break;
                case 312:
                    $bu = 'SG';
                    if ($array[$bu]['max'] > 0) {
                        $task->setAolPercent(round(($array[$bu]['Completed - Pending Export'] + $array[$bu]['Completed - RFA(s) closed'] + $array[$bu]['Completed Exported'] + $array[$bu]['Hide from Reports; Hide from Client Survey Explorer'] + $array[$bu]['Hide from Reports; OK for Client Survey Explorer'] + $array[$bu]['Completed - Export Failed']) / $array[$bu]['max'] * 100));
                        $task->setUpdatedaol(1);
                    }
                    break;
                case 313:
                    $bu = 'IN';
                    if ($array[$bu]['max'] > 0) {
                        $task->setAolPercent(round(($array[$bu]['Completed - Pending Export'] + $array[$bu]['Completed - RFA(s) closed'] + $array[$bu]['Completed Exported'] + $array[$bu]['Hide from Reports; Hide from Client Survey Explorer'] + $array[$bu]['Hide from Reports; OK for Client Survey Explorer'] + $array[$bu]['Completed - Export Failed']) / $array[$bu]['max'] * 100));
                        $task->setUpdatedaol(1);
                    }
                    break;
                case 314:
                    $bu = 'AU';
                    if ($array[$bu]['max'] > 0) {
                        $task->setAolPercent(round(($array[$bu]['Completed - Pending Export'] + $array[$bu]['Completed - RFA(s) closed'] + $array[$bu]['Completed Exported'] + $array[$bu]['Hide from Reports; Hide from Client Survey Explorer'] + $array[$bu]['Hide from Reports; OK for Client Survey Explorer'] + $array[$bu]['Completed - Export Failed']) / $array[$bu]['max'] * 100));
                        $task->setUpdatedaol(1);
                    }
                    break;
                case 315:
                    $bu = 'TH';
                    if ($array[$bu]['max'] > 0) {
                        $task->setAolPercent(round(($array[$bu]['Completed - Pending Export'] + $array[$bu]['Completed - RFA(s) closed'] + $array[$bu]['Completed Exported'] + $array[$bu]['Hide from Reports; Hide from Client Survey Explorer'] + $array[$bu]['Hide from Reports; OK for Client Survey Explorer'] + $array[$bu]['Completed - Export Failed']) / $array[$bu]['max'] * 100));
                        $task->setUpdatedaol(1);
                    }
                    break;
                case 316:
                    $bu = 'LA';
                    if ($array[$bu]['max'] > 0) {
                        $task->setAolPercent(round(($array[$bu]['Completed - Pending Export'] + $array[$bu]['Completed - RFA(s) closed'] + $array[$bu]['Completed Exported'] + $array[$bu]['Hide from Reports; Hide from Client Survey Explorer'] + $array[$bu]['Hide from Reports; OK for Client Survey Explorer'] + $array[$bu]['Completed - Export Failed']) / $array[$bu]['max'] * 100));
                        $task->setUpdatedaol(1);
                    }
                    break;
            }
            $em->persist($task);
        }
        $em->flush();
    }

    public function aolsyncAction() {
        //getIncomplete Project.
        $time_proj = $this->getRequest()->getContent();
        $incom_result = $this->inComplete($time_proj);
        $incomProj = $incom_result[0];
        $times = $incom_result[1];

        $survey = $this->readAolFile();
        //$this->writeLog($survey, "20130719");
//        $parseProj = $this->parseDescription($incomProj);
//        $parseSurvey = $this->parseSurveyName($survey);
//        $checked = $this->check($parseProj, $parseSurvey);
        $em = $this->getDoctrine()->getManager();
        $res = $em->getRepository('AlbatrossAceBundle:Bu')->findAll();
        $bus = array();
        foreach ($res as $b) {
            foreach ($b->getCountry() as $c)
                $bus[$b->getCode()][] = $c->getCode();
        }
        foreach ($incomProj as $proj) {
            $bu = $camp = null;
            if ($proj['description']) {
                /* Get Campaign */
                $tab = explode('|', $proj['description']);
                $name = $tab[0];
                if (isset($tab[1]))
                    $camp = $tab[1];

                /* Get BU */
                $tab = explode('[', $name);
                if (isset($tab[1])) {
                    $tab1 = explode(']', $tab[1]);
                    $bu = $tab1[0];
                    $name = $tab[0] . $tab1[1];
                }

                /* Create pattern for preg_match */
                $name = str_replace("$$", ".*", $name);
                $name = str_replace("/", "\/", $name);
                $pattern = '/^' . $name . '$/';
                $array = $this->initArrayStatus();

                foreach ($survey as $k => $s) {
                    /* if match project name */
                    if (preg_match($pattern, $k, $matches)) {
                        /* if there is a specific BU */
                        if ($bu) {
                            /* if there is a specific campaign */
                            if ($camp) {
                                foreach ($bus as $b => $cu) {
                                    foreach ($cu as $c) {
                                        if (isset($s[$c][$camp])) {
                                            foreach ($s[$c][$camp] as $status => $val) {
                                                if (!isset($array[$bu][$status]))
                                                    $array[$bu][$status] = $val;
                                                else
                                                    $array[$bu][$status] += $val;
                                                if (!isset($array[$bu]['max']))
                                                    $array[$bu]['max'] = $val;
                                                else
                                                    $array[$bu]['max'] += $val;
                                            }
                                        }
                                    }
                                }
                                /* all campaign */
                            } else {
                                foreach ($bus as $b => $cu) {
                                    foreach ($cu as $c1) {
                                        if (isset($s[$c1])) {
                                            foreach ($s[$c1] as $c) {
                                                foreach ($c as $status => $val) {
                                                    if (!isset($array[$bu][$status]))
                                                        $array[$bu][$status] = $val;
                                                    else
                                                        $array[$bu][$status] += $val;
                                                    if (!isset($array[$bu]['max']))
                                                        $array[$bu]['max'] = $val;
                                                    else
                                                        $array[$bu]['max'] += $val;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            /* all BU */
                        } else {
                            foreach ($bus as $bu1 => $cs) {
                                /* if there is a specific campaign */
                                if ($camp) {
                                    foreach ($cs as $c1) {
                                        if (isset($s[$c1][$camp])) {
                                            foreach ($s[$c1][$camp] as $status => $val) {
                                                if (!isset($array[$bu1][$status])) {
                                                    $array[$bu1][$status] = $val;
                                                } else {
                                                    $array[$bu1][$status] += $val;
                                                }
                                                if (!isset($array[$bu1]['max']))
                                                    $array[$bu1]['max'] = $val;
                                                else
                                                    $array[$bu1]['max'] += $val;
                                            }
                                        }
                                    }

                                    /* all campaign */
                                } else {
                                    foreach ($cs as $c1) {
                                        if (isset($s[$c1])) {
                                            foreach ($s[$c1] as $c) {
                                                foreach ($c as $status => $val) {
                                                    if (!isset($array[$bu1][$status])) {
                                                        $array[$bu1][$status] = $val;
                                                    } else {
                                                        $array[$bu1][$status] += $val;
                                                    }
                                                    if (!isset($array[$bu1]['max']))
                                                        $array[$bu1]['max'] = $val;
                                                    else
                                                        $array[$bu1]['max'] += $val;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
//                $this->writeLog(array(array($proj['name'] => $array)), "130719" . $proj['name']);
                $this->saveAolPercent($array, $proj);
            }
        }
        $time_proj = intval($time_proj);
        $times = intval($times);
        $time_proj++;
        $progress_bar = ceil(748 * ($time_proj / $times));
        if ($time_proj < $times) {
            $re = '{"times":"' . $time_proj . '","percent":"' . $progress_bar . '"}';
            return (new Response($re));
        } else {
            $re = '{"times":"' . "stop" . '","percent":"' . $progress_bar . '"}';
            return (new Response($re));
        }
    }

    public function inComplete($time_proj) {
        $final = array();
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->add('select', 'p')
                ->add('from', 'AlbatrossAceBundle:Project p')
                ->add('where', $qb->expr()->not(
                                $qb->expr()->eq('p.percent', ':percent')
                ))
        ;

        $qb->setParameters(array(
            'percent' => '100'
        ));
        $query = $qb->getQuery();
        $result = $query->getArrayResult();

        $times = ceil(count($result) / 100);

        $total_proj = array_chunk($result, 100);
        $final_proj = $total_proj[$time_proj];
        $final = array($final_proj, $times);

        return $final;
    }

    public function showfilesAction($type = null, $date = null) {
        if ($type == null)
            $result = $this->readAolFile(1);
        elseif ($type == 'daily')
            $result = $this->readAolFile(1, NULL, $type, $date);
        $list = '';
        foreach ($result as $re) {
            $retitle = $re;
            if (strlen($re) > 25) {
                $re = substr($re, 0, 20) . '... .csv';
            }
            $list .="<div class=\"filelist\" ><div class=\"filename\" title=\"$retitle\">$re</div><div onclick=\"deletefile('$retitle')\" class=\"deletefile\">delete</div></div>";
        }

        return (new Response($list));
    }

    public function deletefileAction($type = null, $date = null) {
        $re = $this->getRequest()->getContent();
        $result = $this->readAolFile(2, $re, $type, $date);
        exit($result);
    }

    public function readAolFile($prm = NULL, $fname = NULL, $type = NULL, $date = NULL) { //prm : null is get survey, 1 is read list, 2 is delete
//Read Excel file
        if ($type == NULL)
            $dir = 'aolExport/';
        elseif ($type == 'daily')
            $dir = 'aolExport2/';

        if ($date == null)
            $date = date('ymd');
        else
            $date = date('ymd', strtotime($date));

        $targetDir = $dir . $date;
        $result = array();
        $list = array();
        if (is_dir($targetDir)) {
            $file = scandir($targetDir);
            if ($prm == 2) {
                return unlink($targetDir . '/' . $fname);
            }
            foreach ($file as $f) {
                if (pathinfo($targetDir . $f, PATHINFO_EXTENSION) && pathinfo($targetDir . $f, PATHINFO_EXTENSION) == 'csv') {
                    if ($prm == 1) {
                        $list[] = $f;
                    } else {
                        $result = $this->readAolFile1($targetDir . '/', $result, $f);
                    }
                }
            }
        }
        return ($prm == 1) ? $list : $result;
    }

    public function readAolFile1($dir, $result, $file) {
        $csv = new SplFileObject($dir . $file);
        $excelArr = array();
        while (!$csv->eof()) {
            $excelArr[] = $csv->fgetcsv();
        }

        $survey = $country = $status = 0;

        foreach ($excelArr[0] as $k => $name) {
            switch ($name) {
                case 'Survey':
                    $survey = $k;
                case 'LocCountryCode':
                    $country = $k;
                case 'Campaign':
                    $campaign = $k;
                case 'SurveyStatusName':
                    $status = $k;
            }
        }

        unset($excelArr[0]);

        $total = 0;
        foreach ($excelArr as $earr) {
            if (isset($earr[$survey])) {
                if (!(isset($result[$earr[$survey]]) && isset($result[$earr[$survey]][strtoupper($earr[$country])]) && isset($result[$earr[$survey]][strtoupper($earr[$country])][substr($earr[$campaign], 0, 7)]) && isset($result[$earr[$survey]][strtoupper($earr[$country])][substr($earr[$campaign], 0, 7)][$earr[$status]])))
                    $result[$earr[$survey]][strtoupper($earr[$country])][substr($earr[$campaign], 0, 7)][$earr[$status]] = 0;
                $result[$earr[$survey]][strtoupper($earr[$country])][substr($earr[$campaign], 0, 7)][$earr[$status]]++;
            }
        }
        return $result;
    }

    public function writeLog($data, $ext) {
        $file = fopen("synclog/" . $ext . ".txt", "a+");

        foreach ($data as $projK => $projData) {
            fwrite($file, $projK . ":\n");
            foreach ($projData as $countryK => $countryData) {
                fwrite($file, '  ' . $countryK . ":\n");
                foreach ($countryData as $campaignK => $campaignData) {
                    fwrite($file, '      ' . $campaignK . ":\n");
                    foreach ($campaignData as $statusK => $statusData) {
                        fwrite($file, '        ' . $statusK . ':' . $statusData . "\n");
                    }
                }
            }
        }
        fclose($file);
        return;
    }

    public function saveAceTaskAction() {
        $times = $this->getRequest()->getContent();
        $result = $this->getUpdatedTask(); //get tasks updated
        $total_task = array_chunk($result, 100);
        $task = $total_task[$times];

        $this->login();
        foreach ($task as $re) {
            $percent = floor($re->getAolPercent() / 10);
            $url = 'http://api.aceproject.com/?fct=savetask&guid=' . $this->guid . '&taskid=' . $re->getAceId() . '&statusid=' . $re->getStatusId() . '&percentdone=' . $percent . '&format=DS';
            $res = file_get_contents($url);
        }

        $times = intval($times);
        $times++;
        $progress_bar = ceil(748 * (($times + 1) / count($total_task)));
        if ($times < count($total_task)) {
            $re = '{"times":"' . $times . '","percent":"' . $progress_bar . '"}';
            return (new Response($re));
        } else {
            $re = '{"times":"' . "stop" . '","percent":"' . $progress_bar . '"}';
            return (new Response($re));
        }
    }

    public function getUpdatedTask() { //get task updated;
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->add('select', 't')
                ->add('from', 'AlbatrossAceBundle:Task t')
                ->where('t.updatedaol=:updated')
        ;
        $qb->setParameters(array(
            'updated' => 1
        ));
        $query = $qb->getQuery();
        $result = $query->getResult();
        return $result;
    }

    public function updatestatusAction($tid) {
        $re = $this->getRequest()->getContent();
        $re = explode(':', $re);
        $em = $this->getDoctrine()->getManager();
        $task = $em->getRepository('AlbatrossAceBundle:Task')->findOneById($tid);
        $task->setUpdatedaol($re[1]);

        $em->persist($task);
        $em->flush();

        return (new Response('1'));
    }

    public function uploadProjectFileAction() {
        $em = $this->getDoctrine()->getManager();
        //bind form to get post data
        $attachmentsOption = $this->container->getParameter('valuelist');
        $attachmentsOption['attachments']['type'] = array('0' => 'IOF');
        $form = $this->createForm(new AttachmentsType(), $attachmentsOption);
        $form->bindRequest($this->getRequest());
        $data = $form->getData();

        //get project id and project name
        $pid = $data['project_id'];
        $date = date('ymd');

        if ($data['file'] != null) {
            //get file data
            $content = $data['file'];
            $filename = $content->getClientOriginalName();
            $content->move(
                    $this->get('kernel')->getRootDir() . '/../web/projectFiles/' . $date . '/', $filename
            );

            //get path info
            $path = 'projectFiles/' . $date . '/' . $filename;
        } else {
            $path = '';
        }

        //get user info
        $user_entity = $data['user'];

        //get date time
        $datetime = date('Y-m-d H:i:s');

        //type
        $type = (string) $data['type'];

        //get message
        $message = $data['message'];

        //get label
        $label = $data['label'];

        //get customwave
        $wave = $data['wave'];

        $attachtype = $data['attachtype'];

        if ($attachtype == 'edit') {
            $attachment = $em->getRepository('AlbatrossAceBundle:Attachments')->find($data['parents']);
        } else {
            $attachment = new Attachments();
        }
        $attachment->setUser($user_entity);
        $attachment->setSubmitteddate(new \DateTime($datetime));

        if ($data['attachtype'] == 'upload') {
            $attachment->setStatus('approved'); // default value
            $attachment->setPath($path);
            $attachment->setChildren(0);
            $attachment->setCustomwave($wave);
            $attachment->setLabel($label);
        } else if ($data['attachtype'] == 'update') {
            $attachment->setStatus('updated');
            $parentAttachment = $em->getRepository('AlbatrossAceBundle:Attachments')->find($data['parents']);
            $parentAttachment->setChildren(1);
            $attachment->setParent($parentAttachment);
            $attachment->setChildren(0);
            if ($path == '') {
                $attachment->setPath($parentAttachment->getpath());
            } else {
                $attachment->setPath($path);
            }

            if (!empty($wave)) {
                $firstIOF = $this->getFirstIOF($parentAttachment, $em);
                if($firstIOF->getCustomwave() == null)
                    $firstIOF->setCustomwave($wave);
            }

            if ($label != '') {
                $attachment->setLabel($label);
            } else {
                $attachment->setLabel($parentAttachment->getLabel());
            }

            $em->persist($parentAttachment);
        } else if ($data['attachtype'] == 'edit') {
            if ($path != '') {
                $attachment->setPath($path);
            }
            if (!empty($wave)) {
                if($attachment->getParent() == null && $attachment->getCustomwave() == null)
                    $attachment->setCustomwave($wave);
            }
            if ($label != '') {
                $attachment->setLabel($label);
            }
        }

        $attachment->setType($type);
        $attachment->setMessage($message);

        $em->persist($attachment);
        //save attach info
        if ($type == '0') { // when type is iof, in this case save all attach info for per bu and project id.
            $attachinfo = $data['attachinfo'];

            if ($data['attachtype'] == 'edit') {
                $this->deleteAttachInfoByAttachmentId($attachment->getId());
            }
            //get project info
            foreach ($attachinfo as $a) {
                $attachinfo_entity = new Attachinfo();
                $attachinfo_entity->setBu($a->getBu());
                $attachinfo_entity->setProject($a->getProject());
                $attachinfo_entity->setScope($a->getScope());
                $attachinfo_entity->setFwstartdate(new \DateTime($a->getFwstartdate()));
                $attachinfo_entity->setFwenddate(new \DateTime($a->getFwenddate()));
                $attachinfo_entity->setReportduedate(new \DateTime($a->getReportduedate()));
                $attachinfo_entity->setComment($a->getComment());
                $attachinfo_entity->setAttachments($attachment);

                $em->persist($attachinfo_entity);
            }
        } elseif ($type == '3') { // when type is other, in this case no need to save project id.
            $attachinfo_entity = new Attachinfo();
            //set project entity in information table for uploaded attachment
            $attachinfo_entity->setAttachments($attachment);
            $em->persist($attachinfo_entity);
        } else {
            $attachinfo_entity = new Attachinfo();
            //get project info
            $project_entity = $em->getRepository('AlbatrossAceBundle:Project')->find($pid);

            //set project entity in information table for uploaded attachment
            $attachinfo_entity->setProject($project_entity);
            $attachinfo_entity->setAttachments($attachment);
            $em->persist($attachinfo_entity);
        }

        $em->flush();
//        return new Response('<script type="text/javascript"> parent.projectFileFinish(); </script>');
        return $this->redirect($this->generateUrl('iofview', array(
                            'id' => $attachment->getId(),
                            'status' => $attachment->getStatus(),
        )));
    }
    
    protected function getFirstIOF($entity, $em){
        if($entity->getParent() != null) {
            if($parent = $em->getRepository('AlbatrossAceBundle:Attachments')->findFirstOneByParentID($entity->getParent()->getId())){
                $entity = $this->getFirstIOF($parent, $em);
            }
        }
        return $entity;
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

    public function filelistAction($current, $sid) {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('f')
                ->from('AlbatrossAceBundle:Attachments', 'f')
                ->leftJoin('f.filesection', 's')
                ->where('f.type = 3')
                ->andWhere('s.id = :sid')
                ->orderBy('f.id');
        $qb->setParameters(array(
            'sid' => $sid,
        ));
        $query = $qb->getQuery();
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $query, $this->get('request')->query->get('page', 1), 20
        ); /* page number */
        return $this->render('AlbatrossAceBundle:Default:filelist.html.twig', array(
                    'files' => $pagination,
                    'current' => $current,
        ));
    }

    public function uploadOtherFileAction() {
        $attachmentsOption = $this->container->getParameter('valuelist');
        $attachmentsOption['attachments']['type'] = array('3' => 'other');
        $form = $this->createForm(new AttachmentsType(), $attachmentsOption);

        $form->bindRequest($this->getRequest());
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
        $attachment->setFilesection($data['filesection']);
        $em->persist($attachment);
        $em->flush();
        $sectionId = $data['filesection']->getId();
        return $this->redirect($this->generateUrl('filelist', array('sid' => $sectionId)));
    }

    public function deleteOtherFileAction($id) {
        $em = $this->getDoctrine()->getManager();
        $file_entity = $em->getRepository('AlbatrossAceBundle:Attachments')->find($id);
        unlink($file_entity->getPath());
        $em->remove($file_entity);
        $em->flush();

        return $this->redirect($this->generateUrl('filelist'));
    }

    public function downloadOtherFileAction($id) {
        $em = $this->getDoctrine()->getManager();
        $header = $this->getRequest()->server->getHeaders();
        $file_entity = $em->getRepository('AlbatrossAceBundle:Attachments')->find($id);
        $file_dir = $file_entity->getPath();
        $file_arr = explode('/', $file_dir);
        $file_name = $file_arr['2'];
        $encoded_filename_pre = urlencode($file_name);  
        $encoded_filename = str_replace("+", "%20", $encoded_filename_pre);  
        $ua = $header['USER_AGENT'];
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

    public function ioflistAction($current) {
        //search
        $statusOption = $this->container->getParameter('valuelist');
        $status = $statusOption['attachments']['status'];
        //array_unshift the status array put all in the 0 value
        array_unshift($statusOption['attachments']['status'], 'All');
        $iofsearchForm = $this->createForm(new IofsearchType(), $statusOption);

        $iofsearchForm->bind($this->getRequest());
        $data = $iofsearchForm->getData();

        $src_client = $data['label'];
        $src_status = $data['status'];
        $src_bu = $data['bu'];
        $src_user = $data['user'];
        $src_proj = $data['project'];
        $src_number = $data['number'];
        $src_t_f = $data['submit_from'];
        $src_t_t = $data['submit_to'];

        $secu = $this->container->get('security.context');
        $user = $secu->getToken()->getUser();
        $uid = $user->getId();

        //get iof file list
        $result = $this->searchIofFile($src_client, $src_status, $src_bu, $src_user, $src_proj, $src_number, $src_t_f, $src_t_t);
        return $this->render('AlbatrossAceBundle:Default:ioflist.html.twig', array(
                    'files' => $result,
                    'current' => $current,
                    'status' => $status,
                    'iofsearchForm' => $iofsearchForm->createView(),
                    'uid' => $uid
        ));
    }

    public function getSpecificProjectAction() {
        $re = $this->getRequest()->getContent();
        $result = $this->findProjectByClient($re);
        $html = '<option value=""></option>';

        foreach ($result as $re) {
            $html .= '<option value="' . $re['id'] . '">' . $re['name'] . '</option>';
        }

        return new Response($html);
    }

    protected function findProjectByClient($customclientId) {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->add('select', 'p')
                ->add('from', 'AlbatrossCustomBundle:Customproject p')
                ->where('p.customclient = :c')
        ;
        $qb->setParameters(array(
            'c' => $customclientId
        ));
        $query = $qb->getQuery();
        $result = $query->getArrayResult();

        return $result;
    }

    public function getSpecificWaveAction() {
        $re = $this->getRequest()->getContent();
        $result = $this->findWaveByProject($re);
        $html = '<option value=""></option>';

        foreach ($result as $re) {
            $html .= '<option value="' . $re['id'] . '">' . $re['name'] . '</option>';
        }

        return new Response($html);
    }

    protected function findWaveByProject($customprojectId) {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->add('select', 'w')
                ->add('from', 'AlbatrossCustomBundle:Customwave w')
                ->where('w.customproject = :p')
        ;
        $qb->setParameters(array(
            'p' => $customprojectId
        ));
        $query = $qb->getQuery();
        $result = $query->getArrayResult();

        return $result;
    }

    //get attachment info list by selected wave
    public function autoAttachmentInfoListAction() {
        $waveId = $this->getRequest()->getContent();
        $selectedProj = $this->findaceProjBySelecWave($waveId);
        $getJsonResponsString = $this->attachInfoJsonString($selectedProj);

        return new Response($getJsonResponsString);
    }

    //find ace project by selected wave
    protected function findaceProjBySelecWave($waveId) {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->add('select', 't, p')
                ->add('from', 'AlbatrossAceBundle:Task t')
                ->leftJoin('t.project', 'p')
                ->leftJoin('p.customwave', 'w')
                ->where('t.number BETWEEN 101 AND 116 OR t.number = 600')
                ->andWhere('t.resume LIKE :resumea')
                ->andWhere('w.id = :cw');
        $qb->setParameters(array(
            'cw' => $waveId,
            'resumea' => '%field%'
        ));
        $query = $qb->getQuery();
        $result = $query->getArrayResult();

        return $result;
    }

    protected function attachInfoJsonString($objList) {
        $resultArr = array();
        $finalString = '';
        $dueArr = array();
        $buList = $this->getBuNumberIdArr();

        foreach ($objList as $list) {
            if ($list['number'] == 600) {
                $dueArr[$list['project']['id']] = $list['reportduedate'];
            }
        }

        foreach ($objList as $list) {
            if ($list['number'] >= 101 && $list['number'] <= 116) {
                $resultArr[$list['project']['id']][$buList[$list['number'] - 100]]['scope'] = $list['scope'] ? strip_tags($list['scope']) : '0';
                $resultArr[$list['project']['id']][$buList[$list['number'] - 100]]['fws'] = $list['fwstartdate'];
                $resultArr[$list['project']['id']][$buList[$list['number'] - 100]]['fwe'] = $list['fwenddate'];
                $resultArr[$list['project']['id']][$buList[$list['number'] - 100]]['comment'] = '';
                $resultArr[$list['project']['id']][$buList[$list['number'] - 100]]['due'] = $dueArr[$list['project']['id']];
                $resultArr[$list['project']['id']][$buList[$list['number'] - 100]]['bu'] = $buList[$list['number'] - 100];
            }
        }

        foreach ($resultArr as $key => $r) {
            foreach ($r as $k => $v) {
                $finalString .= $v['scope'] . ':' . $v['fws'] . ':' . $v['fwe'] . ':' . $v['due'] . ':' . $v['comment'] . ':' . $k . ':' . $key . ',';
            }
        }
        $finalString = trim($finalString, ',');

        return $finalString;
    }

    protected function getBuNumberIdArr() {
        $em = $this->getDoctrine()->getManager();
        $bu = $em->getRepository('AlbatrossAceBundle:Bu')->findAll();
        $buArr = array();
        foreach ($bu as $b) {
            $buArr[$b->getNumber()] = $b->getId();
        }
        return $buArr;
    }
    public function viewiofAction($id, $status, $current) {
        $em = $this->getDoctrine()->getManager();
        $IOFEntity = $em->getRepository('AlbatrossAceBundle:Attachments')->find($id);
        $IOFFileEntity = $IOFEntity->getIoffile()->toArray();
        $IOFInfoEntity = $IOFEntity->getAttachinfo()->toArray();
        
        $result = array();
        foreach($IOFFileEntity as $key => $files){
            if($files->getFormindex() == null){
                $index1 = 1;
            }else{
                $index1 = $files->getFormindex();
            }
            if($files->getFormindex2() == null){
                $index2 = 1;
            }else{
                $index2 = $files->getFormindex2();
            }
            $result[$index1]['fileinfo'][$index2]['file'][$key]['label'] = $files->getLabel();
            $result[$index1]['fileinfo'][$index2]['file'][$key]['path'] = $files->getPath();
            $result[$index1]['fileinfo'][$index2]['file'][$key]['fid'] = $files->getId();
            $result[$index1]['fileinfo'][$index2]['message'] = $files->getIofmessage()->getMessage();
        }
        if(empty($IOFInfoEntity)){
            $result[1]['project'][1]['bu'] = 'none';
            $result[1]['project'][1]['project'] = 'none';
        }else{
            foreach($IOFInfoEntity as $key => $info){
                if($info->getFormindex() == null){
                    $index1 = 1;
                }else{
                    $index1 = $info->getFormindex();
                }
                $result[$index1]['project'][$key]['bu'] = $info->getBu()->getName();
                $result[$index1]['project'][$key]['project'] = $info->getProject()->getName();
                $result[$index1]['project'][$key]['fws'] = $info->getFwstartdate()->format('Y-m-d');
                $result[$index1]['project'][$key]['fwe'] = $info->getFwenddate()->format('Y-m-d');
                $result[$index1]['project'][$key]['reportdue'] = ($info->getReporttype()) ? $info->getReportduedatetext() : $info->getReportduedate()->format('Y-m-d');
                $result[$index1]['project'][$key]['scope'] = $info->getScope();
                $result[$index1]['project'][$key]['comment'] = $info->getComment();
            }
        }
        
        $otherInfo = array();
        if($IOFEntity->getSubmitby()){
            $submitby = $IOFEntity->getSubmitby();
        }else{
            $submitby = $IOFEntity->getUser()->getId();
        }
        
        $otherInfo['user'] = $em->getRepository('AlbatrossUserBundle:User')->find($submitby);
        $otherInfo['pm'] = $IOFEntity->getUser()->getUsername();
        $otherInfo['wave']['id'] = $IOFEntity->getCustomwave()->getId();
        $otherInfo['wave']['name'] = $IOFEntity->getCustomwave()->getName();
        $otherInfo['wave']['pid'] = $IOFEntity->getCustomwave()->getCustomproject()->getId();
        $otherInfo['iof'] = $id;
        
        return $this->render('AlbatrossAceBundle:Default:viewIOF.html.twig', array(
            'result' => $result,
            'current' => $current,
            'status' => $status,
            'otherInfo' => $otherInfo
        ));
    }
    public function downloadIOFAction($fid) {
        $em = $this->getDoctrine()->getManager();
        $file_entity = $em->getRepository('AlbatrossAceBundle:IOFFile')->find($fid);
        $file_dir = $file_entity->getPath();
        $file_arr = explode('/', $file_dir);
        $header = $this->getRequest()->server->getHeaders();
        $file_name = $file_arr['2'];
        $encoded_filename_pre = urlencode($file_name);  
        $encoded_filename = str_replace("+", "%20", $encoded_filename_pre);  
        $ua = $header['USER_AGENT'];
        
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
    public function iofviewAction($id, $status, $current) {
        $em = $this->getDoctrine()->getManager();
        if ($status == 'approved') {
            $button = 0;
            $preInfo = '';
        } else {// means there is some previous version; show the button to list previous version
            $preInfo = $this->getPreVersion($id);
            $button = 1;
        }

        $secu = $this->container->get('security.context');
        $user = $secu->getToken()->getUser();
        $uid = $user->getId();

        $statusOption = $this->container->getParameter('valuelist');
        $status = $statusOption['attachments']['status'];

        $qb = $em->createQueryBuilder();
        $qb->select('i')
                ->from('AlbatrossAceBundle:Attachinfo', 'i')
                ->leftJoin('i.attachments', 'a')
                ->where('a.id = :id')
                ->orderBy('i.id');
        $qb->setParameters(array(
            'id' => $id,
        ));
        $query = $qb->getQuery();
        $result = $query->getResult();

        $attachment = $em->getRepository('AlbatrossAceBundle:Attachments')->find($id);
        $message = $attachment->getMessage();

        $attachmentsOption = $this->container->getParameter('valuelist');
        $attachmentsOption['attachments']['type'] = array('0' => 'IOF');
        $attachmentsForm = $this->createForm(new AttachmentsType(), $attachmentsOption);

        if($attachment->getParent() != null ){
            $firstVersionIOF = $this->getFirstIOF($attachment, $em);
            if($firstVersionIOF->getCustomwave() == null){
                $firstVersionIOF = null;
            }
        }else{
            $firstVersionIOF = null;
        }
        return $this->render('AlbatrossAceBundle:Default:iofview.html.twig', array(
                    'infomation' => $result,
                    'current' => $current,
                    'aid' => $id,
                    'message' => $message,
                    'button' => $button,
                    'preinfo' => $preInfo,
                    'attachmentsForm' => $attachmentsForm->createView(),
                    'file' => $attachment,
                    'status' => $status,
                    'uid' => $uid,
                    'firstVersionIOF' => $firstVersionIOF
        ));
    }

    public function getPreVersion($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AlbatrossAceBundle:Attachments')->find($id);
        if ($entity->getParent() != null) {
            $pid = $entity->getParent()->getId();
        }

        $final = $this->getIOFParent($pid, array(), $em);
        return $final;
    }

    protected function getIOFParent($pid, $result, $em) {
        $entity = $em->getRepository('AlbatrossAceBundle:Attachments')->find($pid);
        $result[] = $entity;
        if ($entity->getParent() != null) {
            $pid = $entity->getParent()->getId();
            $result = $this->getIOFParent($pid, $result, $em);
            return $result;
        } else {
            return $result;
        }
    }

    public function searchIofFile($src_client, $src_status, $src_bu, $src_user, $src_proj, $src_number, $src_t_f, $src_t_t) {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        switch ($src_status) {
            case 0:
                $src_status = '';
                break;
            case 1:
                $src_status = '0';
                break;
            case 2:
                $src_status = '1';
                break;
            case 3:
                $src_status = '2';
                break;
        }
        if ($src_bu == null) {
            $src_bu = '';
        } else {
            $src_bu = $src_bu->getId();
        }
        if ($src_t_f != '') {
            $src_t_f = new \DateTime($src_t_f);
        }
        if ($src_t_t != '') {
            $src_t_t = new \DateTime($src_t_t);
        }
        if (($src_client != '' || $src_status != '' || $src_bu != '' || $src_user != '' || $src_proj != '' || $src_number != '' || $src_t_f != '' || $src_t_t != '')) {
            $qb->select('file, f, pa, u, w, cp, c')
                    ->from('AlbatrossAceBundle:IOFFile', 'file')
                    ->leftjoin('file.attachments', 'f')
                    ->leftjoin('f.attachinfo', 'i')
                    ->leftjoin('f.user', 'u')
                    ->leftjoin('i.project', 'p')
                    ->leftjoin('i.bu', 'b')
                    ->leftjoin('f.parent', 'pa')
                    ->leftjoin('f.customwave', 'w')
                    ->leftjoin('w.customproject', 'cp')
                    ->leftjoin('cp.customclient', 'c')
                    ->where('f.type = 0')
                    ->andWhere('f.children = 0');
            if ($src_client != '')
                $qb->andWhere('file.label LIKE :client');
            if ($src_status != '')
                $qb->andWhere('f.status = :status');
            if ($src_bu != '')
                $qb->andWhere('b.id = :bu');
            if ($src_user != '')
                $qb->andWhere('u.id = :user');
            if ($src_proj != '')
                $qb->andWhere('p.name LIKE :project');
//        if ($src_number != '')
//            $qb->andWhere('p.number LIKE :project');
            if ($src_t_f != '' && $src_t_t == '')
                $qb->andWhere('f.submitteddate BETWEEN :src_t_f AND :today');
            if ($src_t_t != '' && $src_t_f == '')
                $qb->andWhere('f.submitteddate BETWEEN :last AND :src_t_t');
            if ($src_t_t != '' && $src_t_f != '')
                $qb->andWhere('f.submitteddate BETWEEN :src_t_f AND :src_t_t');
//setParameters
            $parameter = array();
            if ($src_client != '') {
                $parameter = array_merge($parameter, array(
                    'client' => '%' . $src_client . '%'
                ));
            }
            if ($src_status != '') {
                $parameter = array_merge($parameter, array(
                    'status' => $src_status
                ));
            }
            if ($src_bu != '') {
                $parameter = array_merge($parameter, array(
                    'bu' => $src_bu
                ));
            }
            if ($src_user != '') {
                $parameter = array_merge($parameter, array(
                    'user' => $src_user->getId()
                ));
            }
            if ($src_proj != '') {
                $parameter = array_merge($parameter, array(
                    'project' => '%' . $src_proj . '%'
                ));
            }
//        if ($src_number != '')
            if ($src_t_f != '' && $src_t_t == '') {
                $parameter = array_merge($parameter, array(
                    'src_t_f' => $src_t_f,
                    'today' => date('Y-m-d')
                ));
            }
            if ($src_t_t != '' && $src_t_f == '') {
                $parameter = array_merge($parameter, array(
                    'last' => new \DateTime('2013-01-01'),
                    'rc_t_f' => $src_t_f
                ));
            }
            if ($src_t_t != '' && $src_t_f != '') {
                $parameter = array_merge($parameter, array(
                    'src_t_f' => $src_t_f,
                    'src_t_t' => $src_t_t
                ));
            }
            $qb->setParameters($parameter);
        } else {
            $qb->select('file, f, pa, u, w, c')
                    ->from('AlbatrossAceBundle:IOFFile', 'file')
                    ->leftjoin('file.attachments', 'f')
                    ->leftjoin('f.parent', 'pa')
                    ->leftjoin('f.user', 'u')
                    ->leftjoin('f.customwave', 'w')
                    ->leftjoin('w.customproject', 'p')
                    ->leftjoin('p.customclient', 'c')
                    ->where('f.type = 0')
                    ->andWhere('f.children = 0')
                    ->orderBy('f.id');
        }

        $query = $qb->getQuery();

        $result = $query->getArrayResult();
        $check = array();
        foreach ($result as $key => $re) {
            if ($re['attachments']['parent'] != null) {
                if (isset($check[$re['attachments']['parent']['id']])) {
                    unset($result[$check[$re['attachments']['parent']['id']]]);
                    $check[$re['attachments']['parent']['id']] = $key;
                } else {
                    $check[$re['attachments']['parent']['id']] = $key;
                }
            }
        }

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $result, $this->get('request')->query->get('page', 1), 30
        ); /* page number */
        return $pagination;
    }

    //forecast page index
    public function forecastAction($current, $isrefresh) {
        $month = $this->getTwelveMonth();

        $secu = $this->container->get('security.context');
        $user = $secu->getToken()->getUser();
        if ($user->getIdentity()->getName() == 'Project Manager' || $user->getIdentity()->getName() == 'Senior Project Manager' || $user->getIdentity()->getName() == 'BU manager')
            $user_bu = $user->getPosition()->getId();
        else
            $user_bu = '';
        $parameter = array('month' => $month, 'user_bu' => $user_bu);
        $forecastsearchForm = $this->createForm(new ForecastsearchType(), $parameter);

        $forecastsearchForm->bind($this->getRequest());
        $data = $forecastsearchForm->getData();
        $pmlist = $this->getPmPostion();
        $forecastForm = $this->createForm(new ForecastType());
        $src_client = $data['client'];
        $src_bu = $data['bu'];
        $src_user = $data['user'];
        $src_proj = $data['project'];
        $src_contract = $data['contract'];
        $src_step = $data['step'];
        $src_scope_f = $data['scope_f'];
        $src_scope_t = $data['scope_t'];
        $src_fw_s_f = $data['fw_s_f'];
        $src_fw_s_t = $data['fw_s_t'];
        $src_fw_e_f = $data['fw_e_f'];
        $src_fw_e_t = $data['fw_e_t'];
        $src_due_f = $data['due_f'];
        $src_due_t = $data['due_t'];
        $src_scope_month = $data['f_month'];
        $src_scope_All = $data['scope_year'];
        $src_update_f = $data['update_f'];
        $src_update_t = $data['update_t'];
        $tasklist = $this->getForecastTasks();
        $ioflist = $this->getIofTasks();
        $pmEditList = $this->getPmEditList();
        $userArr = $src_user->toArray();
        if($src_client == null && empty($userArr) && $src_proj == null && $src_contract == null && 
                empty($src_step) && $src_scope_f == null && $src_scope_t == null && $src_fw_s_f == null && 
                $src_fw_s_t == null && $src_fw_e_f == null && $src_fw_e_t == null && $src_due_f == null && 
                $src_due_t == null && empty($src_scope_month) && $src_update_f == null && $src_update_t == null){
            $lastYearScope = $this->getLastYearScope($src_bu);
        }else{
            $lastYearScope = '';
        }
        $result = $this->getForecastPageList($tasklist, $ioflist, $pmEditList, $isrefresh);
        $final = $this->filterConditions($result, $src_client, $src_bu, 
                $src_user, $src_proj, $src_contract, $src_step, 
                $src_scope_f, $src_scope_t, $src_fw_s_f, $src_fw_s_t, 
                $src_fw_e_f, $src_fw_e_t, $src_due_f, $src_due_t,
                $src_scope_month, $src_scope_All, $src_update_f, $src_update_t);

        return $this->render('AlbatrossAceBundle:Default:forecast.html.twig', array(
                    'tasks' => $final,
                    'current' => $current,
                    'month' => $month,
                    'forecastForm' => $forecastForm->createView(),
                    'forecastsearchForm' => $forecastsearchForm->createView(),
                    'user' => $user,
                    'pmlist' => $pmlist,
                    'lastYearScope' => $lastYearScope,
        ));
    }
    
    protected function getLastYearScope($src_bu){
        //get Current month and year
        $currentTime = date('Y-m', time());
        //get Arr of last year but same month
        $lastYearArr = array();
        $lastYearFinal = array();
        for($i = 0; $i < 12; $i++){
            $lastYearArr[$i] = date('Y-m', strtotime('-1 year, +'.$i.' month', strtotime($currentTime)));
        }
        foreach($lastYearArr as $key => $ly){
            $daysOfMonth = date('t', strtotime($ly));
            $lastYearFinal[$key] = $ly .'-'.$daysOfMonth;
        }
        
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('date')
                ->from('AlbatrossDailyBundle:Date', 'date')
                ->leftJoin('date.bu', 'bu');
        if($src_bu == null){
            $qb->where('bu.id is null');
        }else{
            $qb->where('bu.id = :bid');
            $qb->setParameter('bid', $src_bu->getId());
        }
        $qb->andWhere('date.dailydate IN (:dayArr)')
                ->setParameter('dayArr', $lastYearFinal);
        $query = $qb->getQuery();
        $result = $query->getArrayResult();
        
        $dateIdArr = array();
        foreach($result as $re){
            $dateIdArr[] = $re['id'];
        }
        $scopeQb = $em->createQueryBuilder();
        $scopeQb->select('number', 'date2')
                ->from('AlbatrossDailyBundle:Number', 'number')
                ->leftJoin('number.date', 'date2')
                ->leftJoin('number.status', 'status')
                ->where('date2.id IN (:dateId)')
                ->setParameter('dateId', $dateIdArr)
                ->andWhere('status.id = 1');
        $scopeQuery = $scopeQb->getQuery();
        $scopeResult = $scopeQuery->getArrayResult();
        $temp = array_flip($lastYearFinal);
        foreach($scopeResult as $s){
            $temp[$s['date']['dailydate']->format('Y-m-d')] = $s['number'];
        }
        $final = array();
        foreach($temp as $key => $f){
            if(is_int($f)){
                $temp[$key] = '0';
            }
            $final[] = $temp[$key];
        }
        
        return $final;
    }
    
    public function getForecastPageList($result, $ioflist, $pmEditList, $isrefresh) {
        $buArr = $this->getBuNumberCodeArr();
        $userArr = $this->userNameMatch();
        $forecastList = array();
        $scopeNum = array();
        foreach ($result as $re) {
            if ($re['number'] >= 101 && $re['number'] <= 116) {
                $buKey = $re['number'] - 100;
                if (isset($pmEditList['refer'][$re['id']])) {
                    $pmEdit = $pmEditList['result'][$pmEditList['refer'][$re['id']]];
                    $fwsobj = $pmEdit['fwstartdate'];
                    $fweobj = $pmEdit['fwenddate'];
                    $allobj = $pmEdit['reportduedate'];
                    $updateobj = $pmEdit['edittime'];
                    $fws = $fwsobj->format('Y-m-d');
                    $fwe = $fweobj->format('Y-m-d');
                    if(!$pmEdit['reporttype']){
                        $allobj = $pmEdit['reportduedate'];
                        $all = $allobj->format('Y-m-d');
                        $reportType = 'date';
                    }else{
                        $all = $pmEdit['reportduetext'];
                        $reportType = 'text';
                    }
                    if($updateobj != ''){
                        $update = $updateobj->format('Y-m-d');
                    }else{
                        $update = '';
                    }
                    $forecastList[$re['project']['id']]['bu'][$buArr[$buKey]]['step'] = 'PM';
                    $forecastList[$re['project']['id']]['bu'][$buArr[$buKey]]['reprottype'] = $reportType;
                    $forecastList[$re['project']['id']]['bu'][$buArr[$buKey]]['fws'] = $fws;
                    $forecastList[$re['project']['id']]['bu'][$buArr[$buKey]]['fwe'] = $fwe;
                    $forecastList[$re['project']['id']]['bu'][$buArr[$buKey]]['scope'] = $pmEdit['scope'] ? $pmEdit['scope'] : 0;
                    $forecastList[$re['project']['id']]['bu'][$buArr[$buKey]]['pm'] = $pmEdit['user']['username'];
                    $forecastList[$re['project']['id']]['bu'][$buArr[$buKey]]['All'] = $all;
                    $forecastList[$re['project']['id']]['bu'][$buArr[$buKey]]['pmid'] = $pmEdit['user']['id'];
                    $forecastList[$re['project']['id']]['bu'][$buArr[$buKey]]['update'] = $update;
                    $forecastList[$re['project']['id']]['pm'] = $pmEdit['user']['username'];
                    $forecastList[$re['project']['id']]['pmid'] = $pmEdit['user']['id'];
                    $forecastList[$re['project']['id']]['All'] = $all;
                }
                if (isset($ioflist['refer'][$re['project']['id']][$buKey])) {
                    $attachmentInfo = $ioflist['result'][$ioflist['refer'][$re['project']['id']][$buKey]];
                    $fwsobj = $attachmentInfo['fwstartdate'];
                    $fweobj = $attachmentInfo['fwenddate'];
                    if(!$attachmentInfo['reporttype']){
                        $allobj = $attachmentInfo['reportduedate'];
                        $all = $allobj->format('Y-m-d');
                    }else{
                        $all = $attachmentInfo['reportduedatetext'];
                    }
                    $updateobj = $attachmentInfo['attachments']['submitteddate'];
                    $fws = $fwsobj->format('Y-m-d');
                    $fwe = $fweobj->format('Y-m-d');
                    if($updateobj != ''){
                        $update = $updateobj->format('Y-m-d');
                    }else{
                        $update = '';
                    }
                    $forecastList[$re['project']['id']]['bu'][$buArr[$buKey]]['step'] = 'IOF';
                    $forecastList[$re['project']['id']]['bu'][$buArr[$buKey]]['reprottype'] = 'date';
                    $forecastList[$re['project']['id']]['bu'][$buArr[$buKey]]['fws'] = $fws;
                    $forecastList[$re['project']['id']]['bu'][$buArr[$buKey]]['fwe'] = $fwe;
                    $forecastList[$re['project']['id']]['bu'][$buArr[$buKey]]['scope'] = $attachmentInfo['scope'] ? $attachmentInfo['scope'] : 0;
                    $forecastList[$re['project']['id']]['bu'][$buArr[$buKey]]['pm'] = $attachmentInfo['attachments']['user']['username'];
                    $forecastList[$re['project']['id']]['bu'][$buArr[$buKey]]['All'] = $all;
                    $forecastList[$re['project']['id']]['bu'][$buArr[$buKey]]['pmid'] = $attachmentInfo['attachments']['user']['id'];
                    $forecastList[$re['project']['id']]['pm'] = $attachmentInfo['attachments']['user']['username'];
                    $forecastList[$re['project']['id']]['pmid'] = $attachmentInfo['attachments']['user']['id'];
                    $forecastList[$re['project']['id']]['bu'][$buArr[$buKey]]['update'] = $update;
                    $forecastList[$re['project']['id']]['All'] = $all;
                }
                if (!isset($forecastList[$re['project']['id']]['bu'][$buArr[$buKey]])) {
                    $updateobj = $re['createdDate'];
                    if($updateobj != ''){
                        $update = $updateobj->format('Y-m-d');
                    }else{
                        $update = '';
                    }
                    $forecastList[$re['project']['id']]['bu'][$buArr[$buKey]]['step'] = 'Contract';
                    $forecastList[$re['project']['id']]['bu'][$buArr[$buKey]]['reprottype'] = 'date';
                    $forecastList[$re['project']['id']]['bu'][$buArr[$buKey]]['fws'] = $re['fwstartdate'];
                    $forecastList[$re['project']['id']]['bu'][$buArr[$buKey]]['fwe'] = $re['fwenddate'];
                    $forecastList[$re['project']['id']]['bu'][$buArr[$buKey]]['scope'] = $re['scope'] ? $re['scope'] : 0;
                    $forecastList[$re['project']['id']]['bu'][$buArr[$buKey]]['update'] = $update;
                    $forecastList[$re['project']['id']]['pm'] = '';
                    $forecastList[$re['project']['id']]['pmid'] = 0;
                    $forecastList[$re['project']['id']]['All'] = '';
                }
                $forecastList[$re['project']['id']]['bu'][$buArr[$buKey]]['tid'] = $re['id'];
                $forecastList[$re['project']['id']]['bu'][$buArr[$buKey]]['pid'] = $re['project']['id'];
                $forecastList[$re['project']['id']]['bu'][$buArr[$buKey]]['name'] = $re['project']['name'];
                $forecastList[$re['project']['id']]['bu'][$buArr[$buKey]]['contract'] = (strlen($re['projectnumber']) <= 14) ? $re['projectnumber'] : substr($re['projectnumber'], 0, 14) . '...';
                $forecastList[$re['project']['id']]['bu'][$buArr[$buKey]]['contracttitle'] = $re['projectnumber'];
                $forecastList[$re['project']['id']]['bu'][$buArr[$buKey]]['bu'] = $buArr[$buKey];
                //formula
                $formula = $this->formula($forecastList[$re['project']['id']]['bu'][$buArr[$buKey]]['fws'], $forecastList[$re['project']['id']]['bu'][$buArr[$buKey]]['fwe'], $forecastList[$re['project']['id']]['bu'][$buArr[$buKey]]['scope']);
                $forecastList[$re['project']['id']]['bu'][$buArr[$buKey]]['forecast'] = $formula;
                $forecastList[$re['project']['id']]['bu'][$buArr[$buKey]]['projectName'] = $re['project']['name'];
            }
        }
        
        foreach ($result as $re) {
            if ($re['number'] == 600) {
                if (isset($forecastList[$re['project']['id']]['bu']) && $forecastList[$re['project']['id']]['pm'] == '') {
                    $acename = explode(',', $re['pm']);
                    $platformArr = array();
                    foreach ($acename as $an) {
                        $an = trim($an);
                        if (isset($userArr[$an])) {
                            $platformArr[] = $userArr[$an]['name'];
                            $pmid = $userArr[$an]['id'];
                        } else {
                            $platformArr[] = $an . '(ace)';
                            $pmid = 0;
                        }
                    }
                    $platformName = implode(',', $platformArr);
                    $forecastList[$re['project']['id']]['pm'] = $platformName;
                    $forecastList[$re['project']['id']]['pmid'] = $pmid;
                }
                if (isset($forecastList[$re['project']['id']]['bu']) && $forecastList[$re['project']['id']]['All'] == '') {
                    $forecastList[$re['project']['id']]['All'] = $re['reportduedate'];
                }
            }
            if ($isrefresh == '1') {
                if ($re['number'] >= 101 && $re['number'] <= 116) {
                    $buKey = $re['number'] - 100;
                    //save scope
                    if ($forecastList[$re['project']['id']]['bu'][$buArr[$buKey]]['scope'] != 0) {
                        $scope = $forecastList[$re['project']['id']]['bu'][$buArr[$buKey]]['scope'];
                        $projName = $forecastList[$re['project']['id']]['bu'][$buArr[$buKey]]['projectName'];
                        $fws = $forecastList[$re['project']['id']]['bu'][$buArr[$buKey]]['fws'];
                        $fwe = $forecastList[$re['project']['id']]['bu'][$buArr[$buKey]]['fwe'];
                        $gabDate = ceil((strtotime($fwe) - strtotime($fws)) / 86400);
                        $scopeNum = $this->saveForecastScope($scope, $fws, $fwe, $gabDate, $buArr[$buKey], $scopeNum, $projName);
                    }
                }
            }
        }

        if ($isrefresh == '1') {
            $em = $this->getDoctrine()->getManager();
            $all = array();

            foreach ($scopeNum as $key => $s) {
                $bu = $em->getRepository('AlbatrossAceBundle:Bu')->findByCode($key);
                foreach ($s as $k => $num) {
                    if (!isset($all[$k])) {
                        $all[$k] = $num;
                    } else {
                        $all[$k] = $all[$k] + $num;
                    }
                    if (!($newForecast = $em->getRepository('AlbatrossAceBundle:ForecastScope')->findOneByMonthAndBu($k, $bu[0]->getId()))) {
                        $newForecast = new ForecastScope();
                        $newForecast->setBu($bu[0]);
                        $newForecast->setMonth($k);
                    }
                    $newForecast->setForecast($num);
                    $em->persist($newForecast);
                }
            }
            foreach ($all as $key => $a) {
                if (!($allForecast = $em->getRepository('AlbatrossAceBundle:ForecastScope')->findOneByMonthAndBu($key, null))) {
                    $allForecast = new ForecastScope();
                    $allForecast->setBu(null);
                    $allForecast->setMonth($key);
                }
                $allForecast->setForecast($a);
                $em->persist($allForecast);
            }
            $em->flush();
            print_r(1);
            exit();
        }

        return $forecastList;
    }

    public function getBuNumberCodeArr() {
        $em = $this->getDoctrine()->getManager();
        $bu = $em->getRepository('AlbatrossAceBundle:Bu')->findAll();
        $buArr = array();
        foreach ($bu as $b) {
            $buArr[$b->getNumber()] = $b->getCode();
        }
        return $buArr;
    }

    //return 12 month from current month 
    public function getTwelveMonth() {
        $result = array();
        $cur = strtotime(date('Y-m-01', time()));
        for ($i = 0; $i <= 11; $i++) {
            $result[$i] = date('M', $cur);
            $cur = strtotime('next month', $cur);
        }

        return $result;
    }

    public function formula($start = null, $end = null, $scope = null) {
        if ($start == null || $end == null || $scope == null) {
            for ($i = 0; $i <= 11; $i++) {
                $result[$i] = 0;
            }
            return $result;
        }
        $curMonth = date('Y-m-01', time()); //timestemp
        $lastMonth = date('Y-m-01', strtotime('+1 year', strtotime($curMonth)));

        $curMonthTotal = $this->getTotalMonth($curMonth);
        if (is_object($start)) {
            $start = $start->format('Y-m-d');
        }
        if (is_object($end)) {
            $end = $end->format('Y-m-d');
        }
        $startMonthTotal = $this->getTotalMonth($start);
        $endMonthTotal = $this->getTotalMonth($end);

        $gabDate = ceil((strtotime($end) - strtotime($start)) / 86400);
        if ($gabDate == 0) {
            $per = $scope;
        } else {
            $per = $scope / $gabDate;
        }

        $startLeft = $this->getLeftDate($start);
        $endLeft = (int) date('d', strtotime($end));

        $monthArr = $this->getTwelveMonth();
        $count = 0;
        if ((strtotime($end) < strtotime($curMonth)) || $scope == '' || strtotime($start) > strtotime($lastMonth)) {
            for ($i = 0; $i <= 11; $i++) {
                $result[$i] = 0;
            }
            return $result;
        }
        foreach ($monthArr as $key => $mr) {
            $i = $curMonthTotal + $key;
            if ($i == $startMonthTotal) {
                if ($startMonthTotal == $endMonthTotal)
                    $result[$key] = $scope;
                else
                    $result[$key] = round($per * $startLeft);
            } elseif ($i == $endMonthTotal) {
                $result[$key] = round($per * $endLeft);
            } elseif ($i < $startMonthTotal) {
                $result[$key] = 0;
            } elseif ($i > $startMonthTotal && $i < $endMonthTotal) {
                $result[$key] = round($per * date('t', strtotime('+' . $count . 'month', strtotime($curMonth))));
            } else {
                $result[$key] = 0;
            }
            $count++;
        }
        return $result;
    }

    public function getLeftDate($date) {
        //month total date
        $t = date('t', strtotime($date));
        //cur date
        $d = date('d', strtotime($date));

        $result = $t - $d;
        return $result;
    }

    public function getTotalMonth($date) {
        $dateArr = explode('-', $date);
        $year = $dateArr[0];
        $month = $dateArr[1];
        $result = $year * 12 + $month;

        return $result;
    }

    protected function getForecastTasks() {

        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();

        $qb->select('t, p')
                ->from('AlbatrossAceBundle:Task', 't')
                ->leftJoin('t.project', 'p')
                ->where('t.number BETWEEN :a AND :b OR t.number = :d')
                ->andWhere('t.resume LIKE :resumea OR t.resume LIKE :resumec')
                ->orderBy('p.id')
        ;
        $qb->setParameters(array(
            'a' => '101',
            'b' => '116',
            'd' => '600',
            'resumea' => '%field%',
            'resumec' => '%delivered%'
        ));

        $query = $qb->getQuery();
        $result = $query->getArrayResult();
        return $result;
    }

    public function savepmeditAction() {
        $referer = $this->getRequest()->headers->get('referer');
        $forecastsearchForm = $this->createForm(new ForecastType());
        $forecastsearchForm->bind($this->getRequest());
        $data = $forecastsearchForm->getData();

        $secu = $this->container->get('security.context');
        $user = $secu->getToken()->getUser();
        $editor = $user->getId();
        $editTime = date('Y-m-d H:i:s');

        $em = $this->getDoctrine()->getManager();
        $task = $em->getRepository('AlbatrossAceBundle:Task')->findOneById($data['task']);

        $forecast = new Forecast();
        $forecast->setUser($data['pm']);
        $forecast->setEdittime(new \DateTime($editTime));
        $forecast->setFwstartdate(new \DateTime($data['fwstartdate']));
        $forecast->setFwenddate(new \DateTime($data['fwenddate']));
        $forecast->setEditor($editor);
        if($data['reporttype'] == 1){
            $forecast->setReporttype(1);
            $forecast->setReportduetext($data['reportduetext']);
            $em = $this->saveDelieveryDateToWaveFromForecast($task, $em, 1, $data['reportduetext']);
        }else{
            $forecast->setReporttype(0);
            $forecast->setReportduedate(new \DateTime($data['reportduedate']));
        }
        $forecast->setScope($data['scope']);
        $forecast->setTask($task);

        $em->persist($forecast);
        $em->flush();
        return $this->redirect($referer);
    }
    protected function saveDelieveryDateToWaveFromForecast($task, $em, $deliveryType, $deliveryDate) {
        $project = $task->getProject();
        $waveEntity = $project->getCustomwave();
        if(is_object($waveEntity)) {
            if($waveEntity->getWaveStep() == 'PM' || $waveEntity->getWaveStep() == 'ACE' || $waveEntity->getWaveStep() == ''){
                if(!$deliveryType){
//                    $waveEntity->setDeliveryDate($deliveryDate);
//                    $waveEntity->setWaveStep('PM');
//                } else {
                    if($waveEntity->getWaveStep() == 'ACE' || (strtotime($waveEntity->getDeliveryDate()) < strtotime($deliveryDate) || $waveEntity->getDeliveryDate() == '-' || !$waveEntity->getDeliveryDate())){
                        $waveEntity->setDeliveryDate($deliveryDate);
                        $waveEntity->setWaveStep('PM');
                    }
                }
                $em->persist($waveEntity);
            }
        }
        return $em;
    }
    
    protected function getIofTasks() {

        $em = $this->getDoctrine()->getManager();
        $attachInfo = $em->createQueryBuilder();
        $attachInfo->select('a, p, b, d, u')
                ->from('AlbatrossAceBundle:Attachinfo', 'a')
                ->leftJoin('a.project', 'p')
                ->leftJoin('a.bu', 'b')
                ->leftJoin('a.attachments', 'd')
                ->leftJoin('d.user', 'u')
                ->where('a.bu is not null')
                ->andWhere('d.children = 0')
                ->orderBy('a.id')
        ;
        $query = $attachInfo->getQuery();
        $result = $query->getArrayResult();
        $refer = array();
        foreach ($result as $key => $re) {
            $refer[$re['project']['id']][$re['bu']['number']] = $key;
        }
        $return['result'] = $result;
        $return['refer'] = $refer;

        return $return;
    }

    protected function getPmEditList() {
        $em = $this->getDoctrine()->getManager();
        $forecast = $em->createQueryBuilder();
        $forecast->select('f, t, u')
                ->from('AlbatrossAceBundle:Forecast', 'f')
                ->leftJoin('f.task', 't')
                ->leftJoin('f.user', 'u')
                ->orderBy('f.id')
        ;
        $query = $forecast->getQuery();
        $result = $query->getArrayResult();
        $refer = array();
        foreach ($result as $key => $re) {
            $refer[$re['task']['id']] = $key;
        }
        $return['result'] = $result;
        $return['refer'] = $refer;

        return $return;
    }

    protected function filterConditions($result, $src_client, $src_bu, $src_user, $src_proj, 
            $src_contract, $src_step, $src_scope_f, 
            $src_scope_t, $src_fw_s_f, $src_fw_s_t, 
            $src_fw_e_f, $src_fw_e_t, $src_due_f, 
            $src_due_t, $src_scope_month, $src_scope_All, 
            $src_update_f, $src_update_t) {

        if ($src_bu == null) {
            $src_bu = '';
        } else {
            $src_bu = $src_bu->getCode();
        }
        
        $stepArr = array(0 => 'Contract', 1 => 'PM', 2 => 'IOF', 3 => 'exception');
        if (empty($src_step)) {
            $src_step = array(0, 1, 2);
        } else {
            for($i = 0; $i < 3; $i++){
                if(!isset($src_step[$i]))
                    $src_step[$i] = 3;
            }
        }
        $userArr = $src_user->toArray();
        $userArrSearch = array();
        if (empty($userArr)) {
            $src_user = '';
        } else {
            foreach($userArr as $u){
                $userArrSearch[] = $u->getId();
            }
        }
        if (empty($src_scope_month)) {
            $src_scope_month = '';
        }
        $final = array();
        foreach ($result as $key => $re) {
            foreach ($re as $kbu => $r) {
                if (!is_string($r) && !is_integer($r) && !empty($r)) {
                    foreach ($r as $k => $in) {
                        if (array_key_exists('pm', $in)) {
                            if (($src_client == null || stripos($in['name'], $src_client) !== false) &&
                                    ($src_bu == '' || $in['bu'] == $src_bu) &&
                                    (empty($userArrSearch) || in_array($in['pmid'], $userArrSearch)) &&
                                    ($src_proj == null || stripos($in['name'], $src_proj) !== false) &&
                                    ($src_contract == null || stripos($in['contract'], $src_contract) !== false) &&
                                    ($in['step'] == $stepArr[$src_step[0]] || $in['step'] == $stepArr[$src_step[1]] || $in['step'] == $stepArr[$src_step[2]]) &&
                                    ($src_scope_f == null || $in['scope'] > $src_scope_f) &&
                                    ($src_scope_t == null || $in['scope'] < $src_scope_t) &&
                                    ($src_fw_s_f == null || strtotime($in['fws']) > strtotime($src_fw_s_f)) &&
                                    ($src_fw_s_t == null || strtotime($in['fws']) < strtotime($src_fw_s_t)) &&
                                    ($src_fw_e_f == null || strtotime($in['fwe']) > strtotime($src_fw_e_f)) &&
                                    ($src_fw_e_t == null || strtotime($in['fwe']) < strtotime($src_fw_e_t)) &&
                                    ($src_update_f == null || strtotime($in['update']) >= strtotime($src_update_f)) &&
                                    ($src_update_t == null || strtotime($in['update']) <= strtotime($src_update_t)) &&
                                    ($src_due_f == null || strtotime($re['All']) > strtotime($src_due_f)) &&
                                    ($src_due_t == null || strtotime($re['All']) < strtotime($src_due_t)) &&
                                    ($src_scope_month == '' || $this->checkMonthScope($src_scope_month, $in['forecast'])) &&
                                    (($src_scope_All == false && $this->checkYearScope($in['forecast'])) || $src_scope_All == true)
                            ) {
                                $final[$key]['bu'][$k] = $in;
                                $final[$key]['pm'] = $re['pm'];
                                $final[$key]['All'] = $re['All'];
                                $final[$key]['pmid'] = $re['pmid'];
                            }
                        } else {
                            if (($src_client == null || stripos($in['name'], $src_client) !== false) &&
                                    ($src_bu == '' || $in['bu'] == $src_bu) &&
                                    (empty($userArrSearch) || in_array($re['pmid'], $userArrSearch)) &&
                                    ($src_proj == null || stripos($in['name'], $src_proj) !== false) &&
                                    ($src_contract == null || stripos($in['contract'], $src_contract) !== false) &&
                                    ($in['step'] == $stepArr[$src_step[0]] || $in['step'] == $stepArr[$src_step[1]] || $in['step'] == $stepArr[$src_step[2]]) &&
                                    ($src_scope_f == null || $in['scope'] > $src_scope_f) &&
                                    ($src_scope_t == null || $in['scope'] < $src_scope_t) &&
                                    ($src_fw_s_f == null || strtotime($in['fws']) > strtotime($src_fw_s_f)) &&
                                    ($src_fw_s_t == null || strtotime($in['fws']) < strtotime($src_fw_s_t)) &&
                                    ($src_fw_e_f == null || strtotime($in['fwe']) > strtotime($src_fw_e_f)) &&
                                    ($src_fw_e_t == null || strtotime($in['fwe']) < strtotime($src_fw_e_t)) &&
                                    ($src_update_f == null || strtotime($in['update']) >= strtotime($src_update_f)) &&
                                    ($src_update_t == null || strtotime($in['update']) <= strtotime($src_update_t)) &&
                                    ($src_due_f == null || strtotime($re['All']) > strtotime($src_due_f)) &&
                                    ($src_due_t == null || strtotime($re['All']) < strtotime($src_due_t)) &&
                                    ($src_scope_month == '' || $this->checkMonthScope($src_scope_month, $in['forecast'])) &&
                                    (($src_scope_All == false && $this->checkYearScope($in['forecast'])) || $src_scope_All == true)
                            ) {
                                $final[$key]['bu'][$k] = $in;
                                $final[$key]['pm'] = $re['pm'];
                                $final[$key]['All'] = $re['All'];
                                $final[$key]['pmid'] = $re['pmid'];
                            }
                        }
                    }
                }
            }
        }

        return $final;
    }

    protected function userNameMatch() { // acename=>platform name
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('AlbatrossUserBundle:User')->findAll();

        $result = array();
        foreach ($users as $u) {
            $result[$u->getAceUsername()]['name'] = $u->getUsername();
            $result[$u->getAceUsername()]['id'] = $u->getId();
        }

        return $result;
    }

    protected function checkMonthScope($selectMonth, $scope) {
        foreach ($selectMonth as $k => $sm) {
            if ($scope[$sm] != 0) {
                return true;
            }
        }
        return false;
    }

    protected function checkYearScope($scope) {
        foreach ($scope as $s) {
            if ($s > 0) {
                return true;
            }
        }
        return false;
    }

    public function editFileListLabelAction() {
        $re = $this->getRequest()->getContent();
        $data = explode(':', $re);

        $em = $this->getDoctrine()->getManager();
        $en = $em->getRepository('AlbatrossAceBundle:Attachments')->find($data[0]);

        $en->setLabel($data[1]);
        $em->persist($en);
        $em->flush();

        return new Response(1);
    }

    public function getspecificattachinfoAction() {
        $id = $this->getRequest()->getContent();

        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('i, b, p')
                ->from('AlbatrossAceBundle:Attachinfo', 'i')
                ->leftJoin('i.attachments', 'a')
                ->leftJoin('i.bu', 'b')
                ->leftJoin('i.project', 'p')
                ->where('a.id = :id')
                ->orderBy('i.id')
        ;
        $qb->setParameters(
                array('id' => $id)
        );
        $query = $qb->getQuery();
        $result = $query->getArrayResult();
        $final = '';
        foreach ($result as $re) {
            if (is_object($re['fwstartdate']))
                $startDate = $re['fwstartdate']->format('Y-m-d');
            else
                $startDate = '';
            if (is_object($re['fwenddate']))
                $endDate = $re['fwenddate']->format('Y-m-d');
            else
                $endDate = '';
            if (is_object($re['reportduedate']))
                $dueDate = $re['reportduedate']->format('Y-m-d');
            else
                $dueDate = '';
            $final .= $re['scope'] . ':'
                    . $startDate . ':'
                    . $endDate . ':'
                    . $dueDate . ':'
                    . $re['comment'] . ':'
                    . $re['bu']['id'] . ':'
                    . $re['project']['id'] . ',';
        }
        $final = trim($final, ',');
        return new Response($final);
    }

    public function saveForecastScope($scope, $fws, $fwe, $gabDate, $bu, $scopeNum, $projectName) {
        $startLeft = $this->getLeftDate($fws);
        $endLeft = (int) date('d', strtotime($fwe));
        if ($gabDate == 0) {
            $per = $scope;
        } else {
            $per = $scope / $gabDate;
        }
        $startManth = date('Y-m', strtotime($fws));
        $endMonth = date('Y-m', strtotime($fwe));
        if(!preg_match('/^CI-.*|^.*-CI-.*/', $projectName)){
            for ($i = $startManth; strtotime($i) <= strtotime($endMonth); $i = date('Y-m', strtotime('+1 month', strtotime($i)))) {

                $m = date('Y-m', strtotime($i));

                if (isset($scopeNum[$bu][$m])) {
                    if (strtotime($i) == strtotime($startManth)) {
                        if (strtotime($startManth) == strtotime($endMonth))
                            $scopeNum[$bu][$m] = $scopeNum[$bu][$m] + $scope;
                        else
                            $scopeNum[$bu][$m] = $scopeNum[$bu][$m] + round($per * $startLeft);
                    } elseif (strtotime($i) == strtotime($endMonth)) {
                        $scopeNum[$bu][$m] = $scopeNum[$bu][$m] + round($per * $endLeft);
                    } elseif (strtotime($i) > strtotime($startManth) && strtotime($i) < strtotime($endMonth)) {
                        $scopeNum[$bu][$m] = $scopeNum[$bu][$m] + round($per * date('t', strtotime($m)));
                    }
                } else {
                    if (strtotime($i) == strtotime($startManth)) {
                        if (strtotime($startManth) == strtotime($endMonth))
                            $scopeNum[$bu][$m] = (int) $scope;
                        else
                            $scopeNum[$bu][$m] = round($per * $startLeft);
                    } elseif (strtotime($i) == strtotime($endMonth)) {
                        $scopeNum[$bu][$m] = round($per * $endLeft);
                    } elseif (strtotime($i) > strtotime($startManth) && strtotime($i) < strtotime($endMonth)) {
                        $scopeNum[$bu][$m] = round($per * date('t', strtotime($m)));
                    }
                }
            }
        }
        return $scopeNum;
    }

    protected function getPmPostion() {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AlbatrossUserBundle:User')->findAll();
        $result = array();
        foreach ($entities as $e) {
            if ($e->getPosition() != null) {
                $result[$e->getId()] = $e->getPosition()->getId();
            } else {
                $result[$e->getId()] = '';
            }
        }
        return $result;
    }

    public function attachmentDeleteAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AlbatrossAceBundle:Attachments')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Customproject entity.');
        }
        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('ioflist'));
    }
    
    //Refresh survey function
    ////read todays file, and get monthes from file name.
    ////read files and make surveyid as array.
    ////get survey data from database which belong to these monthes.
    ////check in array or remove.
    public function refreshSurveysAction(){
        $dir = 'aolExport/';
        $date = date('ymd');
        $targetDir = $dir . $date;
        
        //init Values
        $monthArrFromFile = array();
        $surveyArrFromFile = array();
        if (is_dir($targetDir)) {
            $files = scandir($targetDir);
            foreach ($files as $f) {
                //have extension and extension must be csv.
                if (pathinfo($targetDir . $f, PATHINFO_EXTENSION) && pathinfo($targetDir . $f, PATHINFO_EXTENSION) == 'csv') {
                    $monthAndExtentionArr = explode(' ', $f);
                    $monthArr = explode('.', $monthAndExtentionArr[2]);
                    $monthArrFromFile[] = date('Y-m', strtotime($monthAndExtentionArr[1] . $monthArr[0]));
                    $surveyIdArr = $this->CurdayAolFileSurveyIdArr($targetDir .'/'. $f);
                    $surveyArrFromFile = array_merge($surveyArrFromFile, $surveyIdArr);
                }
            }
        }else{
            $referer = $this->getRequest()->headers->get('referer');
            return new Response('<script>alert("Please upload file first");location.href = "'.$referer.'"</script>');
        }
        $this->getSurveyFromDB($monthArrFromFile, $surveyArrFromFile);
        
        $this->deleteQuestionnaireAndCampaign();
        return $this->redirect($this->generateUrl('project'));
    }
    protected function getSurveyFromDB($monthArrFromFile, $surveyArrFromFile){
        $em = $this->getDoctrine()->getManager();
        $surveyQuery = $em->createQuery('SELECT s.id, s.SurveyInstanceID, s.SurveyInstanceID, s.Date, q.name FROM AlbatrossAceBundle:Aolsurvey s LEFT JOIN s.campaign cam LEFT JOIN cam.questionnaire q');
        $surveyEntities = $surveyQuery->getResult();
        $haveToDelete = array();
        $monthArrFromFileFlip = array_flip($monthArrFromFile);
        $surveyArrFromFileFlip = array_flip($surveyArrFromFile);
        $index = 0;
        foreach($surveyEntities as $survey){
            if(isset($monthArrFromFileFlip[date('Y-m', strtotime($survey['Date']))])){
                if(!isset($surveyArrFromFileFlip[trim($survey['SurveyInstanceID'])])){
                    $haveToDelete[$index]['id'] = $survey['id'];
                    $haveToDelete[$index]['surveyid'] = $survey['SurveyInstanceID'];
                    $haveToDelete[$index]['survey'] = $survey['name'];
                    $haveToDelete[$index]['date'] = $survey['Date'];
                    $index++;
                }
            }
        }
        foreach($haveToDelete as $hd){
            $entity = $em->getRepository('AlbatrossAceBundle:Aolsurvey')->find($hd['id']);
            $em->remove($entity);
        }
        $this->writeSurveyDeleteLog($haveToDelete);
        $em->flush();
        return;
    }
    protected function deleteQuestionnaireAndCampaign(){
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('questionnaire', 'campaign')
                ->from('AlbatrossAceBundle:Questionnaire', 'questionnaire')
                ->leftJoin('questionnaire.campaign', 'campaign')
                ->leftJoin('campaign.aolsurvey', 'survey')
                ->where('survey.id is not null');
        $query = $qb->getQuery();
        $result = $query->getResult();
        
        $haveSurveyArr = array();
        
        foreach($result as $re){
            $haveSurveyArr[] = $re->getId();
        }

        $qb2 = $em->createQueryBuilder();
        $qb2->select('q')
                ->from('AlbatrossAceBundle:Questionnaire', 'q')
                ->where('q.id NOT IN (:qid)')
                ->setParameter('qid', $haveSurveyArr);
        $query2 = $qb2->getQuery();
        $result2 = $query2->getResult();
        
        foreach($result2 as $re2){
            $em->remove($re2);
        }
        
        $em->flush();
        return;
    }
    protected function writeSurveyDeleteLog($data){
        $date = date('Ymd');
        if(!is_dir('deletedSurveyLog')){
            mkdir('deletedSurveyLog');
        }
        $file = fopen("deletedSurveyLog/" . $date . ".txt", "a+");

        foreach ($data as $surveyData) {
            fwrite($file,'<tr><td>'. $surveyData['surveyid'].'</td><td>'.$surveyData['survey']. '</td><td>'.$surveyData['date'].'</td></tr>'."\n");
        }
        fclose($file);
        return;
    }
    public function showDeletedSurveysAction(){
        $date = date('Ymd');
        $file_handle = fopen('deletedSurveyLog/'.$date.'.txt', "r");
        
        $result = '<table id="deleted-survey-table"><tr><th colspan="3" id="deleted-survey-table-title">DELETED SURVERYS'.
                '<img id="closeShowDeletedSurvey" onclick="closeSurveyShow();" src="/images/close.png"></th></tr>'.
                '<tr id="deleted-survey-table-subtitle"><th>Survey ID</th><th>Survey Name</th><th>Date</th></tr>';
        while (!feof($file_handle)) {
           $line = fgets($file_handle);
           if($line != ''){
                $result .= $line;
           }
        }
        fclose($file_handle);
        return new Response($result.'</table>');
    }
    protected function CurdayAolFileSurveyIdArr($fileDir){
        $csv = new SplFileObject($fileDir);
        $excelArr = array();
        $result = array();
        while (!$csv->eof()) {
            $excelArr[] = $csv->fgetcsv();
        }

        $sid = 0;

        foreach ($excelArr[0] as $k => $name) {
            if($name == 'SurveyInstanceID'){
                $sid = $k;
                break;
            }
        }

        unset($excelArr[0]);

        foreach ($excelArr as $earr) {
            if (isset($earr[$sid])) {
                $result[] = $earr[$sid];
            }
        }
        return $result;
    }
//    searchIofFile backup
//    public function searchIofFile($src_client, $src_status, $src_bu, $src_user, $src_proj, $src_number, $src_t_f, $src_t_t) {
//        $em = $this->getDoctrine()->getManager();
//        $qb = $em->createQueryBuilder();
//        switch ($src_status) {
//            case 0:
//                $src_status = '';
//                break;
//            case 1:
//                $src_status = '0';
//                break;
//            case 2:
//                $src_status = '1';
//                break;
//            case 3:
//                $src_status = '2';
//                break;
//        }
//        if ($src_bu == null) {
//            $src_bu = '';
//        } else {
//            $src_bu = $src_bu->getId();
//        }
//        if ($src_t_f != '') {
//            $src_t_f = new \DateTime($src_t_f);
//        }
//        if ($src_t_t != '') {
//            $src_t_t = new \DateTime($src_t_t);
//        }
//        if (($src_client != '' || $src_status != '' || $src_bu != '' || $src_user != '' || $src_proj != '' || $src_number != '' || $src_t_f != '' || $src_t_t != '')) {
//            $qb->select('f, pa, u, w, cp, c')
//                    ->from('AlbatrossAceBundle:Attachments', 'f')
//                    ->leftjoin('f.attachinfo', 'i')
//                    ->leftjoin('f.user', 'u')
//                    ->leftjoin('i.project', 'p')
//                    ->leftjoin('i.bu', 'b')
//                    ->leftjoin('f.parent', 'pa')
//                    ->leftjoin('f.customwave', 'w')
//                    ->leftjoin('w.customproject', 'cp')
//                    ->leftjoin('cp.customclient', 'c')
//                    ->where('f.type = 0')
//                    ->andWhere('f.children = 0');
//            if ($src_client != '')
//                $qb->andWhere('f.label LIKE :client');
//            if ($src_status != '')
//                $qb->andWhere('f.status = :status');
//            if ($src_bu != '')
//                $qb->andWhere('b.id = :bu');
//            if ($src_user != '')
//                $qb->andWhere('u.id = :user');
//            if ($src_proj != '')
//                $qb->andWhere('p.name LIKE :project');
////        if ($src_number != '')
////            $qb->andWhere('p.number LIKE :project');
//            if ($src_t_f != '' && $src_t_t == '')
//                $qb->andWhere('f.submitteddate BETWEEN :src_t_f AND :today');
//            if ($src_t_t != '' && $src_t_f == '')
//                $qb->andWhere('f.submitteddate BETWEEN :last AND :src_t_t');
//            if ($src_t_t != '' && $src_t_f != '')
//                $qb->andWhere('f.submitteddate BETWEEN :src_t_f AND :src_t_t');
////setParameters
//            $parameter = array();
//            if ($src_client != '') {
//                $parameter = array_merge($parameter, array(
//                    'client' => '%' . $src_client . '%'
//                ));
//            }
//            if ($src_status != '') {
//                $parameter = array_merge($parameter, array(
//                    'status' => $src_status
//                ));
//            }
//            if ($src_bu != '') {
//                $parameter = array_merge($parameter, array(
//                    'bu' => $src_bu
//                ));
//            }
//            if ($src_user != '') {
//                $parameter = array_merge($parameter, array(
//                    'user' => $src_user->getId()
//                ));
//            }
//            if ($src_proj != '') {
//                $parameter = array_merge($parameter, array(
//                    'project' => '%' . $src_proj . '%'
//                ));
//            }
////        if ($src_number != '')
//            if ($src_t_f != '' && $src_t_t == '') {
//                $parameter = array_merge($parameter, array(
//                    'src_t_f' => $src_t_f,
//                    'today' => date('Y-m-d')
//                ));
//            }
//            if ($src_t_t != '' && $src_t_f == '') {
//                $parameter = array_merge($parameter, array(
//                    'last' => new \DateTime('2013-01-01'),
//                    'rc_t_f' => $src_t_f
//                ));
//            }
//            if ($src_t_t != '' && $src_t_f != '') {
//                $parameter = array_merge($parameter, array(
//                    'src_t_f' => $src_t_f,
//                    'src_t_t' => $src_t_t
//                ));
//            }
//            $qb->setParameters($parameter);
//        } else {
//            $qb->select('f, pa, u, w, p, c')
//                    ->from('AlbatrossAceBundle:Attachments', 'f')
//                    ->leftjoin('f.parent', 'pa')
//                    ->leftjoin('f.user', 'u')
//                    ->leftjoin('f.customwave', 'w')
//                    ->leftjoin('w.customproject', 'p')
//                    ->leftjoin('p.customclient', 'c')
//                    ->where('f.type = 0')
//                    ->andWhere('f.children = 0')
//                    ->orderBy('f.id');
//        }
//
//        $query = $qb->getQuery();
//
//        $result = $query->getArrayResult();
//        $check = array();
//        foreach ($result as $key => $re) {
//            if ($re['parent'] != null) {
//                if (isset($check[$re['parent']['id']])) {
//                    unset($result[$check[$re['parent']['id']]]);
//                    $check[$re['parent']['id']] = $key;
//                } else {
//                    $check[$re['parent']['id']] = $key;
//                }
//            }
//        }
//
//        $paginator = $this->get('knp_paginator');
//        $pagination = $paginator->paginate(
//                $result, $this->get('request')->query->get('page', 1), 20
//        ); /* page number */
//
//        return $pagination;
//    }
}
