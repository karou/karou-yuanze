<?php

namespace Albatross\ForecastBundle\Controller;

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

class DefaultController extends Controller
{
    public function indexAction()
    {
		$current = $isrefresh = '';
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
        if ($src_client == null && empty($userArr) && $src_proj == null && $src_contract == null &&
                empty($src_step) && $src_scope_f == null && $src_scope_t == null && $src_fw_s_f == null &&
                $src_fw_s_t == null && $src_fw_e_f == null && $src_fw_e_t == null && $src_due_f == null &&
                $src_due_t == null && empty($src_scope_month) && $src_update_f == null && $src_update_t == null) {
            $lastYearScope = $this->getLastYearScope($src_bu);
        } else {
            $lastYearScope = '';
        }
        $result = $this->getForecastPageList($tasklist, $ioflist, $pmEditList, $isrefresh);
        $final = $this->filterConditions($result, $src_client, $src_bu, $src_user, $src_proj, $src_contract, $src_step, $src_scope_f, $src_scope_t, $src_fw_s_f, $src_fw_s_t, $src_fw_e_f, $src_fw_e_t, $src_due_f, $src_due_t, $src_scope_month, $src_scope_All, $src_update_f, $src_update_t);
		
        return $this->render('ForecastBundle:Default:index.html.twig', array(
                    'tasks' => $final,
                    'current' => $current,
                    'month' => $month,
                    'forecastForm' => $forecastForm->createView(),
                    'forecastsearchForm' => $forecastsearchForm->createView(),
                    'user' => $user,
                    'pmlist' => $pmlist,
                    'lastYearScope' => $lastYearScope,
                    'step' => $this->stepList(),
                    'contractNumber' => $this->contractNumberList(),
                    'ace' => $this->AceList(),
                    'projectManager' => $this->ProjectManagerList(),
                    'client' => $this->ClientList(),
                    'bu' => $this->BUList()
        ));
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
	
	protected function getForecastTasks() {

        $em = $this->getDoctrine()->getManager();
        /*$qb = $em->createQueryBuilder();

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
        $result = $query->getArrayResult();*/
		
		$connection = $em->getConnection();
		
		$sql = 'select t.*,p.* from task t left join project p on (t.project_id=p.id) where (t.number BETWEEN 101 AND 116 OR t.number = 600) and t.resume LIKE "%field%" OR t.resume LIKE "%delivered%"';
			
		$statement = $connection->prepare($sql);
		$statement->execute();
		$result = $statement->fetchAll();
		
        return $result;
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
	
	protected function getLastYearScope($src_bu) {
        //get Current month and year
        $currentTime = date('Y-m', time());
        //get Arr of last year but same month
        $lastYearArr = array();
        $lastYearFinal = array();
        for ($i = 0; $i < 12; $i++) {
            $lastYearArr[$i] = date('Y-m', strtotime('-1 year, +' . $i . ' month', strtotime($currentTime)));
        }
        foreach ($lastYearArr as $key => $ly) {
            $daysOfMonth = date('t', strtotime($ly));
            $lastYearFinal[$key] = $ly . '-' . $daysOfMonth;
        }

        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('date')
                ->from('AlbatrossDailyBundle:Date', 'date')
                ->leftJoin('date.bu', 'bu');
        if ($src_bu == null) {
            $qb->where('bu.id is null');
        } else {
            $qb->where('bu.id = :bid');
            $qb->setParameter('bid', $src_bu->getId());
        }
        $qb->andWhere('date.dailydate IN (:dayArr)')
                ->setParameter('dayArr', $lastYearFinal);
        $query = $qb->getQuery();
        $result = $query->getArrayResult();

        $dateIdArr = array();
        foreach ($result as $re) {
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
        foreach ($scopeResult as $s) {
            $temp[$s['date']['dailydate']->format('Y-m-d')] = $s['number'];
        }
        $final = array();
        foreach ($temp as $key => $f) {
            if (is_int($f)) {
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
                    if (!$pmEdit['reporttype']) {
                        $allobj = $pmEdit['reportduedate'];
                        $all = $allobj->format('Y-m-d');
                        $reportType = 'date';
                    } else {
                        $all = $pmEdit['reportduetext'];
                        $reportType = 'text';
                    }
                    if ($updateobj != '') {
                        $update = $updateobj->format('Y-m-d');
                    } else {
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
                    if (!$attachmentInfo['reporttype']) {
                        $allobj = $attachmentInfo['reportduedate'];
                        $all = $allobj->format('Y-m-d');
                    } else {
                        $all = $attachmentInfo['reportduedatetext'];
                    }
                    $updateobj = $attachmentInfo['attachments']['submitteddate'];
                    $fws = $fwsobj->format('Y-m-d');
                    $fwe = $fweobj->format('Y-m-d');
                    if ($updateobj != '') {
                        $update = $updateobj->format('Y-m-d');
                    } else {
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
                    $forecastList[$re['project']['id']]['bu'][$buArr[$buKey]]['update'] = $update;
                    $forecastList[$re['project']['id']]['pm'] = $attachmentInfo['attachments']['user']['username'];
                    $forecastList[$re['project']['id']]['pmid'] = $attachmentInfo['attachments']['user']['id'];
                    $forecastList[$re['project']['id']]['All'] = $all;
                }
                if (!isset($forecastList[$re['project']['id']]['bu'][$buArr[$buKey]])) {
                    $updateobj = $re['createdDate'];
                    if ($updateobj != '') {
                        $update = $updateobj->format('Y-m-d');
                    } else {
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
	
	protected function filterConditions($result, $src_client, $src_bu, $src_user, $src_proj, $src_contract, $src_step, $src_scope_f, $src_scope_t, $src_fw_s_f, $src_fw_s_t, $src_fw_e_f, $src_fw_e_t, $src_due_f, $src_due_t, $src_scope_month, $src_scope_All, $src_update_f, $src_update_t) {

        if ($src_bu == null) {
            $src_bu = '';
        } else {
            $src_bu = $src_bu->getCode();
        }

        $stepArr = array(0 => 'Contract', 1 => 'PM', 2 => 'IOF', 3 => 'exception');
        if (empty($src_step)) {
            $src_step = array(0, 1, 2);
        } else {
            for ($i = 0; $i < 3; $i++) {
                if (!isset($src_step[$i]))
                    $src_step[$i] = 3;
            }
        }
        $userArr = $src_user->toArray();
        $userArrSearch = array();
        if (empty($userArr)) {
            $src_user = '';
        } else {
            foreach ($userArr as $u) {
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
	
	private function stepList()
    {
		return array('Contract','PM Update','IOF');
    }
	
	private function contractNumberList()
    {
		$em = $this->getDoctrine()->getManager();
		$contractQB = $em->createQueryBuilder();
		$contractQB->select('t.projectNumber as projectnumber')
				->from('AlbatrossAceBundle:Task', 't')
				->where('t.number > 100')
				->andWhere('t.number < 177');
		$contractQuery = $contractQB->getQuery();
		$contractArray = $contractQuery->getArrayResult();
		
		return $contractArray;
    }
	
	private function AceList()
    {
		$em = $this->getDoctrine()->getManager();
		$aceQB = $em->createQueryBuilder();
		$aceQB->select('p.name')
			->from('AlbatrossAceBundle:Project', 'p')
			->orderBy('p.name', 'ASC');
		$aceQuery = $aceQB->getQuery();
		$aceArray = $aceQuery->getArrayResult();
		
		return $aceArray;
    }
	
	private function ProjectManagerList()
    {
        $em = $this->getDoctrine()->getManager();
        $pmQB = $em->createQueryBuilder();
        $pmQB->select('u','i')
                ->from('AlbatrossUserBundle:User','u')
                ->leftJoin('u.identity', 'i')
                ->where("i.name IN ('Senior Project Manager','Project Manager','BU Manager')")
                ->andWhere('u.status = :active')
                ->setParameters(array('active'=>'active'));
        $pmQuery = $pmQB->getQuery();
        $pmArray = $pmQuery->getArrayResult();
        
        return $pmArray;
    }
    
    private function ClientList()
    {
        $em = $this->getDoctrine()->getManager();
        $clientQB = $em->createQueryBuilder();
        $clientQB->select('c')->from('AlbatrossCustomBundle:Customclient', 'c');
        $clientQuery = $clientQB->getQuery();
        $clientArray = $clientQuery->getArrayResult();
        
        return $clientArray;
    }
    
    private function BUList()
    {
        $em = $this->getDoctrine()->getManager();
        $buQB = $em->createQueryBuilder();
        $buQB->select('b')->from('AlbatrossAceBundle:Bu', 'b');
        $buQuery = $buQB->getQuery();
        $buArray = $buQuery->getArrayResult();
        
        return $buArray;
    }
}
