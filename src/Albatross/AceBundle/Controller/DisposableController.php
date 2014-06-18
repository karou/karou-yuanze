<?php

namespace Albatross\AceBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DisposableController extends Controller {
    public function disposableFunctionCollectionAction(){
        return $this->render('AlbatrossAceBundle:Default:disposableFunctionCollection.html.twig');
    }
    //check wave step: contract forecast IOF
    public function disposableFunctionStepAction() {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('cw', 'proj', 'task', 'iof', 'forecast')
                ->from('AlbatrossCustomBundle:Customwave', 'cw')
                ->leftJoin('cw.project', 'proj')
                ->leftJoin('cw.attachments', 'iof')
                ->leftJoin('proj.tasks', 'task')
                ->leftJoin('task.forecast', 'forecast')
                ->where('(task.number BETWEEN 101 AND 116) OR task.number = 600');
        $query = $qb->getQuery();
        $resultArr = $query->getResult();
        foreach ($resultArr as $result) {
            if ($result->getAttachments() == null) {
                $checkPm = 0;
                foreach ($result->getProject() as $proj) {
                    if($checkPm == 1){
                        break;
                    }else{
                        foreach($proj->getTasks() as $t) {
                            if ($t->getNumber() > 100 && $t->getNumber() < 117 && count($t->getForecast()->toArray()) > 0) {
                                $result->setWaveStep('PM'); // from pm 
                                $em->persist($result);
                                $checkPm = 1;
                                break;
                            } else {
                                $result->setWaveStep('ACE');
                                $em->persist($result);
                            }
                        }
                    }
                }
            } else {    // from iof
                $result->setWaveStep('IOF');
                $em->persist($result);
            }
        }
        $em->flush();
        return new Response('victory');
    }
    //save delivery date
    public function disposableFunctionDeliveryAction() {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('cw', 'proj', 'task', 'iof', 'info', 'bu', 'forecast')
                ->from('AlbatrossCustomBundle:Customwave', 'cw')
                ->leftJoin('cw.project', 'proj')
                ->leftJoin('cw.attachments', 'iof')
                ->leftJoin('iof.attachinfo', 'info')
                ->leftJoin('info.bu', 'bu')
                ->leftJoin('proj.tasks', 'task')
                ->leftJoin('task.forecast', 'forecast')
                ->where('(task.number BETWEEN 101 AND 116) OR task.number = 600');
        $query = $qb->getQuery();
        $resultArr = $query->getArrayResult();
        foreach ($resultArr as $result) {
            $reportDelivery = '-';
            if ($result['attachments'] == null) {
                foreach ($result['project'] as $proj) {
                    foreach($proj['tasks'] as $t) {
                        if ($t['number'] > 100 && $t['number'] < 117 && !empty($t['forecast'])) { // from pm
                            $for = array();
                            foreach ($t['forecast'] as $k => $forc) {
                                if ($k == 0) {
                                    $for = $forc;
                                } else {
                                    if ($for['edittime'] < $forc['edittime']) $for = $forc;
                                }
                            }
                            if ($reportDelivery == '-') {
//                                if ($for['reporttype']) $reportDelivery = $for['reportduetext'];
                                $reportDelivery = is_object($for['reportduedate']) ? $for['reportduedate']->format('Y-m-d') : '-';
                            }else {
                                if (!$for['reporttype']) {
//                                    $reportDelivery = $for['reportduetext'];
//                                } else {
                                    if (strtotime($reportDelivery) < strtotime($for['reportduedate']->format('Y-m-d'))) {
                                        $reportDelivery = $for['reportduedate']->format('Y-m-d');
                                    }
                                }
                            }
                        } else {
                            if ($t['number'] == 600) { // from task
                                if ($t['reportduedate'] != null) {
                                    if ($reportDelivery == '-') $reportDelivery = $t['reportduedate'];
                                    else if (strtotime($reportDelivery) < strtotime($t['reportduedate'])) $reportDelivery = $t['reportduedate'];
                                }
                            }
                        }
                    }
                }
            } else {    // from iof
                foreach ($result['attachments']['attachinfo'] as $attachinfo) {
                    if ($reportDelivery == '-') {
//                        if ($attachinfo['reporttype']) $reportDelivery = $attachinfo['reportduedatetext'];
                        $reportDelivery = $attachinfo['reportduedate']->format('Y-m-d');
                    }else if (strtotime($reportDelivery) < strtotime($attachinfo['reportduedate']->format('Y-m-d'))) {
//                        if ($attachinfo['reporttype']) $reportDelivery = $attachinfo['reportduedatetext'];
                        $reportDelivery = $attachinfo['reportduedate']->format('Y-m-d');
                    }
                }
            }
            $waveEntity = $em->getRepository('AlbatrossCustomBundle:Customwave')->find($result['id']);
            $waveEntity->setDeliveryDate($reportDelivery);
            $em->persist($waveEntity);
        }
        $em->flush();
        return new Response('victory');
    }
    public function disposableFunctionPercentAction($offset) {
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
        
        $em = $this->getDoctrine()->getManager();
        
        $fetchAll = $em->getRepository('AlbatrossCustomBundle:Customwave')->findAll();
        $count = count($fetchAll);
        $qb = $em->createQueryBuilder();
        $qb->select('wave')
                ->from('AlbatrossCustomBundle:Customwave', 'wave')
                ->setFirstResult($offset)
                ->setMaxResults(50);
        $query = $qb->getQuery();
        $resultArr = $query->getResult();
        foreach ($resultArr as $waveEntity) {
            $campaignArr = $waveEntity->getCampaign();
            $final = array();
            $projecName = $waveEntity->getCustomProject()->getName();
            $pNameArr = explode('_', $projecName);
            $bu = $pNameArr[2];
            $notCheckArr = array('WW', 'APAC', 'EU');

            if (in_array($bu, $notCheckArr)) {
                $filterBu = '';
            } else {
                $em = $this->getDoctrine()->getManager();
                $buEntity = $em->getRepository('AlbatrossAceBundle:Bu')->findOneByCode($bu);
                $buName = $buEntity->getName();
                $filterBu = $buName;
            }
            foreach($campaignArr as $campaign) {
                $survey = $campaign->getAolsurvey()->toArray();
                foreach ($survey as $s) {
                    if ($s->getMailboxName() != 'mdelete' && $s->getMailboxName() != 'invalidsurvey' &&
                            ($filterBu == '' || $filterBu == $s->getLocation()->getCountry()->getBu()->getName())) {
                        if (!isset($final['total']))
                            $final['total'] = 0;
                        if (!isset($final['assign']))
                            $final['assign'] = 0;
                        if (!isset($final['fwdone']))
                            $final['fwdone'] = 0;
                        if (!isset($final['validation']))
                            $final['validation'] = 0;

                        if (in_array($s->getSurveyStatusName(), $statusValidation)) {
                            $final['validation'] ++;
                            $final['fwdone'] ++;
                            $final['assign'] ++;
                            $final['total'] ++;
                        } else if (in_array($s->getSurveyStatusName(), $statusFWdone)) {
                            $final['fwdone'] ++;
                            $final['assign'] ++;
                            $final['total'] ++;
                        } else if (in_array($s->getSurveyStatusName(), $status_Assigned)) {
                            $final['assign'] ++;
                            $final['total'] ++;
                        } else if (in_array($s->getSurveyStatusName(), $status_Total)) {
                            $final['total'] ++;
                        }
                    }
                }
            }
            if(isset($final['total']) && $final['total'] >0) {
                $final['assignPercent'] = floor(($final['assign'] / $final['total']) * 100);
                $final['fwdonePercent'] = floor(($final['fwdone'] / $final['total']) * 100);
                $final['validationPercent'] = floor(($final['validation'] / $final['total']) * 100);
            } else {
                $final['assignPercent'] = 0;
                $final['fwdonePercent'] = 0;
                $final['validationPercent'] = 0;
            }
            $waveEntity->setAssignPercent($final['assignPercent']);
            $waveEntity->setFieldworkPercent($final['fwdonePercent']);
            $waveEntity->setEditingPercent($final['validationPercent']);
            $em->persist($waveEntity);
        }
        $em->flush();
        if($offset > $count){
            return new Response('victory');
        } else {
            return new Response($offset + 50);
        }
    }
}