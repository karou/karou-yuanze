<?php 

namespace Albatross\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Albatross\UserBundle\Entity\Comment;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller {

    public function indexAction($current) {
        $em = $this->getDoctrine()->getManager();
        $questionnaireQB = $em->createQueryBuilder();
        $questionnaireQB->select('q', 'w', 'p')
                ->from('AlbatrossCustomBundle:Customfield', 'q')
                ->leftJoin('q.customwave', 'w')
                ->leftJoin('w.customproject', 'p')
                ->where('q.fieldtype = :questionnaire');
        $questionnaireQB->setParameters(array(
            'questionnaire' => 'questionnaire'
        ));
        $questionnaireQuery = $questionnaireQB->getQuery();
        $questionnaireEntity = $questionnaireQuery->getArrayResult();

        for ($i = 1; $i < 10; $i++) {
            $questionnaireCount[$i] = 0;
        }
        foreach ($questionnaireEntity as $q) {
            if ($q['question_status'] < 10)
                $questionnaireCount[$q['question_status']]++;
        }

        //
        //ace project the do not belong to any customwave
        //
        $aceProjectQb = $em->createQueryBuilder();
        $aceProjectQb->select('p')
                ->from('AlbatrossAceBundle:Project', 'p')
                ->where('p.customwave is null');
        $aceProjectQuery = $aceProjectQb->getQuery();
        $aceProjectEntity = $aceProjectQuery->getArrayResult();
        $aceNum = count($aceProjectEntity);

        //
        //customwaves that do not bind with the ace project
        //
        $customwaveQb = $em->createQueryBuilder();
        $customwaveQb->select('w', 'p', 'cp')
                ->from('AlbatrossCustomBundle:Customwave', 'w')
                ->leftJoin('w.project', 'p')
                ->leftJoin('w.customproject', 'cp')
                ->where('p.customwave is null')
                ->andWhere('w.name not like :test');
        $customwaveQb->setParameter('test', 'Test%');
        $customwaveQuery = $customwaveQb->getQuery();
        $customwaveEntity = $customwaveQuery->getArrayResult();
        $customwaveWithoutAceResultCount = count($customwaveEntity);

        $surveyResult = $this->getHomepageQuestionnaireInfo($em);
        //
        //customwave the do not bind any survey
        //
        $customwaveQbWave = $em->createQueryBuilder();
        $customwaveQbWave->select('w', 'q', 'cp')
                ->from('AlbatrossCustomBundle:Customwave', 'w')
                ->leftJoin('w.questionnaire', 'q')
                ->leftJoin('q.customwave', 'cw')
                ->leftJoin('w.customproject', 'cp')
                ->where('cw.id is null')
                ->andWhere('w.name not like :test');
        $customwaveQbWave->setParameter('test', 'Test%');
        $customwaveQueryWave = $customwaveQbWave->getQuery();
        $customwaveEntityWave = $customwaveQueryWave->getArrayResult();
        $customwaveWithoutAceResultWaveCount = count($customwaveEntityWave);

        //  
        //iof do not belong to any wave
        //
        $iof_qb = $em->createQueryBuilder();
        $iof_qb->select('a')
                ->from('AlbatrossAceBundle:Attachments', 'a')
                ->where('a.customwave is null')
                ->andWhere('a.children = 0')
                ->andWhere('a.parent is null')
                ->andWhere('a.type = 0');
        $iof_query = $iof_qb->getQuery();
        $iof_entity = $iof_query->getArrayResult();
        $iof_entity_count = count($iof_entity);

        //
        //user access
        //
        $secu = $this->container->get('security.context');
        $user = $secu->getToken()->getUser();

        //
        //client and guest can not see the abstract information
        //
        $role = $secu->isGranted('ROLE_TYPE_USER') ? 1 : 0;
        $admin = $secu->isGranted('ROLE_ADMIN') ? : 0;


        $currMonthFrom = date('Y-m', mktime(date("H"), date("i"), date("s"), date("m") - 1, date("d"), date("Y") - 1));
        $currMonthTo = date('Y-m', mktime(date("H"), date("i"), date("s"), date("m") - 1, date("d"), date("Y")));

        $prevMonthFrom = date('Y-m', mktime(date("H"), date("i"), date("s"), date("m") - 1, date("d"), date("Y") - 2));
        $prevMonthTo = date('Y-m', mktime(date("H"), date("i"), date("s"), date("m") - 1, date("d"), date("Y") - 1));

        /////for getting forcast graph data////////
        $forecast1 = $this->getForcastForYear($currMonthFrom, $currMonthTo, 0);
        $forecast2 = $this->getForcastForYear($prevMonthFrom, $prevMonthTo, 0);

        /////for getting data for surveys//////////
        $assSurveys = $this->getSurveys('assigned');
        $subSurveys = $this->getSurveys('submitted');
        $valSurveys = $this->getSurveys('validated');
        $penSurveys = $this->getSurveys('pendingValidation');

        //////////get today's forecast % //////////////
        $todayForecastVal = $this->getForecastPer('today', 0);
        $todayPrevForecastVal = $this->getForecastPer('previous', 0);

        //////////get AOL Pending Questionnaire data//////////////
        $pendingQuesData = $this->getPendingQuesData();

        return $this->render('AlbatrossUserBundle:Default:index.html.twig', array(
                    'current' => $current,
                    'questionnarireCount' => $questionnaireCount,
                    'questionnaireEntity' => $questionnaireEntity,
                    'acenum' => $aceNum,
                    'aceProjectEntity' => $aceProjectEntity,
                    'waveWithoutAce' => $customwaveEntity,
                    'waveWithoutAceCount' => $customwaveWithoutAceResultCount,
                    'surveyEntity' => $surveyResult,
                    'waveWithoutSurvey' => $customwaveEntityWave,
                    'waveWithoutSurveyCount' => $customwaveWithoutAceResultWaveCount,
                    'role' => $role,
                    'admin' => $admin,
                    'iof_entity_count' => $iof_entity_count,
                    'iof_entity' => $iof_entity,
                    'forecast1' => $forecast1,
                    'forecast2' => $forecast2,
                    'currMonthFrom' => $currMonthFrom,
                    'currMonthTo' => $currMonthTo,
                    'assSurveys' => $assSurveys,
                    'subSurveys' => $subSurveys,
                    'valSurveys' => $valSurveys,
                    'penSurveys' => $penSurveys,
                    'todayForecastVal' => $todayForecastVal,
                    'todayPrevForecastVal' => $todayPrevForecastVal,
                    'pendingQuesData' => $pendingQuesData
        ));
    }

