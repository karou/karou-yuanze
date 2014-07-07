<?php

namespace Albatross\DailyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Albatross\DailyBundle\Entity\Date;
use Albatross\DailyBundle\Entity\Number;
use Albatross\DailyBundle\Entity\Client;
use Symfony\Component\HttpFoundation\Response;
use Albatross\DailyBundle\Form\FileUploadType;
use Albatross\DailyBundle\Entity\Rules;
use Albatross\AceBundle\Entity\ForecastScope;
use Albatross\DailyBundle\Form\RulesType;

class DefaultController extends Controller {

    public function indexAction() {
        
		$em = $this->getDoctrine()->getManager();
		
		$clientsqb = $em->createQueryBuilder();
		
		$busqb = $em->createQueryBuilder();
        $busqb->select('b')
                ->from('AlbatrossAceBundle:Bu', 'b')
				->orderBy('b.name', 'ASC');
		
		$busquery = $busqb->getQuery();
        $busArr = $busquery->getArrayResult();
		
		/////////search fields///////////////
		$week_val = 0;
		if(isset($_POST['week_val']) and $_POST['week_val']!='')
			$week_val = $_POST['week_val'];
		
		$bu_id = 8;
		if(isset($_POST['bu']) and is_array($_POST['bu']))
			$bu_id = implode(',',$_POST['bu']);
			
		$list_bu = '';
		if(is_array($busArr) and count($busArr) > 0)
		{
			foreach($busArr as $bu)
			{
				$sel = '';
				if(isset($_POST['bu']) and is_array($_POST['bu']) and in_array($bu['id'],$_POST['bu']))
					$sel = ' selected="selected"';
				$list_bu .= '<option value="'.$bu['id'].'" '.$sel.'>'.$bu['name'].'</option>';
			}
		}
		//////////for getting number situation as for today/////
		$thisWeekStDate = date('Y-m-d',strtotime('this week'));
		$thisWeekStDateArr = explode('-',$thisWeekStDate);
		
		$monDate = date('Y-m-d',mktime(0,0,0,$thisWeekStDateArr[1],$thisWeekStDateArr[2]+($week_val*7),$thisWeekStDateArr[0]));
		$monDateDisp = str_replace('-','<br/>',date('D-d',mktime(0,0,0,$thisWeekStDateArr[1]+($week_val*7),$thisWeekStDateArr[2],$thisWeekStDateArr[0])));
		
		$monthDate = date('Y-m',mktime(0,0,0,$thisWeekStDateArr[1]-$week_val,$thisWeekStDateArr[2],$thisWeekStDateArr[0]));
		
		$sunDate = date('Y-m-d',mktime(0,0,0,$thisWeekStDateArr[1]-$week_val+1,$thisWeekStDateArr[2],$thisWeekStDateArr[0]));
		
		$arrMonDate = explode('-',$monDate);
		
		$tuesDate = date('Y-m-d',mktime(0,0,0,$arrMonDate[1],$arrMonDate[2]+1,$arrMonDate[0]));
		$tuesDateDisp = str_replace('-','<br/>',date('D-d',mktime(0,0,0,$arrMonDate[1],$arrMonDate[2]+1,$arrMonDate[0])));
		
		$wedDate = date('Y-m-d',mktime(0,0,0,$arrMonDate[1],$arrMonDate[2]+2,$arrMonDate[0]));
		$wedDateDisp = str_replace('-','<br/>',date('D-d',mktime(0,0,0,$arrMonDate[1],$arrMonDate[2]+2,$arrMonDate[0])));
		
		$thursDate = date('Y-m-d',mktime(0,0,0,$arrMonDate[1],$arrMonDate[2]+3,$arrMonDate[0]));
		$thursDateDisp = str_replace('-','<br/>',date('D-d',mktime(0,0,0,$arrMonDate[1],$arrMonDate[2]+3,$arrMonDate[0])));
		
		$friDate = date('Y-m-d',mktime(0,0,0,$arrMonDate[1],$arrMonDate[2]+4,$arrMonDate[0]));
		$friDateDisp = str_replace('-','<br/>',date('D-d',mktime(0,0,0,$arrMonDate[1],$arrMonDate[2]+4,$arrMonDate[0])));
		
		$satDate = date('Y-m-d',mktime(0,0,0,$arrMonDate[1],$arrMonDate[2]+5,$arrMonDate[0]));
		$satDateDisp = str_replace('-','<br/>',date('D-d',mktime(0,0,0,$arrMonDate[1],$arrMonDate[2]+5,$arrMonDate[0])));
		
		$sunDate = date('Y-m-d',mktime(0,0,0,$arrMonDate[1],$arrMonDate[2]+6,$arrMonDate[0]));
		$sunDateDisp = str_replace('-','<br/>',date('D-d',mktime(0,0,0,$arrMonDate[1],$arrMonDate[2]+6,$arrMonDate[0])));
		
		$connection = $em->getConnection();
		
		///////Code for Situation as for today listing////////
		$sqlSituation = 'select s.id,s.status,n.number,d.daily_date from status s left join number n on (s.id=n.status_id) left join date d on (d.id=n.date_id) where d.daily_date >= "'.$monDate.'" and d.daily_date <= "'.$sunDate.'" and d.bu_id in ('.$bu_id.') and s.today=1 group by s.status,d.daily_date order by s.weight,d.daily_date';
		
		$statement = $connection->prepare($sqlSituation);
		$statement->execute();
		$resultsSituation = $statement->fetchAll();
		
		$situationListing = '<tr>
								<th width="5%">
									<br/><b>No.</b>
								</th>
								<th width="25%">
									<br/><b>The number of</b>
								</th>
								<th width="5%" align="center">
									<b>'.$monDateDisp.'</b>
								</th>
								<th width="5%" align="center">
									<b>'.$tuesDateDisp.'</b>
								</th>
								<th width="5%" align="center">
									<b>'.$wedDateDisp.'</b>
								</th>
								<th width="5%" align="center">
									<b>'.$thursDateDisp.'</b>
								</th>
								<th width="5%" align="center">
									<b>'.$friDateDisp.'</b>
								</th>
								<th width="5%" align="center">
									<b>'.$satDateDisp.'</b>
								</th>
								<th width="5%" align="center">
									<b>'.$sunDateDisp.'</b>
								</th>
								<th align="center">
									<br/>GAP (between today &amp; yesterday)
								</th>
							</tr>';
							
		if(count($resultsSituation) > 0)
		{
			$arrFinal = array();
			foreach($resultsSituation as $resSit)
			{
				$prevVal = $resSit['number'];
				if($monDate==date('Y-m-d'))
				{
					$sqlMonSit = 'select s.id,s.status,n.number,d.daily_date from status s left join number n on (s.id=n.status_id) left join date d on (d.id=n.date_id) where d.daily_date = "'.$monDate.'" and s.id="'.$resSit['id'].'"';
				
					$statMon = $connection->prepare($sqlMonSit);
					$statMon->execute();
					$resMon = $statMon->fetchAll();
					
					$prevVal = $resMon[0]['number'];
				}
		
				$status = $resSit['status'];
				$date = $resSit['daily_date'];
				$arrFinal[$status][$date] = $resSit['number'];
				
				$gapTxt = '-';
				if($date==date('Y-m-d'))
				{
					$gapVal =  $resSit['number'] - $prevVal;
					if($gapVal>0)
						$gapTxt = $gapVal . '<i class="icon-arrow-up greenArrow"></i>';
					else
						$gapTxt = $gapVal . '<i class="icon-arrow-down redArrow"></i>';
				}
				$arrFinal[$status]['gap'] = $gapTxt;
			}
			
			$x=1;
			foreach($arrFinal as $keyFinal=>$final)
			{
				$situationListing .= '<tr>
								<td width="5%">
									'.$x.'
								</td>
								<td width="25%">
									'.$keyFinal.'
								</td>
								<td width="5%" align="center">
									'.((isset($final[$monDate]))?$final[$monDate]:'-').'
								</td>
								<td width="5%" align="center">
									'.((isset($final[$tuesDate]))?$final[$tuesDate]:'-').'
								</td>
								<td width="5%" align="center">
									'.((isset($final[$wedDate]))?$final[$wedDate]:'-').'
								</td>
								<td width="5%" align="center">
									'.((isset($final[$thursDate]))?$final[$thursDate]:'-').'
								</td>
								<td width="5%" align="center">
									'.((isset($final[$friDate]))?$final[$friDate]:'-').'
								</td>
								<td width="5%" align="center">
									'.((isset($final[$satDate]))?$final[$satDate]:'-').'
								</td>
								<td width="5%" align="center">
									'.((isset($final[$sunDate]))?$final[$sunDate]:'-').'
								</td>
								<td align="center">
									'.$final['gap'].'
								</td>
							</tr>';
				
				$x++;
			}
		}
		else
		{
			$situationListing .= '<tr>
								<td colspan="10" align="center">No data available.</td>
							</tr>';
		}
		
		///////Code for Today's result listing////////
		$sqlTodays = 'select s.id,s.status,n.number,d.daily_date from status s left join number n on (s.id=n.status_id) left join date d on (d.id=n.date_id) where d.daily_date >= "'.$monDate.'" and d.daily_date <= "'.$sunDate.'" and s.today=0 and d.bu_id in ('.$bu_id.') group by s.status,d.daily_date order by s.weight,d.daily_date';
		
		$todStatement = $connection->prepare($sqlTodays);
		$todStatement->execute();
		$resultsTodays = $todStatement->fetchAll();
		
		$todaysResult = '<tr>
								<th width="5%">
									<br/><b>No.</b>
								</th>
								<th width="25%">
									<br/><b>The number of</b>
								</th>
								<th width="5%" align="center">
									<b>'.$monDateDisp.'</b>
								</th>
								<th width="5%" align="center">
									<b>'.$tuesDateDisp.'</b>
								</th>
								<th width="5%" align="center">
									<b>'.$wedDateDisp.'</b>
								</th>
								<th width="5%" align="center">
									<b>'.$thursDateDisp.'</b>
								</th>
								<th width="5%" align="center">
									<b>'.$friDateDisp.'</b>
								</th>
								<th width="5%" align="center">
									<b>'.$satDateDisp.'</b>
								</th>
								<th width="5%" align="center">
									<b>'.$sunDateDisp.'</b>
								</th>
								<th align="center">
									<br/>Sum of the current week
								</th>
							</tr>';
							
		if(count($resultsTodays) > 0)
		{
			$arrFinal = array();
			foreach($resultsTodays as $resTod)
			{
				$prevVal = $resTod['number'];
				if($monDate==date('Y-m-d'))
				{
					$sqlMonSit = 'select s.id,s.status,n.number,d.daily_date from status s left join number n on (s.id=n.status_id) left join date d on (d.id=n.date_id) where d.daily_date = "'.$monDate.'" and s.id="'.$resTod['id'].'"';
				
					$statMon = $connection->prepare($sqlMonSit);
					$statMon->execute();
					$resMon = $statMon->fetchAll();
					
					$prevVal = $resMon[0]['number'];
				}
		
				$status = $resTod['status'];
				$date = $resTod['daily_date'];
				$arrFinal[$status][$date] = $resTod['number'];
			}
			
			$x=1;
			foreach($arrFinal as $keyFinal=>$final)
			{
				$totalVal = 0;
				if(isset($final[$monDate]))
					$totalVal += $final[$monDate];
				if(isset($final[$tuesDate]))
					$totalVal += $final[$tuesDate];
				if(isset($final[$wedDate]))
					$totalVal += $final[$wedDate];
				if(isset($final[$thursDate]))
					$totalVal += $final[$thursDate];
				if(isset($final[$friDate]))
					$totalVal += $final[$friDate];
				if(isset($final[$satDate]))
					$totalVal += $final[$satDate];
				if(isset($final[$sunDate]))
					$totalVal += $final[$sunDate];
					
				$todaysResult .= '<tr>
								<td width="5%">
									'.$x.'
								</td>
								<td width="25%">
									'.$keyFinal.'
								</td>
								<td width="5%" align="center">
									'.((isset($final[$monDate]))?$final[$monDate]:'-').'
								</td>
								<td width="5%" align="center">
									'.((isset($final[$tuesDate]))?$final[$tuesDate]:'-').'
								</td>
								<td width="5%" align="center">
									'.((isset($final[$wedDate]))?$final[$wedDate]:'-').'
								</td>
								<td width="5%" align="center">
									'.((isset($final[$thursDate]))?$final[$thursDate]:'-').'
								</td>
								<td width="5%" align="center">
									'.((isset($final[$friDate]))?$final[$friDate]:'-').'
								</td>
								<td width="5%" align="center">
									'.((isset($final[$satDate]))?$final[$satDate]:'-').'
								</td>
								<td width="5%" align="center">
									'.((isset($final[$sunDate]))?$final[$sunDate]:'-').'
								</td>
								<td align="center">
									'.$totalVal.'
								</td>
							</tr>';
				
				$x++;
			}
		}
		else
		{
			$todaysResult .= '<tr>
								<td colspan="10" align="center">No data available.</td>
							</tr>';
		}
		
		///////Code for Forecaset percentage listing////////
		$monForeSql = 'select forecast from forecastscope where bu_id="'.$bu_id.'" and month="'.$monthDate.'"';
		$monForeStatement = $connection->prepare($monForeSql);
		$monForeStatement->execute();
		$resultsMonFore = $monForeStatement->fetchAll();
		$forecastVal = (isset($resultsMonFore['forecast']))?$resultsMonFore['forecast']:0;
		
		$sqlPer = 'select s.id,s.status,n.number,d.daily_date from status s left join number n on (s.id=n.status_id) left join date d on (d.id=n.date_id) where d.daily_date >= "'.$monDate.'" and d.daily_date <= "'.$sunDate.'" and s.id=1  and d.bu_id in ('.$bu_id.') group by s.status,d.daily_date order by s.weight,d.daily_date';
		
		$perStatement = $connection->prepare($sqlPer);
		$perStatement->execute();
		$resultsPer = $perStatement->fetchAll();
		
		$perResult = '<tr>
						<th width="30%">
							<br/><b>Forecast in Total</b>
						</th>
						<th width="5%" align="center">
							<b>'.$monDateDisp.'</b>
						</th>
						<th width="5%" align="center">
							<b>'.$tuesDateDisp.'</b>
						</th>
						<th width="5%" align="center">
							<b>'.$wedDateDisp.'</b>
						</th>
						<th width="5%" align="center">
							<b>'.$thursDateDisp.'</b>
						</th>
						<th width="5%" align="center">
							<b>'.$friDateDisp.'</b>
						</th>
						<th width="5%" align="center">
							<b>'.$satDateDisp.'</b>
						</th>
						<th width="5%" align="center">
							<b>'.$sunDateDisp.'</b>
						</th>
						<th align="center">&nbsp;
							
						</th>
					</tr>';
							
		if(count($resultsPer) > 0)
		{
			$arrFinal = array();
			foreach($resultsPer as $resPer)
			{
				$status = $resPer['status'];
				$date = $resPer['daily_date'];
				$arrFinal[$status][$date] = $resPer['number']; //($resPer['number']/$forecastVal)*100;
			}
			
			$x=1;
			foreach($arrFinal as $keyFinal=>$final)
			{
				$totalVal = $monVal = $tuesVal = $wedVal = $thursVal = $friVal = $satVal = $sunVal = 0;
				if(isset($final[$monDate]))
				{
					$totalVal += $final[$monDate];
					
					if($forecastVal>0)
						$monVal = ($final[$monDate]/$forecastVal)*100;
				}
				if(isset($final[$tuesDate]))
				{
					$totalVal += $final[$tuesDate];
					
					if($forecastVal>0)
						$tuesVal = ($final[$tuesDate]/$forecastVal)*100;
				}
				if(isset($final[$wedDate]))
				{
					$totalVal += $final[$wedDate];
					
					if($forecastVal>0)
						$wedVal = ($final[$wedDate]/$forecastVal)*100;
				}
				if(isset($final[$thursDate]))
				{
					$totalVal += $final[$thursDate];
					
					if($forecastVal>0)
						$thursVal = ($final[$thursDate]/$forecastVal)*100;
				}
				if(isset($final[$friDate]))
				{
					$totalVal += $final[$friDate];
					
					if($forecastVal>0)
						$friVal = ($final[$friDate]/$forecastVal)*100;
				}
				if(isset($final[$satDate]))
				{
					$totalVal += $final[$satDate];
					
					if($forecastVal>0)
						$satVal = ($final[$satDate]/$forecastVal)*100;
				}
				if(isset($final[$sunDate]))
				{
					$totalVal += $final[$sunDate];
					
					if($forecastVal>0)
						$sunVal = ($final[$sunDate]/$forecastVal)*100;
				}	
				$perResult .= '<tr>
								<td width="30%" align="center">
									<b>'.$totalVal.'</b> &nbsp; <a href="javascript:;" class="btn btn-info btn-blue" onclick="refreshPerTable(\''.$bu_id.'\',\''.$week_val.'\');" id="refreshPerLink"><i class="icon-refresh"></i> Refresh</a>
								</td>
								<td width="5%" align="center">
									'.$monVal.'%
								</td>
								<td width="5%" align="center">
									'.$tuesVal.'%
								</td>
								<td width="5%" align="center">
									'.$wedVal.'%
								</td>
								<td width="5%" align="center">
									'.$thursVal.'%
								</td>
								<td width="5%" align="center">
									'.$friVal.'%
								</td>
								<td width="5%" align="center">
									'.$satVal.'%
								</td>
								<td width="5%" align="center">
									'.$sunVal.'%
								</td>
								<td align="center">&nbsp;
									
								</td>
							</tr>';
				
				$x++;
			}
		}
		else
		{
			$perResult .= '<tr>
								<td colspan="9" align="center">No data available.</td>
							</tr>';
		}
		
		return $this->render('AlbatrossDailyBundle:Default:index.html.twig', array('busArr' => $busArr,'monDate' => $monDate,'situationListing' => $situationListing, 'todaysResult' => $todaysResult, 'perResult' => $perResult, 'week_val' => $week_val, 'list_bu' => $list_bu));
    }