    function getPendingQuesData() 
	{
        $em = $this->getDoctrine()->getManager();

        $penqb = $em->createQueryBuilder();
        $penqb->select('c,u,w')
                ->from('AlbatrossCustomBundle:Customfield', 'c')
                ->leftJoin('c.user', 'u')
                ->leftJoin('c.customwave', 'w')
                ->Where('c.fieldtype = :ftype')
                ->setParameter('ftype', "questionnaire")
                ->andWhere('c.user = :user_id')
                ->setParameter('user_id', $this->getUser()->getId());

        $penquery = $penqb->getQuery();
        $penarr = $penquery->getArrayResult();

        $arrTableData = array();
        if (is_array($penarr) and count($penarr) > 0) {
            $x = 1;
            foreach ($penarr as $pen) {
                $questionStatus = '';
                if (@$pen['questionStatus'] == 1)
                    $questionStatus = '<span class="status blue">Client</span>';
                else if (@$pen['questionStatus'] == 2)
                    $questionStatus = '<span class="status red">Project Manager</span>';
                else if (@$pen['questionStatus'] == 3)
                    $questionStatus = '<span class="status green">Proofreading</span>';
                else if (@$pen['questionStatus'] == 4)
                    $questionStatus = '<span class="status blue-light">Upload or Clonage</span>';
                else if (@$pen['questionStatus'] == 5)
                    $questionStatus = '<span class="status orange">Quality Control</span>';
                else if (@$pen['questionStatus'] == 6)
                    $questionStatus = '<span class="status blue1">Translation</span>';

                $fieldVal = $x;
                if ($x < 10)
                    $fieldVal = '0' . $x;

                $arrData = '';
                $arrData[] = $fieldVal;
                $arrData[] = '<a href="#">' . $pen['user']['fullname'] . '</a>';
                $arrData[] = '<b>' . $pen['customwave']['name'] . '</b> ' . $this->getMonthName($pen['customwave']['month']) . ' ' . $pen['customwave']['year'];
                $arrData[] = $pen['submittime'];
                $arrData[] = $questionStatus;

                $arrTableData[] = $arrData;
                $x++;
            }
        }
        return $arrTableData;
    }