    //show the selected week info
    public function dailyAction($date, $bu, $current) {
        $em = $this->getDoctrine()->getManager();
        if ($date == '') {    //when click daily check menu link to page.   
            $select_time = time();
        } else {
            $select_time = strtotime($date);
        }

        $m_and_s = $this->getWeek($select_time); //get week where the selected date belong
        $monday_time_in_week = $m_and_s[0];
        $sunday_time_in_week = $m_and_s[1];
        $qb = $em->createQueryBuilder();

        if ($bu == '0' || $bu == '') {  //select all BU
            $bu = null;

            $qb->select('n, st')
                    ->from('AlbatrossDailyBundle:Number', 'n')
                    ->leftJoin('n.date', 'd')
                    ->leftJoin('d.bu', 'b')
                    ->leftJoin('n.status', 'st')
                    ->where('d.dailydate BETWEEN :m and :s')
                    ->andWhere('b.id is null');
            $qb->setParameters(array(
                'm' => $monday_time_in_week,
                's' => $sunday_time_in_week
            ));
        } else {
            $qb->select('n, st')
                    ->from('AlbatrossDailyBundle:Number', 'n')
                    ->leftJoin('n.date', 'd')
                    ->leftJoin('d.bu', 'b')
                    ->leftJoin('n.status', 'st')
                    ->where('d.dailydate BETWEEN :m and :s')
                    ->andWhere('b.id = :selected_b');
            $qb->setParameters(array(
                'm' => $monday_time_in_week,
                's' => $sunday_time_in_week,
                'selected_b' => $bu
            ));
        }

        $query = $qb->getQuery();
        $number = $query->getResult();
 
        $status = $this->getStatus();
        $result = $this->distinguishNumber($number);
        //getDate
        $show_date = $date ? $date : date('Y-m-d');
        $pre_week = date('Y-m-d', strtotime('last monday', strtotime('-1 week', strtotime($show_date)) + 86400));
        $nxt_week = date('Y-m-d', strtotime('last monday', strtotime('+1 week', strtotime($show_date)) + 86400));

        $cur_week_date_array = array();
        $cur_week = strtotime('last monday', strtotime('+1 day', strtotime($show_date)));

        $last_day_month = '';
        $weekName = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
        for ($t = 0; $t < 7; $t++) {
            $cur_week_date_array[date('d', $cur_week)]['date'] = date('Y-m-d', $cur_week);
            $cur_week_date_array[date('d', $cur_week)]['week'] = $weekName[$t];
            if(date('Y-m-d', $cur_week) == date('Y-m-t', $cur_week)){
                $last_day_month = $t;
            }
            $cur_week = $cur_week + 86400;
        }
        
        $cur_date = date('w', strtotime($show_date));
        if ($cur_date == 0)
            $cur_date = 7;

        $all_bu = $this->getAllBu();

        $fileForm = $this->createForm(new FileUploadType());

        $forcast = $this->getForcast($select_time, $bu);

        $percentCalc = $this->percentCalc($cur_week_date_array, $bu);
  
        //get monday gab number
        if (date('w', strtotime($date)) == 1) {
            $gab = $this->getMondayGabData($date, $bu, $status);
        } elseif( $date == '' ){
            $gab = $this->getMondayGabData(date('Y-m-d'), $bu, $status);
        } else {
            $gab = null;
        }
        //get report Done gab number, sun number first day month to select day
        $report_done = $this->getReportDone($select_time, $bu);

//        $results = $this->setSurveyValidatedNumber($result, $cur_week_date_array, $bu);

        return $this->render('AlbatrossDailyBundle:Default:dailycheck.html.twig', array(
                    'result' => $result,
                    'status' => $status,
                    'showdate' => $show_date,
                    'preweek' => $pre_week,
                    'nxtweek' => $nxt_week,
                    'cur_week_date_array' => $cur_week_date_array,
                    'cur_date' => $cur_date,
                    'current' => $current,
                    'all_bu' => $all_bu,
                    'bu' => $bu,
                    'fileForm' => $fileForm->createView(),
                    'forcast' => $forcast,
                    'percent_calc' => $percentCalc,
                    'gab' => $gab,
                    'report_done' => $report_done,
                    'last_date_month' => $last_day_month
        ));
    }
    public function downloadDailyFileAction($date = null, $fname = null) {
        $header = $this->getRequest()->server->getHeaders();
        if ($date == null) $date = date('ymd');
        else $date = date('ymd', strtotime($date));
        
        $file_name = $fname;
        $file_dir = $dir = 'aolExport2/'.$date.'/'.$file_name;
        
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
    //20130911 Survey Validated Number status caculate by curent day pending validate - pre day pending validate + curent day submitted surveys  
//    protected function setSurveyValidatedNumber($result, $cur_week_date_array, $bu){ 
//        $prenumber = $this->getPreFridaySubmitPendingNumber($cur_week_date_array, $bu);
//
//        if(isset($result['Pending validation']) && isset($result['Submitted surveys']) && $result['Pending validation'][1] != 0 && isset($prenumber[0]) && $prenumber[0]['number'] != 0 && $result['Submitted surveys'][1] != 0 && isset($prenumber[1]) && $prenumber[1]['number']){
//            $result['Survey Validated Number'][1] = (string)(($prenumber[1]['number'] - $result['Pending validation'][1]) + ($result['Submitted surveys'][1] - $prenumber[0]['number']));
//        } else {
//            $result['Survey Validated Number'][1] = (string)0;
//        }
//        
//        for($i = 1; $i < 7; $i++){
//            if(isset($result['Pending validation']) && isset($result['Submitted surveys']) && $result['Pending validation'][$i] != 0 && $result['Pending validation'][$i+1] != 0 && $result['Submitted surveys'][$i] != 0 && $result['Submitted surveys'][$i+1]){
//                $result['Survey Validated Number'][$i+1] = (string)(($result['Pending validation'][$i] - $result['Pending validation'][$i+1]) + ($result['Submitted surveys'][$i+1] - $result['Submitted surveys'][$i]));
//            } else {
//                $result['Survey Validated Number'][$i+1] = (string)0;
//            }
//        }
//
//        return $result;
//    }

    protected function getPreFridaySubmitPendingNumber($cur_week_date_array, $bu){
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();

        reset($cur_week_date_array);
        $monday = current($cur_week_date_array);
        $before_time = date('Y-m-d', strtotime('-3 day', strtotime($monday)));
        if ($bu == '0' || $bu == '') {  //select all
            $bu = null;

            $qb->select('n')
                    ->from('AlbatrossDailyBundle:Number', 'n')
                    ->leftJoin('n.date', 'd')
                    ->leftJoin('d.bu', 'b')
                    ->leftJoin('n.status', 's')
                    ->where('d.dailydate = :m')
                    ->andWhere('b.id is null')
                    ->andWhere('s.status = :submit OR s.status = :pending')
                    ->orderBy('s.id');
            $qb->setParameters(array(
                'm' => $before_time,
                'submit' => 'Submitted surveys',
                'pending' => 'Pending validation'
            ));
        } else {
            $qb->select('n')
                    ->from('AlbatrossDailyBundle:Number', 'n')
                    ->leftJoin('n.date', 'd')
                    ->leftJoin('d.bu', 'b')
                    ->leftJoin('n.status', 's')
                    ->where('d.dailydate = :m')
                    ->andWhere('b.id = :selected_b')
                    ->andWhere('s.status = :submit OR s.status = :pending')
                    ->orderBy('s.id');
            $qb->setParameters(array(
                'm' => $before_time,
                'selected_b' => $bu,
                'submit' => 'Submitted surveys',
                'pending' => 'Pending validation'
            ));
        }
        $query = $qb->getQuery();
        $number = $query->getArrayResult();
        return $number;
    }
    
    public function getMondayGabData($select_time, $bu, $status) { //for caculate the gab for monday
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $before_time = date('Y-m-d', strtotime('-3 day', strtotime($select_time)));
        if ($bu == '0' || $bu == '') {  //select all
            $bu = null;

            $qb->select('n')
                    ->from('AlbatrossDailyBundle:Number', 'n')
                    ->leftJoin('n.date', 'd')
                    ->leftJoin('d.bu', 'b')
                    ->where('d.dailydate = :m')
                    ->andWhere('b.id is null');
            $qb->setParameters(array(
                'm' => $before_time
            ));
        } else {
            $qb->select('n')
                    ->from('AlbatrossDailyBundle:Number', 'n')
                    ->leftJoin('n.date', 'd')
                    ->leftJoin('d.bu', 'b')
                    ->where('d.dailydate = :m')
                    ->andWhere('b.id = :selected_b');
            $qb->setParameters(array(
                'm' => $before_time,
                'selected_b' => $bu
            ));
        }

        $query = $qb->getQuery();
        $number = $query->getResult();
        $result = array();
        foreach ($number as $n) {
            $result[$n->getStatus()->getStatus()] = $n->getNumber();
        }
        $gab = array();
        foreach ($status as $s) {
            if (isset($result[$s['status']])) {
                $gab[$s['weight']] = $result[$s['status']];
            } else {
                $gab[$s['weight']] = 0;
            }
        }

        return $gab;
    }

    public function getForcast($select_time, $bu) {
        $select_date = date('Y-m-d', $select_time);
        $em = $this->getDoctrine()->getManager();

        if (!$em->getRepository('AlbatrossDailyBundle:Date')->findOneByDailydateAndBu(new \DateTime($select_date), $bu)) {
            $newdate = new Date();
            $newdate->setDailydate(new \DateTime($select_date));
            if ($bu == null) {
                $newdate->setBu(null);
            } else {
                $date_bu = $em->getRepository('AlbatrossAceBundle:Bu')->findById($bu);
                $newdate->setBu($date_bu[0]);
            }

            $em->persist($newdate);
            $em->flush();
        }
        $select_date_month = date('Y-m', strtotime($select_date));
        $resultSet = $em->getRepository('AlbatrossAceBundle:ForecastScope')->findOneByMonthAndBu($select_date_month, $bu);
        if(is_object($resultSet)){
            $result = $resultSet->getForecast();
        }else{
            $result = null;
        }
        if ($result == null)
            $result = 0;

        return $result;
    }

    //get report Done gab number, sun number first day month to select day
    public function getReportDone($select_time, $bu) {
        $firstMday = date('Y-m-01', $select_time);
        $currentDay = date('Y-m-d', $select_time);

        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        if($bu == null){
            $qb->select('n', 's')
                ->from('AlbatrossDailyBundle:Number', 'n')
                ->leftJoin('n.date', 'd')
                ->leftJoin('n.status', 's')
                ->leftJoin('d.bu', 'b')
                ->where('d.dailydate BETWEEN :fday AND :cday')
                ->andWhere('s.status = :Report OR s.status = :Survey OR s.status = :QC OR s.status = :Data OR s.status = :LE OR s.status = :Invalid') //get report done status
                ->andWhere('b.id is null');
        }else{
            $qb->select('n', 's')
                ->from('AlbatrossDailyBundle:Number', 'n')
                ->leftJoin('n.date', 'd')
                ->leftJoin('n.status', 's')
                ->leftJoin('d.bu', 'b')
                ->where('d.dailydate BETWEEN :fday AND :cday')
                ->andWhere('s.status = :Report OR s.status = :Survey OR s.status = :QC OR s.status = :Data OR s.status = :LE OR s.status = :Invalid') //get report done status
                ->andWhere('b.id = :bu');
        }
        if($bu == null){
            $parameters = array(
            'fday' => $firstMday,
            'cday' => $currentDay,
            'Report' => 'Report Done',
            'Survey' => 'Survey Validated Number',
            'QC' => 'QC Done',
            'Data' => 'Data Integrity Check Done',
            'LE' => 'LE Translation Done',
            'Invalid' => 'Invalid Survey Number',
            );
        }else{
            $parameters = array(
            'fday' => $firstMday,
            'cday' => $currentDay,
            'Report' => 'Report Done',
            'Survey' => 'Survey Validated Number',
            'QC' => 'QC Done',
            'Data' => 'Data Integrity Check Done',
            'LE' => 'LE Translation Done',
            'Invalid' => 'Invalid Survey Number',
            'bu' => $bu
            );
        }
        $qb->setParameters($parameters);
        $query = $qb->getQuery();
        $result = $query->getArrayResult();
        $sum = array();
        foreach ($result as $re){
            if(isset($sum[$re['status']['status']]))
                $sum[$re['status']['status']] += $re['number'];
            else
                $sum[$re['status']['status']] = (int)$re['number'];
        }
        
        return $sum;
    }

    public function percentCalc($days, $bu) {
        $em = $this->getDoctrine()->getManager();
        $forecast = 0;
        $number = 0;
        $i = 1;
        $return = array();
        foreach ($days as $d) {
            $month = date('Y-m', strtotime($d['date']));
            $date = new \DateTime($d['date']);
            $resultforecast = $em->getRepository('AlbatrossAceBundle:ForecastScope')->findOneByMonthAndBu($month, $bu);
            if (!empty($resultforecast)) {
                //get specific total forecast 
                if ($resultforecast->getForecast() == null)
                    $forecast = 0;
                else
                    $forecast = (int) $resultforecast->getForecast();

                //get number specific by date and bu 
                $qb = $em->createQueryBuilder();
                $qb->select('n')
                        ->from('AlbatrossDailyBundle:Number', 'n')
                        ->leftJoin('n.date', 'd')
                        ->leftJoin('d.bu', 'b')
                        ->leftJoin('n.status', 's')
                        ->where('d.dailydate = :date')
                        ->andWhere('s.status = :status');
                if($bu == null){
                    $qb->andWhere('b.id is null');
                    $parameters = array(
                        'date' => $date,
                        'status' => 'Submitted surveys'
                        );
                }else{
                    $qb->andWhere('b.id = :bu');
                    $parameters = array(
                        'date' => $date,
                        'bu' => $bu,
                        'status' => 'Submitted surveys'
                        );
                }
                        
                $qb->setParameters($parameters);
                $query = $qb->getQuery();
                $num_result = $query->getResult();

                if (count($num_result) > 0) {
                    foreach ($num_result as $nr) {
                        $number = (int) $nr->getNumber();
                    }
                }
            }
            if ($forecast == 0) {
                $return[$i] = 'inval';
            } elseif ($number == 0) {
                $return[$i] = '0%';
            } else {
                $percent = round(($number / $forecast) * 100);
                $return[$i] = $percent . '%';
            }
            $number = 0;
            $forecast = 0;
            $i++;
        }
        return $return;
    }

    public function setOneByAjaxAction() {
        $content = $this->getRequest()->getContent();
        $content = explode(":", $content);
        //************
        //*$content[0] is status id
        //*$content[1] is selected day in week to change
        //*$content[2] is cur showed day
        //*$content[3] is new number to be set
        //*$content[4] is bu
        //************
        $cur_date_in_week = date('w', strtotime($content[2]));
        if($cur_date_in_week == 0){
            $cur_date_in_week = 7;
        }
        $gab = (int) $content[1] - (int) $cur_date_in_week;
        $select_date = date('Y-m-d', strtotime("$gab day", strtotime($content[2])));
        $bu = $content[4];
        if ($bu == '0' || $bu == '')
            $bu = null;
        $em = $this->getDoctrine()->getManager();
        if (!$em->getRepository('AlbatrossDailyBundle:Date')->findOneByDailydateAndBu(new \DateTime($select_date), $bu)) {
            $newdate = new Date();
            $newdate->setDailydate(new \DateTime($select_date));
            if ($bu == null) {
                $newdate->setBu(null);
            } else {
                $date_bu = $em->getRepository('AlbatrossAceBundle:Bu')->findById($bu);
                $newdate->setBu($date_bu[0]);
            }

            $em->persist($newdate);
            $em->flush();
        }
        $date = $em->getRepository('AlbatrossDailyBundle:Date')->findOneByDailydateAndBu(new \DateTime($select_date), $bu);       
        // save the spacific Number to db and get new number value
        $result = $this->saveSpecificNumber($content[0], $date->getId(), $content[3]);
        $this->saveGlobalNumber($content[0], $date->getDailyDate());

        return (new Response($result->getNumber()));
    }

    //the specific forcast deside by date and bu.
    public function setForecastByAjaxAction() {
        $content = $this->getRequest()->getContent();
        $content = explode(":", $content);

        $date = $content[0];
        $bu = $content[1];
        $forecast = $content[2];

        $em = $this->getDoctrine()->getManager();
        $new_forecast = $em->getRepository('AlbatrossDailyBundle:Date')->findOneByDailydateAndBu(new \DateTime($date), $bu);

        if ($forecast == '')
            $forecast = 0;

        $new_forecast->setForecast($forecast);
        $em->persist($new_forecast);
        $em->flush();

        $result = $new_forecast->getForecast();

        return (new Response($result));
    }

    //set one spacfic number to selected bu and day
    public function saveSpecificNumber($statusid, $dateid, $value) {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('n')
                ->from('AlbatrossDailyBundle:Number', 'n')
                ->innerJoin('n.date', 'd')
                ->innerJoin('n.status', 's')
                ->where('d.id = :date')
                ->andWhere('s.id = :status');

        $qb->setParameters(array(
            'date' => $dateid,
            'status' => $statusid
        ));
        $query = $qb->getQuery();
        $number = $query->getResult();
        if (!empty($number)) {
            $number[0]->setNumber($value);

            $em->persist($number[0]);
            $result = $number[0];
        } else {
            $newnum = new Number();
            $status = $em->getRepository('AlbatrossDailyBundle:Status')->findOneById($statusid);
            $date = $em->getRepository('AlbatrossDailyBundle:Date')->findOneById($dateid);
            $newnum->setDate($date);
            $newnum->setStatus($status);
            $newnum->setNumber($value);

            $em->persist($newnum);
            $result = $newnum;
        }

        $em->flush();

        return $result;
    }

    //set one spacfic number to selected bu and day
    public function saveGlobalNumber($statusid, $date) {
        $em = $this->getDoctrine()->getManager();

        if (!$em->getRepository('AlbatrossDailyBundle:Date')->findOneByDailydateAndBu($date, null)) {
            $newdate = new Date();
            $newdate->setDailydate($date);
            $newdate->setBu(null);

            $em->persist($newdate);
            $em->flush();
        }
        $dateGlobal = $em->getRepository('AlbatrossDailyBundle:Date')->findOneByDailydateAndBu($date, null);
        $qb1 = $em->createQueryBuilder();
        $qb1->select('n')
                ->from('AlbatrossDailyBundle:Number', 'n')
                ->innerJoin('n.date', 'd')
                ->innerJoin('n.status', 's')
                ->where('d.dailydate = :date')
                ->andWhere('d.bu IS NULL')
                ->andWhere('s.id = :status');

        $qb1->setParameters(array(
            'date' => $date,
            'status' => $statusid
        ));
        $query1 = $qb1->getQuery();
        $global = $query1->getOneOrNullResult();
        if (!$global) {
            $global = new Number();
            $status = $em->getRepository('AlbatrossDailyBundle:Status')->findOneById($statusid);
            $global->setDate($dateGlobal);
            $global->setStatus($status);
        }

        $qb = $em->createQueryBuilder();
        $qb->select('n')
                ->from('AlbatrossDailyBundle:Number', 'n')
                ->innerJoin('n.date', 'd')
                ->innerJoin('n.status', 's')
                ->where('d.dailydate = :date')
                ->andWhere('d.bu IS NOT NULL')
                ->andWhere('s.id = :status');

        $qb->setParameters(array(
            'date' => $date,
            'status' => $statusid
        ));
        $query = $qb->getQuery();
        $numbers = $query->getResult();
        $sum = 0;
        if (!empty($numbers)) {
            foreach ($numbers as $number) {
                $sum += $number->getNumber();
            }
        }
        $global->setNumber($sum);
        $em->persist($global);
        $em->flush();
    }

    public function getWeek($cur_time) {
        $cur_time = strtotime(date('Y-m-d', $cur_time));
        $cur_date_in_week = date('w', $cur_time);
        if ($cur_date_in_week == '0') {
            $f_time_in_week = strtotime('last monday', $cur_time);
            $l_time_in_week = $cur_time;
        } elseif ($cur_date_in_week == '1') {
            $f_time_in_week = $cur_time;
            $l_time_in_week = strtotime('next sunday', $cur_time);
        } else {
            $f_time_in_week = strtotime('last monday', $cur_time);
            $l_time_in_week = strtotime('next sunday', $cur_time);
        }

        $f_time_in_week = date('Y-m-d', $f_time_in_week);
        $l_time_in_week = date('Y-m-d', $l_time_in_week);
        return array($f_time_in_week, $l_time_in_week);
    }

    //get all status
    public function getStatus() {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('s')
                ->from('AlbatrossDailyBundle:Status', 's')
                ->orderBy('s.weight', 'asc')
                ->addOrderBy('s.id', 'asc');
        $query = $qb->getQuery();
        $status = $query->getArrayResult();
        return $status;
    }

    //distinguish number by status
    public function distinguishNumber($num) {
        $result = $this->iniStatusArr();
        foreach ($num as $n) {
            for ($i = 1; $i <= 7; $i++) {
                foreach ($n->getDate()->getDailydate() as $key => $t) {
                    if ($key == 'date') {
                        $index = date('w', strtotime($t));
                        if ($index == '0') {
                            $index = 7;
                        }
                    }
                }
                if ($i == $index) {
                    $result[$n->getStatus()->getStatus()][$index] = $n->getNumber();
                } 
            }
        }
        
        return $result;
    }
    protected function iniStatusArr(){
        $result = array();
        $statusArr = array(
            'Submitted surveys','Assigned surveys','Delayed surveys (> 2 days)','Declined surveys','Pending validation',
            'Pending validation by Submission Time (> 4 days)','Pending validation by Visit Time (> 4 days)','Pending QC',
            'RFA opened','Open opportunity','Survey Validated Number','QC Done','Data Integrity Check Done',
            'LE Translation Done','Report Done','Invalid Survey Number'
        );
        foreach($statusArr as $status){
            for ($i = 1; $i <= 7; $i++) {
                $result[$status][$i] = 0;
            }
        }
        return $result;
    }
    //read all bu
    public function getAllBu() {
        $em = $this->getDoctrine()->getManager();
        $bu = $em->getRepository('AlbatrossAceBundle:Bu')->findAll();

        $result = array();
        foreach ($bu as $b) {
            $result[$b->getId()] = $b->getCode();
        }

        return $result;
    }

    public function fileUploadAction($date = null) {
        $form = $this->createForm(new FileUploadType());
        $form->bindRequest($this->getRequest());
        $data = $form->getData();

        if ($date)
            $dir = date('ymd', strtotime($date));
        else
            $dir = date('ymd');

        $filename = $data['file']->getClientOriginalName();
        $file_ext = $data['file']->getClientOriginalExtension();
        if ($file_ext != 'csv') {
            return $this->redirect($this->generateUrl('daily'));
        } else {
            $v = $data['file']->move(
                    $this->get('kernel')->getRootDir() . '/../web/aolExport2/' . $dir . '/', $filename
            );
            return $this->redirect($this->generateUrl('daily'));
        }
    }

    public function rulesAction() {
        $em = $this->getDoctrine()->getManager();
        $survey = array();
        $entities = $em->getRepository('AlbatrossDailyBundle:Rules')->findAll();
        foreach ($entities as $key => $entity) {
            $survey[$key] = explode(';', $entity->getSurveyKeyword());
        }
        return $this->render('AlbatrossDailyBundle:Default:rules.html.twig', array(
                    'entities' => $entities,
                    'menu_bar' => 'admin',
                    'menu_cal_cur' => 'rules',
                    'survey' => $survey
        ));
    }

    public function rulesNewAction() {
        $request = $this->getRequest();
        $entity = new Rules();
        $form = $this->createForm(new RulesType(), $entity);

        if ($request->getMethod() == "POST") {
            $form->bind($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();

                return $this->redirect($this->generateUrl('rules'));
            }
        }

        return $this->render('AlbatrossDailyBundle:Default:rules_new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                    'menu_bar' => 'admin',
                    'menu_cal_cur' => 'rules',
        ));
    }

    public function rulesEditAction($id) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AlbatrossDailyBundle:Rules')->findOneById($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Rules entity.');
        }
        $form = $this->createForm(new RulesType(), $entity);

        if ($request->getMethod() == "POST") {
            $form->bind($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();

                return $this->redirect($this->generateUrl('rules'));
            }
        }

        return $this->render('AlbatrossDailyBundle:Default:rules_edit.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                    'menu_bar' => 'admin',
                    'menu_cal_cur' => 'rules',
        ));
    }

    public function rulesDeleteAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AlbatrossDailyBundle:Rules')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Country entity.');
        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('rules'));
    }

}