    function getForecastPer($type, $bu_id) {
        $em = $this->getDoctrine()->getManager();

        $date = date('Y-m-d');
        if ($type == 'previous')
            $date = date('Y-m-d', mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y") - 1));

        $monthNow = date('Y-m');

        $dtqb = $em->createQueryBuilder();
        if ($bu_id > 0) {
            $dtqb->select('d')
                    ->from('AlbatrossDailyBundle:Date', 'd')
                    ->Where('d.dailydate = :dt')
                    ->setParameter('dt', "$date")
                    ->andWhere('d.bu = :buid')
                    ->setParameter('buid', "$bu_id");
        } else {
            $dtqb->select('d')
                    ->from('AlbatrossDailyBundle:Date', 'd')
                    ->Where('d.dailydate = :dt')
                    ->setParameter('dt', "$date");
        }
        $dtquery = $dtqb->getQuery();
        $dtNum = $dtquery->getArrayResult();

        $number = (isset($dtNum[0]['id'])) ? $dtNum[0]['id'] : 0;

        $monthqb = $em->createQueryBuilder();
        $monthqb->select('f')
                ->from('AlbatrossAceBundle:Forecastscope', 'f')
                ->Where('f.month = :monthNow')
                ->setParameter('monthNow', "$monthNow")
                ->andWhere('f.bu = :buid')
                ->setParameter('buid', "$bu_id");

        $monthquery = $monthqb->getQuery();
        $monthRec = $monthquery->getArrayResult();
        $forecast = (isset($monthRec[0]['forecast'])) ? $monthRec[0]['forecast'] : 0;

        if ($forecast <= 0)
            return 0;
        else
            return $perVal = round(($number / $forecast) * 100, 0);
    }

    private function getForcastForYear($monthFrom, $monthTo, $bu_id) {
        //$repository = $this->getDoctrine()->getRepository('MainBundle:Forecastscope');
        $em = $this->getDoctrine()->getManager();

        if ($bu_id > 0) {
            $query = $em->createQueryBuilder('f')
                    ->select('sum(f.forecast) as fcast,f.month')
                    ->from('AlbatrossAceBundle:Forecastscope', 'f')
                    ->Where('f.month >= :monthFrom')
                    ->setParameter('monthFrom', "$monthFrom")
                    ->andWhere('f.month <= :monthTo')
                    ->setParameter('monthTo', "$monthTo")
                    ->andWhere('f.bu = :buId')
                    ->setParameter('buId', "$bu_id")
                    ->groupBy('f.month')
                    ->orderBy('f.month', 'ASC')
                    ->getQuery();
        } else {
            $query = $em->createQueryBuilder('f')
                    ->select('sum(f.forecast) as fcast,f.month')
                    ->from('AlbatrossAceBundle:Forecastscope', 'f')
                    ->Where('f.month >= :monthFrom')
                    ->setParameter('monthFrom', "$monthFrom")
                    ->andWhere('f.month <= :monthTo')
                    ->setParameter('monthTo', "$monthTo")
                    ->groupBy('f.month')
                    ->orderBy('f.month', 'ASC')
                    ->getQuery();
        }
        $arr = $query->getArrayResult();

        $arrResult = array();

        for ($x = 0; $x <= 12; $x++) {
            if (isset($arr[$x])) {
                $arrResult[] = array(
                    'fcast' => $arr[$x]['fcast'],
                    'month' => strtotime($arr[$x]['month'] . '-01')
                );
            } else {
                $arrResult[] = array(
                    'fcast' => '',
                    'month' => ''
                );
            }
        }
        return $arrResult;
    }

    function getSurveys($type) {
        $em = $this->getDoctrine()->getManager();

        $arrayStats = '';
        if ($type == 'assigned') {
            $arrayStats = array("Assigned - Completed not yet submitted", "Assigned - In \"Working\" status", "Assigned - Returned Completely", "Assigned (Accepted where Acceptance is Required)");
        } else if ($type == 'submitted') {
            $arrayStats = array("Declined", "Open Opportunities - No Applications", "Open Opportunities - With Applications");
        } else if ($type == 'validated') {
            $arrayStats = array("Completed - Pending Export", "Completed - RFA(s) closed", "Completed - RFA(s) open", "Completed Exported", "Hide from Reports; Hide from Client Survey Explorer", "Hide from Reports; OK for Client Survey Explorer", "Completed - Export Failed");
        } else if ($type == 'pendingValidation') {
            $arrayStats = array("On Hold - General", "Validation - After Return", "Validation - In Progress", "Validation - Pending");
        }

        $query = $em->createQueryBuilder();
        $query->select('count(a.id) surveys')
                ->from('AlbatrossAceBundle:Aolsurvey', 'a')
                ->Where('a.SurveyStatusName in (:statnames)')
                ->setParameter('statnames', $arrayStats)
                ->groupBy('a.Client')
                ->orderBy('surveys', 'asc');

        $dtquery = $query->getQuery();
        return $dtquery->getArrayResult();
    }

    //
    //surveys the do not belong to any customwave
    //
    protected function getHomepageQuestionnaireInfo($em) 
	{
       /* $surveyQb = $em->createQueryBuilder();
        $surveyQb->select('q', 'c', 'a')
                ->from('AlbatrossAceBundle:Questionnaire', 'q')
                ->leftJoin('q.campaign', 'c')
                ->leftJoin('c.aolsurvey', 'a')
                ->leftJoin('c.customwave', 'cw')
                ->where('cw.id is null');
        $surveyQuery = $surveyQb->getQuery();
        $surveyEntity = $surveyQuery->getArrayResult();*/
		
		$connection = $em->getConnection();
			
		$sql = 'SELECT q.*, c.*, a.* FROM questionnaire q left join questionnaire_customwave qc on (q.id=qc.questionnaire_id) LEFT JOIN campaign c on (q.id=c.questionnaire_id) LEFT JOIN aolsurvey a on (c.id=a.campaign_id) LEFT JOIN customwave cw on (qc.customwave_id=cw.id) WHERE cw.id is null limit 0,100';
		
		$statement = $connection->prepare($sql);
		$statement->execute();
		$surveyEntity = $statement->fetchAll();
			
        $surveyResult = array();
        $surveyNum = 0;
        //filter text
        $filterWord = array(
            'Test',
            'Testing',
            'N/A');
        $monthArr = array(
            date('F'),
            date('F', strtotime('-1 month')),
            date('F', strtotime('-2 month')));
        $statusArr = array(
            'Completed - Pending Export',
            'Completed - RFA(s) closed',
            'Completed - RFA(s) open',
            'Completed Exported',
            'Hide from Reports; Hide from Client Survey Explorer',
            'Hide from Reports; OK for Client Survey Explorer',
            'Completed - Export Failed');
			
		foreach ($surveyEntity as $s) {
            if (stripos($s['name'], 'MISFIRE') === FALSE && stripos($s['name'], 'Action Plan') === FALSE && stripos($s['name'], 'INTERNAL') === FALSE) {
				$sql = 'SELECT * FROM  campaign WHERE questionnaire_id="'.$s['questionnaire_id'].'"';
		
				$statementCamp = $connection->prepare($sql);
				$statementCamp->execute();
				$campaign = $statementCamp->fetchAll();
				
                foreach ($campaign as $c) { // $s['campaign']
                    foreach ($monthArr as $m) {
                        if (stripos($c['name'], $m) && !in_array($c['name'], $filterWord) && stripos($c['name'], date('Y')) !== false) {
                            $check_status = 0;
							
							$sql = 'SELECT * FROM aolsurvey WHERE campaign_id="'.$c['id'].'"';
		
							$statementAol = $connection->prepare($sql);
							$statementAol->execute();
							$aolsurvey = $statementAol->fetchAll();
							
                            foreach ($aolsurvey as $a) {
                                if (!in_array($a['survey_status_name'], $statusArr)) { //$a['SurveyStatusName']
                                    $check_status = 1;
                                }
                            }
                            if ($check_status == 1) {
                                if (!isset($surveyResult[$s['name']][$c['name']])) {
                                    $surveyResult[$s['name']][$c['name']] = 'set';
                                    $surveyNum++;
                                }
                            }
                        }
                    }
                }
            }
        }
        $result = array('entity' => $surveyResult, 'count' => $surveyNum);
        return $result;
    }

    public function headerAction() {
        //judge if the user status, login or not.
        $secu = $this->container->get('security.context');
        $user = $secu->getToken()->getUser();

        //0: nothing 1:admin 2:user_add
        if ($secu->isGranted('ROLE_ADMIN'))
            $role_button = 1;
        elseif ($secu->isGranted('ROLE_USER_ADD'))
            $role_button = 2;
        else
            $role_button = 0;

        return $this->render('AlbatrossUserBundle:Default:header.html.twig', array(
                    'user' => $user,
                    'role_button' => $role_button,
        ));
    }

    public function linkAction() {
        $secu = $this->container->get('security.context');
        $user = $secu->getToken()->getUser();

        return $this->render('AlbatrossUserBundle:Default:link.html.twig', array(
                    'user' => $user,
        ));
    }

    public function footerAction() {
        return $this->render('AlbatrossUserBundle:Default:footer.html.twig');
    }

    public function navBigAction($current = '') {
        $secu = $this->container->get('security.context');
        $user = $secu->getToken()->getUser();
        $forecast_current = $custom_peoject = $custom_project_status = 'nav_big_cal';

        switch ($current) {
            case 'forecast':
                $forecast_current = 'nav_big_cal_cur';
                break;
            case 'custom_project':
                $custom_peoject = 'nav_big_cal_cur';
                break;
            case 'custom_project_status':
                $custom_project_status = 'nav_big_cal_cur';
                break;
            default :
                break;
        }
        $role_client = $secu->isGranted('ROLE_TYPE_CLIENT') ? 1 : 0;
        return $this->render('AlbatrossUserBundle:Default:nav_big.html.twig', array(
                    'forecast_current' => $forecast_current,
                    'custom_project_current' => $custom_peoject,
                    'custom_project_status_current' => $custom_project_status,
                    'role_client' => $role_client,
                    'user' => $user
        ));
    }

    public function navAction($current = '') {
        $secu = $this->container->get('security.context');
        $user = $secu->getToken()->getUser();

        $h_current = $s_current = $e_current = $d_current = $f_current = $i_current = $forecast_current = $custom_peoject = $custom_project_status_current = 'nav_cal';
        switch ($current) {
            case 'homepage':
                $h_current = 'nav_cal_cur';
                break;
            case 'project':
                $s_current = 'nav_cal_cur';
                break;
            case 'user':
                $e_current = 'nav_cal_cur';
                break;
            case 'daily':
                $d_current = 'nav_cal_cur';
                break;
            case 'filelist':
                $f_current = 'nav_cal_cur';
                break;
            case 'ioflist':
                $i_current = 'nav_cal_cur';
                break;
            case 'forecast':
                $forecast_current = 'nav_big_cal_cur';
                break;
            case 'custom_project':
                $custom_peoject = 'nav_cal_cur';
                break;
            case 'custom_project_status':
                $custom_project_status_current = 'nav_cal_cur';
                break;
            default :
                break;
        }

        $menu = array();
        if ($secu->isGranted('ROLE_ADMIN')) {
            $menu['user_info'] = array(
                'label' => 'User Information',
                'route' => '',
                'children' => array(
                    'show' => array(
                        'label' => 'Check User Information',
                        'route' => 'user_profile'
                    ),
                    'mui' => array(
                        'label' => 'Modify User Information',
                        'route' => 'user_edit'
                    )
                )
            );
        }
        $role_client = $secu->isGranted('ROLE_TYPE_CLIENT') ? 1 : 0;
        if ($secu->isGranted('ROLE_TYPE_USER') || $secu->isGranted('ROLE_TYPE_CLIENT')) {
            $role_guest = 0;
        } else {
            $role_guest = 1;
        }

        return $this->render('AlbatrossUserBundle:Default:nav.html.twig', array(
                    'user' => $user,
                    'menu' => $menu,
                    'h_current' => $h_current,
                    's_current' => $s_current,
                    'e_current' => $e_current,
                    'd_current' => $d_current,
                    'f_current' => $f_current,
                    'i_current' => $i_current,
                    'forecast_current' => $forecast_current,
                    'custom_project_current' => $custom_peoject,
                    'custom_project_status_current' => $custom_project_status_current,
                    'role_client' => $role_client,
                    'role_guest' => $role_guest
        ));
    }
	
    function getMonthName($val)
    {
            $arrayMonth = array('','January','February','March','April','May','June','July','August','September','Octuber','November','December');
            return $arrayMonth[$val];
    }

    public function saveUserCommentAction(){
        $request = $this->getRequest();
        $text = $request->get('text');
        $waveId = $request->get('waveid');
        
        if(!empty($text)) {
            $em = $this->getDoctrine()->getManager();
            $secu = $this->container->get('security.context');
            $user = $secu->getToken()->getUser();
            $curTime = date('Y-m-d H:i:s', time());
            $waveEntity = $em->getRepository('AlbatrossCustomBundle:Customwave')->find($waveId);
            $commentEntity = new Comment();
            $commentEntity->setContent($text);
            $commentEntity->setUser($user);
            $commentEntity->setWave($waveEntity);
            $commentEntity->setSubmittime(new \DateTime($curTime));
            
            $em->persist($commentEntity);
            $em->flush();
            
            $time = $commentEntity->getSubmittime()->format('Y-m-d H:i:s');
            $return = '<div class="item-block clearfix"><div class="item-thumb pull-left"><ul><li class="item-pic"><img src="/'
                    . $commentEntity->getUser()->getWebPath() .'" width="34" height="34" alt="anchor"></li>'
                    . '</ul></div><div class="item-intro pull-left" style="width:85%;"><p>'
                    . $commentEntity->getContent() .'</p><div class="item-meta"><ul><li>'
                    . $commentEntity->getUser()->getUsername() .'</li><li>Project: <a href="#">'
                    . $commentEntity->getWave()->getCustomproject()->getName() .'</a></li><li>'
                    . $time .'</li></ul></div></div></div>';
            return new Response($return);
        }
    }
}
