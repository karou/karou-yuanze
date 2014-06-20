<?php

namespace Albatross\OperationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Albatross\OperationBundle\Form\ProjectStatusAceType;
use Albatross\OperationBundle\Form\ProjectStatusAolType;
use Albatross\OperationBundle\Entity\OperationQuestionnaire;
use Albatross\OperationBundle\Entity\OperationProject;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller {

    public function indexAction() {
        return $this->render('AlbatrossOperationBundle:Default:index.html.twig');
    }

    public function saveOperationDataAction() {
        $em = $this->getDoctrine()->getManager();
        $json = $this->getRequest()->getContent();
        $jsonArr = json_decode($json, true);
        $offset = $jsonArr['offset'];
        $limit = '10';
        if ($offset == '0') {
            $countQb = $em->createQueryBuilder();

            $countQb->select('count(campaign)')
                    ->from('AlbatrossAceBundle:Campaign', 'campaign')
                    ->leftJoin('campaign.questionnaire', 'q');
            $countQb->where('q.name NOT LIKE :keyword1 AND q.name NOT LIKE :keyword2 AND q.name NOT LIKE :keyword3 AND q.name NOT LIKE :keyword4 AND q.name NOT LIKE :keyword5');
            $countQb->setParameter('keyword1', 'Action Plan%');
            $countQb->setParameter('keyword2', 'Test%');
            $countQb->setParameter('keyword3', '%MISFIRE');
            $countQb->setParameter('keyword4', '%INTERNAL');
            $countQb->setParameter('keyword5', 'Demo%');
            $queryCount = $countQb->getQuery();
            $countResult = $queryCount->getArrayResult();
            $total = $countResult[0][1];
        } else {
            $total = $jsonArr['total'];
        }
        $qb = $em->createQueryBuilder();
        $qb->select('campaign')
                ->from('AlbatrossAceBundle:Campaign', 'campaign')
                ->leftJoin('campaign.questionnaire', 'q');
        $qb->where('q.name NOT LIKE :keyword1 AND q.name NOT LIKE :keyword2 AND q.name NOT LIKE :keyword3 AND q.name NOT LIKE :keyword4 AND q.name NOT LIKE :keyword5');
        $qb->setParameter('keyword1', 'Action Plan%');
        $qb->setParameter('keyword2', 'Test%');
        $qb->setParameter('keyword3', '%MISFIRE');
        $qb->setParameter('keyword4', '%INTERNAL');
        $qb->setParameter('keyword5', 'Demo%');
        $qb->orderBy('campaign.id', 'ASC');
        $qb->setFirstResult($offset);
        $qb->setMaxResults($limit);
        $query = $qb->getQuery();
        $result = $query->getResult();
        if (!empty($result)) {
            foreach ($result as $campaignEntity) {
                $cid = $campaignEntity->getId();
                $tmp = array();
                $percentArr = $this->getPercent($campaignEntity);
                if ($percentArr != null) {

                    $waveArr = $campaignEntity->getCustomwave()->toArray();
                    if ($waveArr) {
                        $dateArr = $this->getFwdateAction($campaignEntity->getCustomwave()->toArray(), $em, $campaignEntity->getId());
                        if (!empty($dateArr)) {
                            $tmp['fwsdate'] = $dateArr['fwsdate'];
                            $tmp['fwedate'] = $dateArr['fwedate'];
                            $tmp['reportdate'] = $dateArr['reportdate'];
                            $tmp['scope'] = $dateArr['scope'];
                        } else {
                            $tmp['fwsdate'] = 'none';
                            $tmp['fwedate'] = 'none';
                            $tmp['reportdate'] = 'none';
                            $tmp['scope'] = 'none';
                        }
                    } else {
                        $tmp['fwsdate'] = 'none';
                        $tmp['fwedate'] = 'none';
                        $tmp['reportdate'] = 'none';
                        $tmp['scope'] = 'none';
                    }

                    $tmp['firstDate'] = $percentArr['firstDate'];
                    $tmp['lastDate'] = $percentArr['lastDate'];
                    $tmp['num'] = $percentArr['totlenum'];
                    $tmp['assignnum'] = $percentArr['assignnum'];
                    $tmp['fwdonenum'] = $percentArr['fwdonenum'];
                    $tmp['edit'] = $percentArr['edit'];
                    $date = date('Y-m-d H:i:s');
                    if (!$operationQuestionnaireEntity = $em->getRepository('AlbatrossOperationBundle:OperationQuestionnaire')->findOneByCampaign($cid)) {
                        $operationQuestionnaireEntity = new OperationQuestionnaire();
                        $operationQuestionnaireEntity->setCampaign($campaignEntity);
                        $operationQuestionnaireEntity->setQuestionnaire($campaignEntity->getQuestionnaire());
                    } else {
                        $this->removeRelations($operationQuestionnaireEntity);
                    }
                    //save customclient
                    $clientCheckArr = array();
                    foreach ($waveArr as $wave) {
                        $clientCheckId = $wave->getCustomproject()->getCustomClient()->getId();
                        if (!isset($clientCheckArr[$clientCheckId])) {
                            $clientEntity = $wave->getCustomproject()->getCustomClient();
                            $clientEntity->addOperationquestionnaire($operationQuestionnaireEntity);
                            $operationQuestionnaireEntity->addCustomclient($clientEntity);
                            $em->persist($clientEntity);
                            $clientCheckArr[$clientCheckId] = 'set';
                        }
                    }
                    //save bu
                    $buCheckArr = array();
                    foreach ($percentArr['bu'] as $bk => $bu) {
                        if (!isset($buCheckArr[$bk])) {
                            $buEntity = $em->getRepository('AlbatrossAceBundle:Bu')->find($bk);
                            $buEntity->addOperationquestionnaire($operationQuestionnaireEntity);
                            $operationQuestionnaireEntity->addBu($buEntity);
                            $em->persist($buEntity);
                            $buCheckArr[$bk] = 'set';
                        }
                    }
                    //save country
                    $countryCheckArr = array();
                    foreach ($percentArr['country'] as $ck => $country) {
                        if (!isset($countryCheckArr[$ck])) {
                            $countryEntity = $em->getRepository('AlbatrossAceBundle:Country')->find($ck);
                            $countryEntity->addOperationquestionnaire($operationQuestionnaireEntity);
                            $operationQuestionnaireEntity->addCountry($countryEntity);
                            $em->persist($countryEntity);
                            $countryCheckArr[$ck] = 'set';
                        }
                    }
                    $operationQuestionnaireEntity->setAssignedNum($tmp['assignnum']);
                    $operationQuestionnaireEntity->setEditingNum($tmp['edit']);
                    $operationQuestionnaireEntity->setFwNum($tmp['fwdonenum']);
                    $operationQuestionnaireEntity->setSurveyNum($tmp['num']);
                    $operationQuestionnaireEntity->setReportdate($tmp['reportdate']);
                    $operationQuestionnaireEntity->setModifiedDate($date);
                    $operationQuestionnaireEntity->setFirstVisitDate($tmp['firstDate']);
                    $operationQuestionnaireEntity->setLastVisitDate($tmp['lastDate']);
                    $operationQuestionnaireEntity->setFwsdate($tmp['fwsdate']);
                    $operationQuestionnaireEntity->setFwedate($tmp['fwedate']);
                    $operationQuestionnaireEntity->setQid($campaignEntity->getQuestionnaire()->getId());
                    $operationQuestionnaireEntity->setCampid($campaignEntity->getId());
                    $operationQuestionnaireEntity->setQuestionnaireName($campaignEntity->getQuestionnaire()->getName());
                    $em->persist($operationQuestionnaireEntity);
                    //                $this->writeLog($tmp);
                }
            }
            $em->flush();
            $return = (string) ($offset + $limit);
        } else {
            $return = 'finish';
        }

        return new Response('{"offset":"' . $return . '", "total":"' . (string) $total . '"}');
    }

    public function saveOperationDataAceAction() {
        $em = $this->getDoctrine()->getManager();
        $json = $this->getRequest()->getContent();
        $jsonArr = json_decode($json, true);
        $offset = $jsonArr['offset'];
        $limit = '50';
        if ($offset == '0') {
            $countQb = $em->createQueryBuilder();
            $countQb->select('count(proj)')
                    ->from('AlbatrossAceBundle:Project', 'proj')
                    ->leftJoin('proj.tasks', 'task')
                    ->where('task.number > 100 AND task.number < 117');
            $queryCount = $countQb->getQuery();
            $countResult = $queryCount->getArrayResult();
            $total = $countResult[0][1];
        } else {
            $total = $jsonArr['total'];
        }
        $qb = $em->createQueryBuilder();
        $qb->select('proj')
                ->from('AlbatrossAceBundle:Project', 'proj')
                ->leftJoin('proj.tasks', 'task')
                ->where('task.number > 100 AND task.number < 117');
        $qb->orderBy('proj.id', 'asc');
        $qb->setFirstResult($offset);
        $qb->setMaxResults($limit);
        $query = $qb->getQuery();
        $result = $query->getResult();
        if (!empty($result)) {
            foreach ($result as $projectEntity) {
                $pid = $projectEntity->getId();

                $tmp = array();
                $buArr = array();
                $percentArr = '';
                if ($waveEntity = $projectEntity->getCustomwave()) {
                    $percentArr = $this->getAcePercent($waveEntity);
                    if ($attachment = $waveEntity->getAttachments()) {
                        foreach ($attachment->getAttachinfo() as $attachinfo) {
                            if ($attachinfo->getProject()->getId() == $pid) {
                                if (!isset($tmp['fwsdate'])) {
                                    $tmp['fwsdate'] = $attachinfo->getFwstartdate()->format('Y-m-d');
                                    $tmp['fwedate'] = $attachinfo->getFwenddate()->format('Y-m-d');
                                    $tmp['reportdate'] = $attachinfo->getReporttype() ? 'none' : $attachinfo->getReportduedate()->format('Y-m-d');
                                    $tmp['scope'] = (int) $attachinfo->getScope();
                                    $buArr[$attachinfo->getBu()->getName()] = $attachinfo->getBu()->getId();
                                } else {
                                    if ($tmp['fwsdate'] > $attachinfo->getFwstartdate()->format('Y-m-d'))
                                        $tmp['fwsdate'] = $attachinfo->getFwstartdate()->format('Y-m-d');
                                    if ($tmp['fwedate'] > $attachinfo->getFwenddate()->format('Y-m-d'))
                                        $tmp['fwedate'] = $attachinfo->getFwenddate()->format('Y-m-d');
                                    if (!$attachinfo->getReporttype()) {
                                        if (($tmp['reportdate'] > $attachinfo->getReportduedate()->format('Y-m-d')) || $tmp['reportdate'] == 'none')
                                            $tmp['reportdate'] = $attachinfo->getReportduedate()->format('Y-m-d');
                                    }
                                    $tmp['scope'] += $attachinfo->getScope();
                                    $buArr[$attachinfo->getBu()->getName()] = $attachinfo->getBu()->getId();
                                }
                            }
                        }
                        $tmp['user'] = $attachment->getUser()->getUsername();
                        $tmp['step'] = 'iof';
                    }
                }
                if (!isset($tmp['scope'])) { // no iof get data from forecast or task
                    $prjArr = array();
                    $buNumArr = $this->getBuNumberIdArr();
                    foreach ($projectEntity->getTasks() as $t) {
                        if ($t->getNumber() == 600) { // from task
                            if ($t->getReportduedate()) {
                                $prjArr[$pid] = $t->getReportduedate();
                            } else {
                                $prjArr[$pid] = 'no date';
                            }
                        }
                    }

                    foreach ($projectEntity->getTasks() as $t) {
                        $forecasts = $t->getForecast()->toArray();
                        if ($t->getNumber() > 100 && $t->getNumber() < 117 && !empty($forecasts)) { // from pm (forecast table)
                            $for = array();
                            foreach ($t->getForecast() as $k => $forc) {
                                if ($k == 0) {
                                    $for = $forc;
                                } else {
                                    if ($for->getEdittime() < $forc->getEdittime()) {
                                        $for = $forc;
                                    }
                                }
                            }
                            if (!isset($tmp['fwsdate'])) {
                                $tmp['fwsdate'] = $for->getFwstartdate()->format('Y-m-d');
                                $tmp['fwedate'] = $for->getFwenddate()->format('Y-m-d');
                                $tmp['reportdate'] = $for->getReporttype() ? 'none' : $for->getReportduedate()->format('Y-m-d');
                                $tmp['scope'] = (int) $for->getScope();
                                $buArr[$for->getTask()->getNumber()] = $buNumArr[$for->getTask()->getNumber() - 100];
                                $tmp['user'] = is_object($for->getUser()) ? $for->getUser()->getUsername() : '';
                            } else {
                                $tmp['fwsdate'] = ($tmp['fwsdate'] > $for->getFwstartdate()->format('Y-m-d')) ? $for->getFwstartdate()->format('Y-m-d') : $tmp['fwsdate'];
                                $tmp['fwedate'] = ($tmp['fwedate'] > $for->getFwenddate()->format('Y-m-d')) ? $for->getFwenddate()->format('Y-m-d') : $tmp['fwedate'];
                                if (!$for->getReporttype()) {
                                    $tmp['reportdate'] = ($tmp['reportdate'] > $for->getReportduedate()->format('Y-m-d') || $tmp['reportdate'] == 'none') ? $for->getReportduedate()->format('Y-m-d') : $tmp['reportdate'];
                                }
                                $tmp['scope'] += (int) $for->getScope();
                                $buArr[$for->getTask()->getNumber()] = $buNumArr[$for->getTask()->getNumber() - 100];
                                $tmp['user'] = is_object($for->getUser()) ? $for->getUser()->getUsername() : '';
                            }
                            $tmp['step'] = 'pm';
                        } else if ($t->getNumber() > 100 && $t->getNumber() < 117 && empty($forecasts)) { // from task
                            if (!isset($tmp['fwsdate'])) {
                                $tmp['fwsdate'] = $t->getFwstartdate();
                                $tmp['fwedate'] = $t->getFwenddate();
                                $tmp['reportdate'] = !empty($prjArr) ? $prjArr[$pid] : 0;
                                $tmp['scope'] = (int) $t->getScope();
                                $buArr[$t->getNumber()] = $buNumArr[$t->getNumber() - 100];
                                $tmp['user'] = $t->getPm();
                            } else {
                                if ($t->getFwstartdate() < $tmp['fwsdate'])
                                    $tmp['fwsdate'] = $t->getFwstartdate();
                                if ($t->getFwenddate() < $tmp['fwedate'])
                                    $tmp['fwedate'] = $t->getFwenddate();
                                if (!empty($prjArr) && $prjArr[$pid] < $tmp['reportdate'])
                                    $tmp['reportdate'] = $prjArr[$pid];
                                $tmp['scope'] += (int) $t->getScope();
                                $buArr[$t->getNumber()] = $buNumArr[$t->getNumber() - 100];
                                $tmp['user'] = $t->getPm();
                            }
                            $tmp['step'] = 'ace';
                        }
                    }
                }

                if (!empty($percentArr)) {
                    $tmp['firstDate'] = $percentArr['firstDate'];
                    $tmp['lastDate'] = $percentArr['lastDate'];
                    $tmp['num'] = $percentArr['totlenum'];
                    $tmp['assignnum'] = $percentArr['assignnum'];
                    $tmp['fwdonenum'] = $percentArr['fwdonenum'];
                    $tmp['edit'] = $percentArr['edit'];
                    $tmp['country'] = $percentArr['country'];
                } else {
                    $tmp['firstDate'] = 'none';
                    $tmp['lastDate'] = 'none';
                    $tmp['num'] = 'none';
                    $tmp['assignnum'] = 'none';
                    $tmp['fwdonenum'] = 'none';
                    $tmp['edit'] = 'none';
                    $tmp['country'] = 'none';
                }
                if (!isset($tmp['fwsdate'])) {
                    $tmp['fwsdate'] = 'none';
                    $tmp['fwedate'] = 'none';
                    $tmp['reportdate'] = 'none';
                    $tmp['scope'] = 'none';
                    $tmp['step'] = 'none';
                }
                $date = date('Y-m-d H:i:s');

                if (!$operationProjectEntity = $em->getRepository('AlbatrossOperationBundle:OperationProject')->findOneByProject($pid)) {
                    $operationProjectEntity = new OperationProject();
                    $operationProjectEntity->setProject($projectEntity);
                } else {
                    $this->removeOperationAceRelations($operationProjectEntity);
                }
                //save customclient
                if ($projectEntity->getCustomwave()) {
                    $clientEntity = $projectEntity->getCustomwave()->getCustomproject()->getCustomClient();
                    $operationProjectEntity->setCustomclient($clientEntity);
                    $em->persist($clientEntity);
                }
                //save bu
                foreach ($buArr as $bu) {
                    $buEntity = $em->getRepository('AlbatrossAceBundle:Bu')->find($bu);
                    $buEntity->addOperationproject($operationProjectEntity);
                    $operationProjectEntity->addBu($buEntity);
                    $em->persist($buEntity);
                }
                //save country
                $countryCheckArr = array();
                if (!empty($percentArr)) {
                    foreach ($percentArr['country'] as $ck => $country) {
                        if (!isset($countryCheckArr[$ck])) {
                            $countryEntity = $em->getRepository('AlbatrossAceBundle:Country')->find($ck);
                            $countryEntity->addOperationproject($operationProjectEntity);
                            $operationProjectEntity->addCountry($countryEntity);
                            $em->persist($countryEntity);
                            $countryCheckArr[$ck] = 'set';
                        }
                    }
                }
                $operationProjectEntity->setAssignedNum($tmp['assignnum']);
                $operationProjectEntity->setEditingNum($tmp['edit']);
                $operationProjectEntity->setFirstVisitDate($tmp['firstDate']);
                $operationProjectEntity->setFwNum($tmp['fwdonenum']);
                $operationProjectEntity->setFwedate($tmp['fwedate']);
                $operationProjectEntity->setFwsdate($tmp['fwsdate']);
                if ($tmp['step'] == 'ace')
                    $operationProjectEntity->setInfoType(1);
                else if ($tmp['step'] == 'pm')
                    $operationProjectEntity->setInfoType(2);
                else if ($tmp['step'] == 'iof')
                    $operationProjectEntity->setInfoType(3);
                $operationProjectEntity->setLastVisitDate($tmp['lastDate']);
                $operationProjectEntity->setModifiedDate($date);
                $operationProjectEntity->setPm($tmp['user']);
                $operationProjectEntity->setReportdate($tmp['reportdate']);
                $operationProjectEntity->setSurveyNum($tmp['num']);
                $em->persist($operationProjectEntity);
            }
            $em->flush();
            $return = (string) ($offset + $limit);
        }else {
            $return = 'finish';
        }

        return new Response('{"offset":"' . $return . '", "total":"' . (string) $total . '"}');
    }

    protected function removeRelations($entity) {
        $em = $this->getDoctrine()->getManager();
        $buArr = $entity->getBu()->toArray();
        foreach ($buArr as $bu) {
            $bu->removeOperationquestionnaire($entity);
            $entity->removeBu($bu);
            $em->persist($entity);
            $em->persist($bu);
        }
        $countryArr = $entity->getCountry()->toArray();
        foreach ($countryArr as $country) {
            $country->removeOperationquestionnaire($entity);
            $entity->removeCountry($country);
            $em->persist($country);
            $em->persist($entity);
        }
        $clientArr = $entity->getCustomclient()->toArray();
        foreach ($clientArr as $client) {
            $client->removeOperationquestionnaire($entity);
            $entity->removeCustomclient($client);
            $em->persist($client);
            $em->persist($entity);
        }
        $em->flush();
        return;
    }

    protected function removeOperationAceRelations($entity) {
        $em = $this->getDoctrine()->getManager();
        $buArr = $entity->getBu()->toArray();
        foreach ($buArr as $bu) {
            $bu->removeOperationproject($entity);
            $entity->removeBu($bu);
            $em->persist($entity);
            $em->persist($bu);
        }
        $countryArr = $entity->getCountry()->toArray();
        foreach ($countryArr as $country) {
            $country->removeOperationproject($entity);
            $entity->removeCountry($country);
            $em->persist($country);
            $em->persist($entity);
        }
        $em->flush();
        return;
    }

    //project Status(operation page)
    public function projectStatusAction($type = null) {
        $aceForm = $this->createForm(new ProjectStatusAceType());
        $aolForm = $this->createForm(new ProjectStatusAolType());

        //get questionnaire
        $em = $this->getDoctrine()->getManager();
        $aolsurveyQb = $em->createQueryBuilder();
        $aolsurveyQb->select('q')
                ->from('AlbatrossAceBundle:Questionnaire', 'q');
        $aolsurveyQuery = $aolsurveyQb->getQuery();
        $aolsurvey = $aolsurveyQuery->getArrayResult();
        $getAolHtml = $this->getAolHtml($aolsurvey);

        $request = $this->getRequest();
        if ($type == 'questionnaire') {
            $aolForm->bind($request);
            $result_merge = $this->getQuestionnaireStatus($request, $aolForm);
            $result = $result_merge['result'];
            $buArr = $result_merge['buArr'];
        } else if ($type == 'ace') {
            $aceForm->bind($request);
            $result_merge = $this->getAceprojectStatus($aceForm);
            $result = $result_merge['result'];
            $buArr = $result_merge['buArr'];
        } else {
            $buArr = '';
            $result = null;
        }

        $sort = $aolForm->getData();
        if ($sort['sort'] != null) {
            $param = explode('and', $sort['sort']);
            $result = $this->sortStatusResult($result, $param);
            if ($param[1] == 'a') {
                $sortKey = 'd';
            } else {
                $sortKey = 'a';
            }
        } else {
            $sortKey = 'a';
        }
        $aolFormView = $aolForm->createView();
        $aceFormView = $aceForm->createView();
        $opquestionnaireEntity = $em->getRepository('AlbatrossOperationBundle:Operationquestionnaire')->findOneBy(array(), array('modified_date' => 'desc'));
        $aceEntity = $em->getRepository('AlbatrossOperationBundle:OperationProject')->findOneBy(array(), array('modified_date' => 'desc'));
        if ($opquestionnaireEntity) {
            $opquestionnaireTime = $opquestionnaireEntity->getModifiedDate();
            $formatOpQuestTime = date('Y-m-d', strtotime($opquestionnaireTime));
        } else {
            $formatOpQuestTime = 'no data';
        }

        if ($aceEntity) {
            $opaceTime = $aceEntity->getModifiedDate();
            $formatOpAceTime = date('Y-m-d', strtotime($opaceTime));
        } else {
            $formatOpAceTime = 'no data';
        }
        return $this->render('AlbatrossOperationBundle:Default:projectStatus.html.twig', array(
                    'aceForm' => $aceFormView,
                    'aolForm' => $aolFormView,
                    'current' => 'custom_project_status',
                    'menu_bar' => 'custom',
                    'menu_cal_cur' => 'custom_project_status',
                    'aolform' => $getAolHtml,
                    'result' => $result,
                    'type' => $type,
                    'buArr' => $buArr,
                    'sortKey' => $sortKey,
                    'formatOpQuestTime' => $formatOpQuestTime,
                    'formatOpAceTime' => $formatOpAceTime
        ));
    }

    //search form for questionnaire list
    protected function getAolHtml($aolsurvey) {
        $surveyHtml = '<select id="surveySelection_1" onchange="surveyChange(this);" class="surveySelection" name="surveySelection[]"><option value=""></option>';    //html form to show

        foreach ($aolsurvey as $aol) {
            $surveyHtml .= '<option value="' . $aol['id'] . '">' . $aol['name'] . '</option>';
        }

        return $surveyHtml . '</select>';
    }

    protected function getQuestionnaireStatus($request, $aolForm) {
        $questionnaire = $request->get('surveySelection');
        if ($questionnaire != null) {
            $questionnaire_uniq = array_unique($questionnaire);
        } else {
            $questionnaire_uniq = null;
        }
        $campaign = $request->get('selectCampaign');
        $data = $aolForm->getData();
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('opquestionnaire', 'questionnaire', 'campaign')
                ->from('AlbatrossOperationBundle:Operationquestionnaire', 'opquestionnaire')
                ->leftJoin('opquestionnaire.bu', 'bu')
                ->leftJoin('opquestionnaire.country', 'country')
                ->leftJoin('opquestionnaire.questionnaire', 'questionnaire')
                ->leftJoin('opquestionnaire.campaign', 'campaign')
                ->leftJoin('opquestionnaire.customclient', 'client');

        if ($campaign[0] != '') {
            $qb->andWhere('campaign.id IN (:cArr)');
            $qb->setParameter('cArr', $campaign);
        }
        if ($data['questionnairename']) {
            $qb->andWhere('opquestionnaire.questionnaire_name LIKE :qname');
            $qb->setParameter('qname', '%' . $data['questionnairename'] . '%');
        }
        $buRequest = $data['bu']->toArray();
        $countryRequest = $data['country']->toArray();
        if (!empty($buRequest) && empty($countryRequest)) {
            $buArr = array();
            $buNameArr = array();
            foreach ($buRequest as $bu) {
                $buArr[] = $bu->getId();
                $buNameArr[] = $bu->getName();
            }
            $qb->andWhere('bu.id IN (:buArr)');
            $qb->setParameter('buArr', $buArr);
        } else if (!empty($countryRequest) && empty($buRequest)) {
            $countryArr = array();
            foreach ($countryRequest as $country) {
                $countryArr[] = $country->getId();
            }
            $qb->andWhere('country.id IN (:countryArr)');
            $qb->setParameter('countryArr', $countryArr);
            $buNameArr = '';
        } else if (!empty($countryRequest) && !empty($buRequest)) {
            $buArr = array();
            $buNameArr = array();
            foreach ($buRequest as $bu) {
                $buArr[] = $bu->getId();
                $buNameArr[] = $bu->getName();
            }
            $countryArr = array();
            foreach ($countryRequest as $country) {
                $countryArr[] = $country->getId();
            }
            $qb->andWhere('(bu.id IN (:buArr)) AND (country.id IN (:countryArr))');
            $qb->setParameter('buArr', $buArr);
            $qb->setParameter('countryArr', $countryArr);
        } else {
            $buNameArr = '';
        }
        $brand = $data['brand']->toArray();
        if (!empty($brand)) {
            $brandArr = array();
            foreach ($brand as $br) {
                $brandArr[] = $br->getId();
            }
            $qb->andWhere('client.id IN (:brArr)');
            $qb->setParameter('brArr', $brandArr);
        }
        if ($questionnaire_uniq[0] != '') {
            $qb->andWhere('questionnaire.id IN (:qArr)');
            $qb->setParameter('qArr', $questionnaire_uniq);
        }
        //date filter part
        if ($data['fw_s_f'] != null) {
            $qb->andWhere('opquestionnaire.fwsdate >= :fwsf');
            $qb->andWhere("opquestionnaire.fwsdate != 'none'");
            $qb->setParameter('fwsf', $data['fw_s_f']);
        }
        if ($data['fw_s_t'] != null) {
            $qb->andWhere('opquestionnaire.fwsdate <= :fwst');
            $qb->andWhere("opquestionnaire.fwsdate != 'none'");
            $qb->setParameter('fwst', $data['fw_s_t']);
        }
        if ($data['fw_e_f'] != null) {
            $qb->andWhere('opquestionnaire.fwedate >= :fwef');
            $qb->andWhere("opquestionnaire.fwedate != 'none'");
            $qb->setParameter('fwef', $data['fw_e_f']);
        }
        if ($data['fw_e_t'] != null) {
            $qb->andWhere('opquestionnaire.fwedate <= :fwet');
            $qb->andWhere("opquestionnaire.fwedate != 'none'");
            $qb->setParameter('fwet', $data['fw_e_t']);
        }
        if ($data['due_f'] != null) {
            $qb->andWhere('opquestionnaire.reportdate >= :duef');
            $qb->andWhere("opquestionnaire.reportdate != 'none'");
            $qb->setParameter('duef', $data['due_f']);
        }
        if ($data['due_t'] != null) {
            $qb->andWhere('opquestionnaire.reportdate <= :duet');
            $qb->andWhere("opquestionnaire.reportdate != 'none'");
            $qb->setParameter('duet', $data['due_t']);
        }

        $query = $qb->getQuery();
        $result = $query->getArrayResult();
        $final = array();
        foreach ($result as $r) {
            $camId = $r['campaign']['id'];
            $final[$camId]['fwsdate'] = $r['fwsdate'];
            $final[$camId]['fwedate'] = $r['fwedate'];
            $final[$camId]['reportdate'] = $r['reportdate'];
            $final[$camId]['scope'] = 'none';

            $final[$camId]['firstDate'] = $r['first_visit_date'];
            $final[$camId]['lastDate'] = $r['last_visit_date'];
            $final[$camId]['questionnairename'] = $r['questionnaire']['name'];
            $final[$camId]['campaignname'] = $r['campaign']['name'];
            $final[$camId]['num'] = $r['survey_num'];
            $final[$camId]['assigned'] = $r['survey_num'] != 0 ? floor(($r['assigned_num'] / $r['survey_num']) * 100) : 0;
            $final[$camId]['done'] = $r['survey_num'] != 0 ? floor(($r['fw_num'] / $r['survey_num']) * 100) : 0;
            $final[$camId]['validationPercent'] = $r['survey_num'] != 0 ? floor(($r['editing_num'] / $r['survey_num']) * 100) : 0;

            $final[$camId]['assignnum'] = $r['assigned_num'];
            $final[$camId]['fwdonenum'] = $r['fw_num'];
            $final[$camId]['edit'] = $r['editing_num'];
            if ($data['assign_f'] != null && isset($final[$camId])) {
                if ((float) $data['assign_f'] > $final[$camId]['assigned']) {
                    unset($final[$camId]);
                }
            }
            if ($data['assign_t'] != null && isset($final[$camId])) {
                if ((float) $data['assign_t'] < $final[$camId]['assigned']) {
                    unset($final[$camId]);
                }
            }
            if ($data['fw_done_f'] != null && isset($final[$camId])) {
                if ((float) $data['fw_done_f'] > $final[$camId]['done']) {
                    unset($final[$camId]);
                }
            }
            if ($data['fw_done_t'] != null && isset($final[$camId])) {
                if ((float) $data['fw_done_t'] < $final[$camId]['done']) {
                    unset($final[$camId]);
                }
            }
            if ($data['editing_done_f'] != null && isset($final[$camId])) {
                if ((float) $data['editing_done_f'] > $final[$camId]['validationPercent']) {
                    unset($final[$camId]);
                }
            }
            if ($data['editing_done_t'] != null && isset($final[$camId])) {
                if ((float) $data['editing_done_t'] < $final[$camId]['validationPercent']) {
                    unset($final[$camId]);
                }
            }
        }

        $result_merge['result'] = $final;
        $result_merge['buArr'] = $buNameArr;
        return $result_merge;
    }

    protected function getFwdateAction($waveentity, $em, $campaignId) {
        $aceResult = array();
        foreach ($waveentity as $c) {
            $qb2 = $em->createQueryBuilder();
            $qb2->select('cw', 'proj', 'task', 'iof', 'info', 'bu', 'forecast')
                    ->from('AlbatrossCustomBundle:Customwave', 'cw')
                    ->leftJoin('cw.campaign', 'cpn')
                    ->leftJoin('cw.project', 'proj')
                    ->leftJoin('cw.attachments', 'iof')
                    ->leftJoin('iof.attachinfo', 'info')
                    ->leftJoin('info.bu', 'bu')
                    ->leftJoin('proj.tasks', 'task')
                    ->leftJoin('task.forecast', 'forecast')
                    ->where('cpn.id = :cid')
                    ->andWhere('proj.id is not null')
                    ->andWhere('cw.id = :waveid')
                    ->andWhere('(task.number BETWEEN 101 AND 116) OR task.number = 600');
            $qb2->setParameter('cid', $campaignId);
            $qb2->setParameter('waveid', $c->getId());
            $query2 = $qb2->getQuery();
            $result2 = $query2->getArrayResult();
            if (!empty($result2)) {
                foreach ($result2 as $proj) {
                    if ($proj['attachments'] == null) { // no iof
                        foreach ($proj['project'] as $task) {

                            $prjArr = array();
                            foreach ($task['tasks'] as $t) {
                                if ($t['number'] == 600) { // from task
                                    if ($t['reportduedate'] != null) {
                                        $prjArr[$task['id']] = $t['reportduedate'];
                                    } else {
                                        $prjArr[$task['id']] = 'no date';
                                    }
                                }
                            }

                            foreach ($task['tasks'] as $t) {
                                if ($t['number'] > 100 && $t['number'] < 117 && !empty($t['forecast'])) { // from pm
                                    $for = array();
                                    foreach ($t['forecast'] as $k => $forc) {
                                        if ($k == 0) {
                                            $for = $forc;
                                        } else {
                                            if ($for['edittime'] < $forc['edittime']) {
                                                $for = $forc;
                                            }
                                        }
                                    }
                                    if (!isset($aceResult['fwsdate'])) {
                                        $aceResult['fwsdate'] = $for['fwstartdate']->format('Y-m-d');
                                        $aceResult['fwedate'] = $for['fwenddate']->format('Y-m-d');
                                        $aceResult['reportdate'] = $for['reporttype'] ? 'none' : $for['reportduedate']->format('Y-m-d');
                                        $aceResult['scope'] = (int) $for['scope'];
                                    } else {
                                        $aceResult['fwsdate'] = ($aceResult['fwsdate'] > $for['fwstartdate']->format('Y-m-d')) ? $for['fwstartdate']->format('Y-m-d') : $aceResult['fwsdate'];
                                        $aceResult['fwedate'] = ($aceResult['fwedate'] > $for['fwenddate']->format('Y-m-d')) ? $for['fwenddate']->format('Y-m-d') : $aceResult['fwedate'];
                                        if ($aceResult['reportdate'] == 'none') {
                                            if (!$for['reporttype']) {
                                                $aceResult['reportdate'] = $for['reportduedate']->format('Y-m-d');
                                            }
                                        } else {
                                            if (!$for['reporttype'] && $aceResult['reportdate'] > $for['reportduedate']->format('Y-m-d')) {
                                                $aceResult['reportdate'] = $for['reportduedate']->format('Y-m-d');
                                            }
                                        }
                                        $aceResult['scope'] += (int) $for['scope'];
                                    }
                                } else if ($t['number'] > 100 && $t['number'] < 117 && empty($t['forecast'])) { // from task
                                    if (!isset($aceResult['fwsdate'])) {
                                        $aceResult['fwsdate'] = $t['fwstartdate'];
                                        $aceResult['fwedate'] = $t['fwenddate'];
                                        $aceResult['reportdate'] = !empty($prjArr) ? $prjArr[$task['id']] : 0;
                                        $aceResult['scope'] = (int) $t['scope'];
                                    } else {
                                        if ($t['fwstartdate'] < $aceResult['fwsdate'])
                                            $aceResult['fwsdate'] = $t['fwstartdate'];
                                        if ($t['fwenddate'] < $aceResult['fwedate'])
                                            $aceResult['fwedate'] = $t['fwenddate'];
                                        if (!empty($prjArr) && $prjArr[$task['id']] < $aceResult['reportdate'])
                                            $aceResult['reportdate'] = $prjArr[$task['id']];
                                        $aceResult['scope'] += (int) $t['scope'];
                                    }
                                }
                            }
                        }
                    } else {    // from iof
                        $attachment = $proj['attachments'];
                        foreach ($attachment['attachinfo'] as $attachinfo) {
                            if (!isset($aceResult['fwsdate'])) {
                                $aceResult['fwsdate'] = $attachinfo['fwstartdate']->format('Y-m-d');
                                $aceResult['fwedate'] = $attachinfo['fwenddate']->format('Y-m-d');
                                $aceResult['reportdate'] = $attachinfo['reporttype'] ? 'none' : $attachinfo['reportduedate']->format('Y-m-d');
                                $aceResult['scope'] = (int) $attachinfo['scope'];
                            } else {
                                if ($aceResult['fwsdate'] > $attachinfo['fwstartdate']->format('Y-m-d'))
                                    $aceResult['fwsdate'] = $attachinfo['fwstartdate']->format('Y-m-d');
                                if ($aceResult['fwedate'] > $attachinfo['fwenddate']->format('Y-m-d'))
                                    $aceResult['fwedate'] = $attachinfo['fwenddate']->format('Y-m-d');
                                if (($aceResult['reportdate'] > $attachinfo['reportduedate']->format('Y-m-d')) || $aceResult['reportdate'] == 'none')
                                    $aceResult['reportdate'] = $attachinfo['reportduedate']->format('Y-m-d');
                                $aceResult['scope'] += $attachinfo['scope'];
                            }
                        }
                    }
                }
            }
        }

        return $aceResult;
    }

    protected function sortStatusResult($entities, $paramArr) {
        $keysvalue = $result = array();
        foreach ($entities as $k => $entity) {
            $keysvalue[$k] = $entity[$paramArr[0]];
        }
        if ($paramArr[1] == 'a') {
            asort($keysvalue);
        } else {
            arsort($keysvalue);
        }
        reset($keysvalue);
        foreach ($keysvalue as $k => $v) {
            $result[$k] = $entities[$k];
        }

        return $result;
    }

    public function getPercent($cam) {
        $filterArr = $this->filterArr();
        $newFilterArr0 = array_flip($filterArr[0]);
        $newFilterArr1 = array_flip($filterArr[1]);
        $newFilterArr2 = array_flip($filterArr[2]);
        $newFilterArr3 = array_flip($filterArr[3]);
        $final = array();
        $surveys = $cam->getAolsurvey()->toArray();
        $finalResult = array();
        $finalResult['firstDate'] = 'none';
        $finalResult['lastDate'] = 'none';
        $buArr = array();
        $countryArr = array();
        if (!empty($surveys)) {
            foreach ($surveys as $s) {
                if ($s->getMailboxName() != 'mdelete' && $s->getMailboxName() != 'invalidsurvey') {
                    if (!isset($final['total']))
                        $final['total'] = 0;
                    if (!isset($final['assign']))
                        $final['assign'] = 0;
                    if (!isset($final['fwdone']))
                        $final['fwdone'] = 0;
                    if (!isset($final['validation']))
                        $final['validation'] = 0;
                    if (isset($newFilterArr3[$s->getSurveyStatusName()])) {
                        $final['validation']++;
                        $final['fwdone']++;
                        $final['assign']++;
                        $final['total']++;
                    } else if (isset($newFilterArr2[$s->getSurveyStatusName()])) {
                        $final['fwdone']++;
                        $final['assign']++;
                        $final['total']++;
                    } else if (isset($newFilterArr1[$s->getSurveyStatusName()])) {
                        $final['assign']++;
                        $final['total']++;
                    } else if (isset($newFilterArr0[$s->getSurveyStatusName()])) {
                        $final['total']++;
                    }
                    if ($finalResult['firstDate'] == 'none' && (isset($newFilterArr2[$s->getSurveyStatusName()]) || isset($newFilterArr3[$s->getSurveyStatusName()]))) {
                        $finalResult['firstDate'] = date('Y-m-d', strtotime($s->getDate()));
                    } else if (isset($newFilterArr2[$s->getSurveyStatusName()]) || isset($newFilterArr3[$s->getSurveyStatusName()])) {
                        $finalResult['firstDate'] = (strtotime($finalResult['firstDate']) > strtotime($s->getDate())) ? date('Y-m-d', strtotime($s->getDate())) : $finalResult['firstDate'];
                    }
                    if ($finalResult['lastDate'] == 'none' && $final['fwdone'] / $final['total'] == 1) {
                        $finalResult['lastDate'] = date('Y-m-d', strtotime($s->getDate()));
                    } else if ($finalResult['lastDate'] != 'none' &&
                            $final['fwdone'] / $final['total'] == 1 &&
                            strtotime($finalResult['lastDate']) < strtotime($s->getDate())) {
                        $finalResult['lastDate'] = date('Y-m-d', strtotime($s->getDate()));
                    }

                    if ($s->getLocation()->getCountry()) {
                        $buArr[$s->getLocation()->getCountry()->getBu()->getId()] = $s->getLocation()->getCountry()->getBu()->getName();
                        $countryArr[$s->getLocation()->getCountry()->getId()] = $s->getLocation()->getCountry()->getName();
                    }
                }
            }
            if (isset($final['total'])) {
                $finalResult['totlenum'] = $final['total'];
                $finalResult['assignnum'] = $final['assign'];
                $finalResult['fwdonenum'] = $final['fwdone'];
                $finalResult['edit'] = $final['validation'];
                $finalResult['bu'] = $buArr;
                $finalResult['country'] = $countryArr;
            } else {
                $finalResult = null;
            }
        } else {
            $finalResult = null;
        }
        return $finalResult;
    }

    protected function filterArr() {

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
        $result[0] = $status_Total;
        $result[1] = $status_Assigned;
        $result[2] = $statusFWdone;
        $result[3] = $statusValidation;

        return $result;
    }

    public function getBuResultAction() {
        $ajaxData = $this->getRequest()->getContent();
        $ajaxDataArr = json_decode($ajaxData, true);
        $id = $ajaxDataArr['id'];
        $buSelectedArr = array();
        foreach ($ajaxDataArr as $k => $arr) {
            if ((string) $k != 'bu' && (string) $k != 'id') {
                $buSelectedArr[$k] = $arr;
            }
        }
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('cpn', 'q', 'aol', 'bu', 'cnty', 'loc')
                ->from('AlbatrossAceBundle:Campaign', 'cpn')
                ->leftJoin('cpn.questionnaire', 'q')
                ->leftJoin('cpn.aolsurvey', 'aol')
                ->leftJoin('aol.location', 'loc')
                ->leftJoin('loc.country', 'cnty')
                ->leftJoin('cnty.bu', 'bu')
                ->where('cpn.id = :cid');
        $qb->setParameter('cid', $id);
        $query = $qb->getQuery();
        $result = $query->getArrayResult();
        $percentArr = $this->getBuPercent($result[0], $buSelectedArr);

        $qb2 = $em->createQueryBuilder();
        $qb2->select('cw', 'proj', 'task', 'iof', 'info', 'bu', 'forecast')
                ->from('AlbatrossCustomBundle:Customwave', 'cw')
                ->leftJoin('cw.campaign', 'cpn')
                ->leftJoin('cw.project', 'proj')
                ->leftJoin('cw.attachments', 'iof')
                ->leftJoin('iof.attachinfo', 'info')
                ->leftJoin('info.bu', 'bu')
                ->leftJoin('proj.tasks', 'task')
                ->leftJoin('task.forecast', 'forecast')
                ->where('cpn.id = :cid')
                ->andWhere('proj.id is not null')
                ->andWhere('(task.number BETWEEN 101 AND 116) OR task.number = 600');
        $qb2->setParameter('cid', $id);
        $query2 = $qb2->getQuery();
        $result2 = $query2->getArrayResult();
        $buArr = $this->getBuArr();
        $aceResult = array();
        if (!empty($result2)) {
            foreach ($result2 as $proj) {
                if ($proj['attachments'] == null) {
                    foreach ($proj['project'] as $task) {

                        $prjArr = array();
                        foreach ($task['tasks'] as $t) {
                            if ($t['number'] == 600) { // from task
                                if ($t['reportduedate'] != null) {
                                    $prjArr[$task['id']] = $t['reportduedate'];
                                } else {
                                    $prjArr[$task['id']] = 'no date';
                                }
                            }
                        }

                        foreach ($task['tasks'] as $t) {
                            if ($t['number'] > 100 && $t['number'] < 117 && !empty($t['forecast'])) { // from pm
                                $for = array();
                                foreach ($t['forecast'] as $k => $forc) {
                                    if ($k == 0) {
                                        $for = $forc;
                                    } else {
                                        if ($for['edittime'] < $forc['edittime']) {
                                            $for = $forc;
                                        }
                                    }
                                }
                                if (!isset($aceResult[$buArr[$t['number'] - 100]])) {
                                    $aceResult[$buArr[$t['number'] - 100]]['fwsdate'] = $for['fwstartdate']->format('Y-m-d') . '(PM)';
                                    $aceResult[$buArr[$t['number'] - 100]]['fwedate'] = $for['fwenddate']->format('Y-m-d') . '(PM)';
                                    $aceResult[$buArr[$t['number'] - 100]]['reportdate'] = $for['reportduedate']->format('Y-m-d') . '(PM)';
                                    $aceResult[$buArr[$t['number'] - 100]]['scope'] = $for['scope'] . '(PM)';
                                } else {
                                    $aceResult[$buArr[$t['number'] - 100]]['fwsdate'] .= '<br/>' . $for['fwstartdate']->format('Y-m-d') . '(PM)';
                                    $aceResult[$buArr[$t['number'] - 100]]['fwedate'] .= '<br/>' . $for['fwenddate']->format('Y-m-d') . '(PM)';
                                    $aceResult[$buArr[$t['number'] - 100]]['reportdate'] .= '<br/>' . $for['reportduedate']->format('Y-m-d') . '(PM)';
                                    $aceResult[$buArr[$t['number'] - 100]]['scope'] .= '<br/>' . $for['scope'] . '(PM)';
                                }
                            } else if ($t['number'] > 100 && $t['number'] < 117) { // from task
                                if (!isset($aceResult[$buArr[$t['number'] - 100]])) {
                                    $aceResult[$buArr[$t['number'] - 100]]['fwsdate'] = $t['fwstartdate'] . '(ACE)';
                                    $aceResult[$buArr[$t['number'] - 100]]['fwedate'] = $t['fwenddate'] . '(ACE)';
                                    $aceResult[$buArr[$t['number'] - 100]]['reportdate'] = $prjArr[$task['id']] . '(ACE)';
                                    $aceResult[$buArr[$t['number'] - 100]]['scope'] = $t['scope'] . '(ACE)';
                                } else {
                                    $aceResult[$buArr[$t['number'] - 100]]['fwsdate'] .= '<br/>' . $t['fwstartdate']->format('Y-m-d') . '(ACE)';
                                    $aceResult[$buArr[$t['number'] - 100]]['fwedate'] .= '<br/>' . $t['fwenddate']->format('Y-m-d') . '(ACE)';
                                    $aceResult[$buArr[$t['number'] - 100]]['reportdate'] .= '<br/>' . $prjArr[$task['id']] . '(ACE)';
                                    $aceResult[$buArr[$t['number'] - 100]]['scope'] .= '<br/>' . $t['scope'] . '(ACE)';
                                }
                            }
                        }
                    }
                } else {    // from iof
                    $attachment = $proj['attachments'];
                    foreach ($attachment['attachinfo'] as $attachinfo) {
                        if (!isset($aceResult[$attachinfo['bu']['name']])) {
                            $aceResult[$attachinfo['bu']['name']]['fwsdate'] = $attachinfo['fwstartdate']->format('Y-m-d') . '(IOF)';
                            $aceResult[$attachinfo['bu']['name']]['fwedate'] = $attachinfo['fwenddate']->format('Y-m-d') . '(IOF)';
                            $aceResult[$attachinfo['bu']['name']]['reportdate'] = $attachinfo['reportduedate']->format('Y-m-d') . '(IOF)';
                            $aceResult[$attachinfo['bu']['name']]['scope'] = $attachinfo['scope'] . '(IOF)';
                        } else {
                            $aceResult[$attachinfo['bu']['name']]['fwsdate'] .= '<br/>' . $attachinfo['fwstartdate']->format('Y-m-d') . '(IOF)';
                            $aceResult[$attachinfo['bu']['name']]['fwedate'] .= '<br/>' . $attachinfo['fwenddate']->format('Y-m-d') . '(IOF)';
                            $aceResult[$attachinfo['bu']['name']]['reportdate'] .= '<br/>' . $attachinfo['reportduedate']->format('Y-m-d') . '(IOF)';
                            $aceResult[$attachinfo['bu']['name']]['scope'] .= '<br/>' . $attachinfo['scope'] . '(IOF)';
                        }
                    }
                }
            }
        }

        $final = $this->getStatusHtml($percentArr, $id, 'bu', $aceResult);
        return new Response($final);
    }

    protected function getBuPercent($cam, $buSelectedArr) {
        $filterArr = $this->filterArr();
        $final = array();
        $surveys = $cam['aolsurvey'];
        $finalResult = array();
        if (!empty($surveys)) {
            foreach ($surveys as $s) {
                $buId = $s['location']['country']['bu']['id'];
                $buName = $s['location']['country']['bu']['name'];
                if (empty($buSelectedArr) || in_array($buName, $buSelectedArr)) {
                    if ($s['MailboxName'] != 'mdelete' && $s['MailboxName'] != 'invalidsurvey') {
                        if (!isset($final[$buName]['total']))
                            $final[$buName]['total'] = 0;
                        if (!isset($final[$buName]['assign']))
                            $final[$buName]['assign'] = 0;
                        if (!isset($final[$buName]['fwdone']))
                            $final[$buName]['fwdone'] = 0;
                        if (!isset($final[$buName]['validation']))
                            $final[$buName]['validation'] = 0;
                        if (in_array($s['SurveyStatusName'], $filterArr[3])) {
                            $final[$buName]['validation']++;
                            $final[$buName]['fwdone']++;
                            $final[$buName]['assign']++;
                            $final[$buName]['total']++;
                        } else if (in_array($s['SurveyStatusName'], $filterArr[2])) {
                            $final[$buName]['fwdone']++;
                            $final[$buName]['assign']++;
                            $final[$buName]['total']++;
                        } else if (in_array($s['SurveyStatusName'], $filterArr[1])) {
                            $final[$buName]['assign']++;
                            $final[$buName]['total']++;
                        } else if (in_array($s['SurveyStatusName'], $filterArr[0])) {
                            $final[$buName]['total']++;
                        }
                        if (!isset($finalResult[$buName]['firstDate'])) {
                            $finalResult[$buName]['firstDate'] = 'none';
                        }
                        if ($finalResult[$buName]['firstDate'] == 'none' && (in_array($s['SurveyStatusName'], $filterArr[2]) || in_array($s['SurveyStatusName'], $filterArr[3]))) {
                            $finalResult[$buName]['firstDate'] = date('Y-m-d', strtotime($s['Date']));
                        } else if ($finalResult[$buName]['firstDate'] != 'none' && (in_array($s['SurveyStatusName'], $filterArr[2]) || in_array($s['SurveyStatusName'], $filterArr[3]))) {
                            $finalResult[$buName]['firstDate'] = (strtotime($finalResult[$buName]['firstDate']) > strtotime($s['Date']) || $finalResult[$buName]['firstDate'] == 'none') ? date('Y-m-d', strtotime($s['Date'])) : $finalResult[$buName]['firstDate'];
                        }
                        if (!isset($finalResult[$buName]['lastDate']) && ($final[$buName]['fwdone'] / $final[$buName]['total'] == 1)) {
                            $finalResult[$buName]['lastDate'] = date('Y-m-d', strtotime($s['Date']));
                        } else if ($final[$buName]['fwdone'] / $final[$buName]['total'] == 1) {
                            $finalResult[$buName]['lastDate'] = strtotime($finalResult[$buName]['lastDate']) < strtotime($s['Date']) ? date('Y-m-d', strtotime($s['Date'])) : $finalResult[$buName]['lastDate'];
                        } else if (!isset($finalResult[$buName]['lastDate']) || ($final[$buName]['fwdone'] / $final[$buName]['total'] != 1)) {
                            $finalResult[$buName]['lastDate'] = 'none';
                        }
                        $finalResult[$buName]['assignPercent'] = $final[$buName]['total'] != 0 ? floor(($final[$buName]['assign'] / $final[$buName]['total']) * 100) : 0;
                        $finalResult[$buName]['fwdonePercent'] = $final[$buName]['total'] != 0 ? floor(($final[$buName]['fwdone'] / $final[$buName]['total']) * 100) : 0;
                        $finalResult[$buName]['validationPercent'] = $final[$buName]['total'] != 0 ? floor(($final[$buName]['validation'] / $final[$buName]['total']) * 100) : 0;
                        $finalResult[$buName]['totlenum'] = $final[$buName]['total'];
                        $finalResult[$buName]['assignnum'] = $final[$buName]['assign'];
                        $finalResult[$buName]['fwdonenum'] = $final[$buName]['fwdone'];
                        $finalResult[$buName]['edit'] = $final[$buName]['validation'];
                        $finalResult[$buName]['id'] = $buId;
                    }
                }
            }
        } else {
            $finalResult = null;
        }
        return $finalResult;
    }

    protected function getAceBuPercent($cam, $buSelectedArr) {
        $filterArr = $this->filterArr();
        $final = array();
        $finalResult = array();
        foreach ($cam as $campaign) {
            $surveys = $campaign['aolsurvey'];
            foreach ($surveys as $s) {
                $buId = $s['location']['country']['bu']['id'];
                $buName = $s['location']['country']['bu']['name'];
                if (empty($buSelectedArr) || in_array($buName, $buSelectedArr)) {
                    if ($s['MailboxName'] != 'mdelete' && $s['MailboxName'] != 'invalidsurvey') {
                        if (!isset($final[$buName]['total']))
                            $final[$buName]['total'] = 0;
                        if (!isset($final[$buName]['assign']))
                            $final[$buName]['assign'] = 0;
                        if (!isset($final[$buName]['fwdone']))
                            $final[$buName]['fwdone'] = 0;
                        if (!isset($final[$buName]['validation']))
                            $final[$buName]['validation'] = 0;
                        if (in_array($s['SurveyStatusName'], $filterArr[3])) {
                            $final[$buName]['validation']++;
                            $final[$buName]['fwdone']++;
                            $final[$buName]['assign']++;
                            $final[$buName]['total']++;
                        } else if (in_array($s['SurveyStatusName'], $filterArr[2])) {
                            $final[$buName]['fwdone']++;
                            $final[$buName]['assign']++;
                            $final[$buName]['total']++;
                        } else if (in_array($s['SurveyStatusName'], $filterArr[1])) {
                            $final[$buName]['assign']++;
                            $final[$buName]['total']++;
                        } else if (in_array($s['SurveyStatusName'], $filterArr[0])) {
                            $final[$buName]['total']++;
                        }
                        if (!isset($finalResult[$buName]['firstDate']) && (in_array($s['SurveyStatusName'], $filterArr[2]) || in_array($s['SurveyStatusName'], $filterArr[3]))) {
                            $finalResult[$buName]['firstDate'] = date('Y-m-d', strtotime($s['Date']));
                        } else if (in_array($s['SurveyStatusName'], $filterArr[2]) || in_array($s['SurveyStatusName'], $filterArr[3])) {
                            $finalResult[$buName]['firstDate'] = (strtotime($finalResult[$buName]['firstDate']) > strtotime($s['Date']) || $finalResult[$buName]['firstDate'] == 'none') ? date('Y-m-d', strtotime($s['Date'])) : $finalResult[$buName]['firstDate'];
                        } else if (!isset($finalResult['firstDate'])) {
                            $finalResult[$buName]['firstDate'] = 'none';
                        }
                        if (!isset($finalResult[$buName]['lastDate']) && ($final[$buName]['fwdone'] / $final[$buName]['total'] == 1)) {
                            $finalResult[$buName]['lastDate'] = date('Y-m-d', strtotime($s['Date']));
                        } else if ($final[$buName]['fwdone'] / $final[$buName]['total'] == 1) {
                            $finalResult[$buName]['lastDate'] = strtotime($finalResult[$buName]['lastDate']) < strtotime($s['Date']) ? date('Y-m-d', strtotime($s['Date'])) : $finalResult[$buName]['lastDate'];
                        } else if (!isset($finalResult[$buName]['lastDate']) || ($final[$buName]['fwdone'] / $final[$buName]['total'] != 1)) {
                            $finalResult[$buName]['lastDate'] = 'none';
                        }
                        $finalResult[$buName]['assignPercent'] = $final[$buName]['total'] != 0 ? floor(($final[$buName]['assign'] / $final[$buName]['total']) * 100) : 0;
                        $finalResult[$buName]['fwdonePercent'] = $final[$buName]['total'] != 0 ? floor(($final[$buName]['fwdone'] / $final[$buName]['total']) * 100) : 0;
                        $finalResult[$buName]['validationPercent'] = $final[$buName]['total'] != 0 ? floor(($final[$buName]['validation'] / $final[$buName]['total']) * 100) : 0;
                        $finalResult[$buName]['totlenum'] = $final[$buName]['total'];
                        $finalResult[$buName]['assignnum'] = $final[$buName]['assign'];
                        $finalResult[$buName]['fwdonenum'] = $final[$buName]['fwdone'];
                        $finalResult[$buName]['edit'] = $final[$buName]['validation'];
                        $finalResult[$buName]['id'] = $buId;
                    }
                }
            }
        }
        if (!empty($surveys)) {
            
        } else {
            $finalResult = null;
        }
        return $finalResult;
    }

    protected function getStatusHtml($Arr, $id, $type, $aceArr = null) {
        if ($type == 'bu') {
            $return = '';
            foreach ($Arr as $k => $a) {
                if (isset($aceArr[$k])) {
                    $return .= '<tr class="bu-result-tr"><th style="padding:0;"><table style="border-collapse: collapse; height:100%; float:right;"><tr><th></th><td class="bu-name-td" onclick="getCountryResult(\'' . $id . '\', this, \'' . $a['id'] . '\')">' . $k . '</td></tr></table></th>' .
                            '<td class="result-date">' . $aceArr[$k]['fwsdate'] . '</td>' .
                            '<td class="result-date">' . $aceArr[$k]['fwedate'] . '</td>' .
                            '<td class="result-num">' . $a['totlenum'] . '</td>' .
                            '<td class="result-num">' . $a['assignPercent'] . '%(' . $a['assignnum'] . ')</td>' .
                            '<td class="result-num">' . $a['fwdonePercent'] . '%(' . $a['fwdonenum'] . ')</td>' .
                            '<td class="result-num">' . $a['validationPercent'] . '%(' . $a['edit'] . ')</td>' .
                            '<td class="result-date">' . $a['firstDate'] . '</td>' .
                            '<td class="result-date">' . $a['lastDate'] . '</td>' .
                            '<td class="result-date">' . $aceArr[$k]['reportdate'] . '</td></tr>';
                } else {
                    $return .= '<tr class="bu-result-tr"><th style="padding:0;"><table style="border-collapse: collapse; float:right;"><tr><th></th><td class="bu-name-td" onclick="getCountryResult(\'' . $id . '\', this, \'' . $a['id'] . '\')">' . $k . '</td></tr></table></th>' .
                            '<td class="result-date"></td>' .
                            '<td class="result-date"></td>' .
                            '<td class="result-num">' . $a['totlenum'] . '</td>' .
                            '<td class="result-num">' . $a['assignPercent'] . '%(' . $a['assignnum'] . ')</td>' .
                            '<td class="result-num">' . $a['fwdonePercent'] . '%(' . $a['fwdonenum'] . ')</td>' .
                            '<td class="result-num">' . $a['validationPercent'] . '%(' . $a['edit'] . ')</td>' .
                            '<td class="result-date">' . $a['firstDate'] . '</td>' .
                            '<td class="result-date">' . $a['lastDate'] . '</td>' .
                            '<td class="result-date"></td></tr>';
                }
            }
        } else if ($type == 'country') {
//            $return = '<tr class="show-country-result"><td colspan="10"><table class="c-result">';
            $return = '';
            foreach ($Arr as $k => $a) {
                $return .= '<tr class="c-result-tr"><th style="padding:0;"><table style="border-collapse: collapse; float:right;"><tr><th></th><td class="c-name-td">' . $k . '</td></tr></table></th>' .
                        '<td class="result-date italic-style">see by bu</td>' .
                        '<td class="result-date italic-style">see by bu</td>' .
                        '<td class="result-num">' . $a['totlenum'] . '</td>' .
                        '<td class="result-num">' . $a['assignPercent'] . '%(' . $a['assignnum'] . ')</td>' .
                        '<td class="result-num">' . $a['fwdonePercent'] . '%(' . $a['fwdonenum'] . ')</td>' .
                        '<td class="result-num">' . $a['validationPercent'] . '%(' . $a['edit'] . ')</td>' .
                        '<td class="result-date">' . $a['firstDate'] . '</td>' .
                        '<td class="result-date">' . $a['lastDate'] . '</td>' .
                        '<td class="result-date italic-style">see by bu</td></tr>';
            }
        }

        $return .= '</tr>';
        return $return;
    }

    public function getCountryResultAction() {
        $data = $this->getRequest()->getContent();
        $dataArr = json_decode($data, true);
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('cpn', 'q', 'aol', 'bu', 'cnty', 'loc')
                ->from('AlbatrossAceBundle:Campaign', 'cpn')
                ->leftJoin('cpn.questionnaire', 'q')
                ->leftJoin('cpn.aolsurvey', 'aol')
                ->leftJoin('aol.location', 'loc')
                ->leftJoin('loc.country', 'cnty')
                ->leftJoin('cnty.bu', 'bu')
                ->where('cpn.id = :cid')
                ->andWhere('bu.id = :bid');
        $qb->setParameter('cid', $dataArr['cid']);
        $qb->setParameter('bid', $dataArr['bid']);
        $query = $qb->getQuery();
        $result = $query->getArrayResult();
        if (empty($result)) {
            $percentArr = null;
        } else {
            $percentArr = $this->getCountryPercentAction($result[0]);
        }
        $final = $this->getStatusHtml($percentArr, $dataArr['cid'], 'country', null);
        return new Response($final);
    }

    public function getAceCountryResultAction() {
        $data = $this->getRequest()->getContent();
        $dataArr = json_decode($data, true);
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('cpn', 'q', 'aol', 'bu', 'cnty', 'loc')
                ->from('AlbatrossAceBundle:Campaign', 'cpn')
                ->leftJoin('cpn.questionnaire', 'q')
                ->leftJoin('cpn.customwave', 'cw')
                ->leftJoin('cw.project', 'p')
                ->leftJoin('cpn.aolsurvey', 'aol')
                ->leftJoin('aol.location', 'loc')
                ->leftJoin('loc.country', 'cnty')
                ->leftJoin('cnty.bu', 'bu')
                ->where('p.id = :pid')
                ->andWhere('bu.id = :bid');
        $qb->setParameter('pid', $dataArr['pid']);
        $qb->setParameter('bid', $dataArr['bid']);
        $query = $qb->getQuery();
        $result = $query->getArrayResult();
        if (empty($result)) {
            $percentArr = null;
        } else {
            $percentArr = $this->getCountryPercentAction($result[0]);
        }
        $final = $this->getAceStatusHtml($percentArr, $dataArr['pid'], 'country', null);
        return new Response($final);
    }

    protected function getCountryPercentAction($cam) {
        $filterArr = $this->filterArr();
        $final = array();
        $surveys = $cam['aolsurvey'];
        $finalResult = array();
        if (!empty($surveys)) {
            foreach ($surveys as $s) {
                if ($s['MailboxName'] != 'mdelete' && $s['MailboxName'] != 'invalidsurvey') {
                    $cId = $s['location']['country']['id'];
                    $cName = $s['location']['country']['name'];
                    if (!isset($final[$cName]['total']))
                        $final[$cName]['total'] = 0;
                    if (!isset($final[$cName]['assign']))
                        $final[$cName]['assign'] = 0;
                    if (!isset($final[$cName]['fwdone']))
                        $final[$cName]['fwdone'] = 0;
                    if (!isset($final[$cName]['validation']))
                        $final[$cName]['validation'] = 0;
                    if (in_array($s['SurveyStatusName'], $filterArr[3])) {
                        $final[$cName]['validation']++;
                        $final[$cName]['fwdone']++;
                        $final[$cName]['assign']++;
                        $final[$cName]['total']++;
                    } else if (in_array($s['SurveyStatusName'], $filterArr[2])) {
                        $final[$cName]['fwdone']++;
                        $final[$cName]['assign']++;
                        $final[$cName]['total']++;
                    } else if (in_array($s['SurveyStatusName'], $filterArr[1])) {
                        $final[$cName]['assign']++;
                        $final[$cName]['total']++;
                    } else if (in_array($s['SurveyStatusName'], $filterArr[0])) {
                        $final[$cName]['total']++;
                    }
                    if (!isset($finalResult[$cName]['firstDate']) && (in_array($s['SurveyStatusName'], $filterArr[2]) || in_array($s['SurveyStatusName'], $filterArr[3]))) {
                        $finalResult[$cName]['firstDate'] = date('Y-m-d', strtotime($s['Date']));
                    } else if (in_array($s['SurveyStatusName'], $filterArr[2]) || in_array($s['SurveyStatusName'], $filterArr[3])) {
                        $finalResult[$cName]['firstDate'] = (strtotime($finalResult[$cName]['firstDate']) > strtotime($s['Date']) || $finalResult[$cName]['firstDate'] == 'none') ? date('Y-m-d', strtotime($s['Date'])) : $finalResult[$cName]['firstDate'];
                    } else if (!isset($finalResult['firstDate'])) {
                        $finalResult[$cName]['firstDate'] = 'none';
                    }

                    if (!isset($finalResult[$cName]['lastDate'])) {
                        $finalResult[$cName]['lastDate'] = 'none';
                    }
                    if ($finalResult[$cName]['lastDate'] == 'none' &&
                            ($final[$cName]['fwdone'] / $final[$cName]['total'] == 1)) {
                        $finalResult[$cName]['lastDate'] = date('Y-m-d', strtotime($s['Date']));
                    } else if ($finalResult[$cName]['lastDate'] != 'none' &&
                            (strtotime($finalResult[$cName]['lastDate']) < strtotime($s['Date']))
                    ) {
                        $finalResult[$cName]['lastDate'] = date('Y-m-d', strtotime($s['Date']));
                    }

                    $finalResult[$cName]['assignPercent'] = $final[$cName]['total'] != 0 ? floor(($final[$cName]['assign'] / $final[$cName]['total']) * 100) : 0;
                    $finalResult[$cName]['fwdonePercent'] = $final[$cName]['total'] != 0 ? floor(($final[$cName]['fwdone'] / $final[$cName]['total']) * 100) : 0;
                    $finalResult[$cName]['validationPercent'] = $final[$cName]['total'] != 0 ? floor(($final[$cName]['validation'] / $final[$cName]['total']) * 100) : 0;
                    $finalResult[$cName]['totlenum'] = $final[$cName]['total'];
                    $finalResult[$cName]['assignnum'] = $final[$cName]['assign'];
                    $finalResult[$cName]['fwdonenum'] = $final[$cName]['fwdone'];
                    $finalResult[$cName]['edit'] = $final[$cName]['validation'];
                    $finalResult[$cName]['id'] = $cId;
                }
            }
        } else {
            $finalResult = null;
        }
        return $finalResult;
    }

    //==========================================================================
    //ace project status
    //==========================================================================
    protected function getAceprojectStatus($aceForm) {
        $data = $aceForm->getData();
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $aceNameEntityArr = $data['ace']->toArray();
        $aceIdArr = array();
        $aceNumberEntityArr = $data['acenumber']->toArray();
        $aceNumberArr = array();
        $buEntityArr = $data['bu']->toArray();
        $buArr = array();
        $countryEntityArr = $data['country']->toArray();
        $countryArr = array();
        $pmEntityArr = $data['pm']->toArray();
        $pmArr = array();
        $brandEntityArr = $data['brand']->toArray();
        $brandArr = array();
        $final = array();
        $qb->select('opproj', 'proj')
                ->from('AlbatrossOperationBundle:OperationProject', 'opproj')
                ->leftJoin('opproj.bu', 'bu')
                ->leftJoin('opproj.customclient', 'client')
                ->leftJoin('opproj.country', 'country')
                ->leftJoin('opproj.project', 'proj')
                ->leftJoin('proj.tasks', 'task')
                ->where('proj.percent < 100');
        if (!empty($aceNameEntityArr)) {
            foreach ($aceNameEntityArr as $aceEntity) {
                $aceIdArr[] = $aceEntity->getId();
            }
            $qb->andWhere('proj.id IN (:projArr)');
            $qb->setParameter('projArr', $aceIdArr);
        }
        if (!empty($aceNumberEntityArr)) {
            foreach ($aceNumberEntityArr as $acenumEntity) {
                $aceNumberArr[] = $acenumEntity->getId();
            }
            $qb->andWhere('task.number > 100 AND task.number < 117 AND task.projectnumber IN (:projNumArr)');
            $qb->setParameter('projNumArr', $aceNumberArr);
        }
        if (!empty($buEntityArr)) {
            $buNameArr = array();
            foreach ($buEntityArr as $bu) {
                $buArr[] = $bu->getId();
                $buNameArr[] = $bu->getName();
            };
            $qb->andWhere('bu.id IN (:buArr)');
            $qb->setParameter('buArr', $buArr);
        } else {
            $buNameArr = '';
        }
        if (!empty($countryEntityArr)) {
            foreach ($countryEntityArr as $country) {
                $countryArr[] = $country->getId();
            }
            $qb->andWhere('country.id IN (:countryArr)');
            $qb->setParameter('countryArr', $countryArr);
        }
        if (!empty($pmEntityArr)) {
            foreach ($pmEntityArr as $pm) {
                $pmArr[] = $pm->getUsername();
            }
            $qb->andWhere('opproj.pm IN (:pmArr)');
            $qb->setParameter('pmArr', $pmArr);
        }
        if (!empty($brandEntityArr)) {
            foreach ($brandEntityArr as $brand) {
                $brandArr[] = $brand->getId();
            }
            $qb->andWhere('client.id IN (:brandArr)');
            $qb->setParameter('brandArr', $brandArr);
        }
        //date filter part
        if ($data['fw_s_f'] != null) {
            $qb->andWhere('opproj.fwsdate >= :fwsf');
            $qb->andWhere("opproj.fwsdate != 'none'");
            $qb->setParameter('fwsf', $data['fw_s_f']);
        }
        if ($data['fw_s_t'] != null) {
            $qb->andWhere('opproj.fwsdate <= :fwst');
            $qb->andWhere("opproj.fwsdate != 'none'");
            $qb->setParameter('fwst', $data['fw_s_t']);
        }
        if ($data['fw_e_f'] != null) {
            $qb->andWhere('opproj.fwedate >= :fwef');
            $qb->andWhere("opproj.fwedate != 'none'");
            $qb->setParameter('fwef', $data['fw_e_f']);
        }
        if ($data['fw_e_t'] != null) {
            $qb->andWhere('opproj.fwedate <= :fwet');
            $qb->andWhere("opproj.fwedate != 'none'");
            $qb->setParameter('fwet', $data['fw_e_t']);
        }
        if ($data['due_f'] != null) {
            $qb->andWhere('opproj.reportdate >= :duef');
            $qb->andWhere("opproj.reportdate != 'none'");
            $qb->setParameter('duef', $data['due_f']);
        }
        if ($data['due_t'] != null) {
            $qb->andWhere('opproj.reportdate <= :duet');
            $qb->andWhere("opproj.reportdate != 'none'");
            $qb->setParameter('duet', $data['due_t']);
        }
        $query = $qb->getQuery();
        $result = $query->getArrayResult();
        foreach ($result as $r) {
            $projId = $r['project']['id'];
            $final[$projId]['firstDate'] = $r['first_visit_date'];
            $final[$projId]['lastDate'] = $r['last_visit_date'];
            $final[$projId]['aceprojectname'] = $r['project']['name'];
            $final[$projId]['num'] = $r['survey_num'];
            $final[$projId]['fwsdate'] = $r['fwsdate'];
            $final[$projId]['fwedate'] = $r['fwedate'];
            $final[$projId]['reportdate'] = $r['reportdate'];
            $final[$projId]['assigned'] = $r['survey_num'] != 0 ? floor(($r['assigned_num'] / $r['survey_num']) * 100) : 0;
            $final[$projId]['done'] = $r['survey_num'] != 0 ? floor(($r['fw_num'] / $r['survey_num']) * 100) : 0;
            $final[$projId]['validationPercent'] = $r['survey_num'] != 0 ? floor(($r['editing_num'] / $r['survey_num']) * 100) : 0;
            $final[$projId]['assignnum'] = $r['assigned_num'];
            $final[$projId]['fwdonenum'] = $r['fw_num'];
            $final[$projId]['edit'] = $r['editing_num'];

            if ($data['assign_f'] != null && (float) $data['assign_f'] > $final[$projId]['assigned']) {
                unset($final[$projId]);
            }
            if ($data['assign_t'] != null && (float) $data['assign_t'] < $final[$r['id']]['assigned']) {
                unset($final[$r['id']]);
            }
            if ($data['fw_done_f'] != null && (float) $data['fw_done_f'] > $final[$r['id']]['done']) {
                unset($final[$r['id']]);
            }
            if ($data['fw_done_t'] != null && (float) $data['fw_done_t'] < $final[$r['id']]['done']) {
                unset($final[$r['id']]);
            }
            if ($data['editing_done_f'] != null && (float) $data['editing_done_f'] > $final[$r['id']]['validationPercent']) {
                unset($final[$r['id']]);
            }
            if ($data['editing_done_t'] != null && (float) $data['editing_done_t'] < $final[$r['id']]['validationPercent']) {
                unset($final[$r['id']]);
            }
        }
        $result_merge['result'] = $final;
        $result_merge['buArr'] = $buNameArr;
        return $result_merge;
    }

    protected function getAcePercent($waveEntity) {
        $final = array();
        $filterArr = $this->filterArr();
        $newFilterArr0 = array_flip($filterArr[0]);
        $newFilterArr1 = array_flip($filterArr[1]);
        $newFilterArr2 = array_flip($filterArr[2]);
        $newFilterArr3 = array_flip($filterArr[3]);
        $finalResult = array();
        foreach ($waveEntity->getCampaign()->toArray() as $cam) {
            $surveys = $cam->getAolsurvey()->toArray();
            if (!empty($surveys)) {
                foreach ($surveys as $s) {
                    if ($s->getMailboxName() != 'mdelete' && $s->getMailboxName() != 'invalidsurvey') {
                        $finalResult['lastDate'] = 'none';
                        $finalResult['firstDate'] = 'none';
                        if (!isset($final['total']))
                            $final['total'] = 0;
                        if (!isset($final['assign']))
                            $final['assign'] = 0;
                        if (!isset($final['fwdone']))
                            $final['fwdone'] = 0;
                        if (!isset($final['validation']))
                            $final['validation'] = 0;
                        if (isset($newFilterArr3[$s->getSurveyStatusName()])) {
                            $final['validation']++;
                            $final['fwdone']++;
                            $final['assign']++;
                            $final['total']++;
                        } else if (isset($newFilterArr2[$s->getSurveyStatusName()])) {
                            $final['fwdone']++;
                            $final['assign']++;
                            $final['total']++;
                        } else if (isset($newFilterArr1[$s->getSurveyStatusName()])) {
                            $final['assign']++;
                            $final['total']++;
                        } else if (isset($newFilterArr0[$s->getSurveyStatusName()])) {
                            $final['total']++;
                        }
                        if (!isset($finalResult['firstDate']) && (isset($newFilterArr2[$s->getSurveyStatusName()]) || isset($newFilterArr3[$s->getSurveyStatusName()]))) {
                            $finalResult['firstDate'] = date('Y-m-d', strtotime($s->getDate()));
                        } else if (isset($newFilterArr2[$s->getSurveyStatusName()]) || isset($newFilterArr3[$s->getSurveyStatusName()])) {
                            $finalResult['firstDate'] = (strtotime($finalResult['firstDate']) > strtotime($s->getDate())) ? date('Y-m-d', strtotime($s->getDate())) : $finalResult['firstDate'];
                        } else if (!isset($finalResult['firstDate'])) {
                            $finalResult['firstDate'] = 'none';
                        }

                        if ($finalResult['lastDate'] == 'none' && $final['fwdone'] / $final['total'] == 1) {
                            $finalResult['lastDate'] = date('Y-m-d', strtotime($s->getDate()));
                        } else if ($finalResult['lastDate'] != 'none' &&
                                $final['fwdone'] / $final['total'] == 1 &&
                                strtotime($finalResult['lastDate']) < strtotime($s->getDate())) {
                            $finalResult['lastDate'] = date('Y-m-d', strtotime($s->getDate()));
                        }

                        if ($s->getLocation()->getCountry()) {
                            $countryArr[$s->getLocation()->getCountry()->getId()] = $s->getLocation()->getCountry()->getName();
                        }
                    }
                }
            }
        }

        if (isset($final['total'])) {
            $finalResult['assignPercent'] = floor(($final['assign'] / $final['total']) * 100);
            $finalResult['fwdonePercent'] = floor(($final['fwdone'] / $final['total']) * 100);
            $finalResult['validationPercent'] = floor(($final['validation'] / $final['total']) * 100);
            $finalResult['totlenum'] = $final['total'];
            $finalResult['assignnum'] = $final['assign'];
            $finalResult['fwdonenum'] = $final['fwdone'];
            $finalResult['edit'] = $final['validation'];
            $finalResult['country'] = $countryArr;
        }
        return $finalResult;
    }

    public function getAceBuResultAction() {
        $ajaxData = $this->getRequest()->getContent();
        $ajaxDataArr = json_decode($ajaxData, true);
        $id = $ajaxDataArr['id'];
        $buSelectedArr = array();
        foreach ($ajaxDataArr as $k => $arr) {
            if ((string) $k != 'bu' && (string) $k != 'id') {
                $buSelectedArr[$k] = $arr;
            }
        }
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('cpn', 'q', 'aol', 'bu', 'cnty', 'loc')
                ->from('AlbatrossAceBundle:Campaign', 'cpn')
                ->leftJoin('cpn.customwave', 'cw')
                ->leftJoin('cw.project', 'proj')
                ->leftJoin('cpn.questionnaire', 'q')
                ->leftJoin('cpn.aolsurvey', 'aol')
                ->leftJoin('aol.location', 'loc')
                ->leftJoin('loc.country', 'cnty')
                ->leftJoin('cnty.bu', 'bu')
                ->where('proj.id = :pid');
        $qb->setParameter('pid', $id);
        $query = $qb->getQuery();
        $result = $query->getArrayResult();
        if (!empty($result)) {
            $percentArr = $this->getAceBuPercent($result, $buSelectedArr);
        } else {
            $percentArr = null;
        }

        $qb2 = $em->createQueryBuilder();
        $qb2->select('cw', 'proj', 'task', 'iof', 'info', 'bu', 'forecast')
                ->from('AlbatrossAceBundle:Project', 'proj')
                ->leftJoin('proj.customwave', 'cw')
                ->leftJoin('cw.attachments', 'iof')
                ->leftJoin('iof.attachinfo', 'info')
                ->leftJoin('info.bu', 'bu')
                ->leftJoin('proj.tasks', 'task')
                ->leftJoin('task.forecast', 'forecast')
                ->where('proj.id = :pid')
                ->andWhere('(task.number BETWEEN 101 AND 116) OR task.number = 600');
        $qb2->setParameter('pid', $id);
        $query2 = $qb2->getQuery();
        $result2 = $query2->getArrayResult();
        $buArr = $this->getBuArr();
        $aceResult = array();
        if (!empty($result2)) {
            if (!isset($result2[0]['customwave']['attachments']) || $result2[0]['customwave']['attachments'] == null) {
                $prjArr = array();
                $pid = $result2[0]['id'];
                foreach ($result2[0]['tasks'] as $t) {
                    if ($t['number'] == 600) { // from task
                        if ($t['reportduedate'] != null) {
                            $prjArr[$pid] = $t['reportduedate'];
                        } else {
                            $prjArr[$pid] = 'no date';
                        }
                    }
                }

                foreach ($result2[0]['tasks'] as $t) {
                    if ($t['number'] > 100 && $t['number'] < 117 && !empty($t['forecast'])) { // from pm
                        $for = array();
                        foreach ($t['forecast'] as $k => $forc) {
                            if ($k == 0) {
                                $for = $forc;
                            } else {
                                if ($for['edittime'] < $forc['edittime']) {
                                    $for = $forc;
                                }
                            }
                        }
                        if (!isset($aceResult[$buArr[$t['number'] - 100]])) {
                            $aceResult[$buArr[$t['number'] - 100]]['fwsdate'] = $for['fwstartdate']->format('Y-m-d') . '(PM)';
                            $aceResult[$buArr[$t['number'] - 100]]['fwedate'] = $for['fwenddate']->format('Y-m-d') . '(PM)';
                            $aceResult[$buArr[$t['number'] - 100]]['reportdate'] = $for['reportduedate']->format('Y-m-d') . '(PM)';
                            $aceResult[$buArr[$t['number'] - 100]]['scope'] = $for['scope'] . '(PM)';
                        } else {
                            $aceResult[$buArr[$t['number'] - 100]]['fwsdate'] .= '<br/>' . $for['fwstartdate']->format('Y-m-d') . '(PM)';
                            $aceResult[$buArr[$t['number'] - 100]]['fwedate'] .= '<br/>' . $for['fwenddate']->format('Y-m-d') . '(PM)';
                            $aceResult[$buArr[$t['number'] - 100]]['reportdate'] .= '<br/>' . $for['reportduedate']->format('Y-m-d') . '(PM)';
                            $aceResult[$buArr[$t['number'] - 100]]['scope'] .= '<br/>' . $for['scope'] . '(PM)';
                        }
                    } else if ($t['number'] > 100 && $t['number'] < 117) { // from task
                        if (!isset($aceResult[$buArr[$t['number'] - 100]])) {
                            $aceResult[$buArr[$t['number'] - 100]]['fwsdate'] = $t['fwstartdate'] . '(ACE)';
                            $aceResult[$buArr[$t['number'] - 100]]['fwedate'] = $t['fwenddate'] . '(ACE)';
                            $aceResult[$buArr[$t['number'] - 100]]['reportdate'] = $prjArr[$pid] . '(ACE)';
                            $aceResult[$buArr[$t['number'] - 100]]['scope'] = $t['scope'] . '(ACE)';
                        } else {
                            $aceResult[$buArr[$t['number'] - 100]]['fwsdate'] .= '<br/>' . $t['fwstartdate']->format('Y-m-d') . '(ACE)';
                            $aceResult[$buArr[$t['number'] - 100]]['fwedate'] .= '<br/>' . $t['fwenddate']->format('Y-m-d') . '(ACE)';
                            $aceResult[$buArr[$t['number'] - 100]]['reportdate'] .= '<br/>' . $prjArr[$pid] . '(ACE)';
                            $aceResult[$buArr[$t['number'] - 100]]['scope'] .= '<br/>' . $t['scope'] . '(ACE)';
                        }
                    }
                }
            } else {    // from iof
                foreach ($result2[0]['customwave']['attachments']['attachinfo'] as $attachinfo) {
                    if (!isset($aceResult[$attachinfo['bu']['name']])) {
                        $aceResult[$attachinfo['bu']['name']]['fwsdate'] = $attachinfo['fwstartdate']->format('Y-m-d') . '(IOF)';
                        $aceResult[$attachinfo['bu']['name']]['fwedate'] = $attachinfo['fwenddate']->format('Y-m-d') . '(IOF)';
                        $aceResult[$attachinfo['bu']['name']]['reportdate'] = $attachinfo['reportduedate']->format('Y-m-d') . '(IOF)';
                        $aceResult[$attachinfo['bu']['name']]['scope'] = $attachinfo['scope'] . '(IOF)';
                    } else {
                        $aceResult[$attachinfo['bu']['name']]['fwsdate'] .= '<br/>' . $attachinfo['fwstartdate']->format('Y-m-d') . '(IOF)';
                        $aceResult[$attachinfo['bu']['name']]['fwedate'] .= '<br/>' . $attachinfo['fwenddate']->format('Y-m-d') . '(IOF)';
                        $aceResult[$attachinfo['bu']['name']]['reportdate'] .= '<br/>' . $attachinfo['reportduedate']->format('Y-m-d') . '(IOF)';
                        $aceResult[$attachinfo['bu']['name']]['scope'] .= '<br/>' . $attachinfo['scope'] . '(IOF)';
                    }
                }
            }
        }

        $final = $this->getAceStatusHtml($percentArr, $id, 'bu', $aceResult);
        return new Response($final);
    }

    protected function getAceStatusHtml($Arr = null, $id, $type, $aceArr) {
        if ($type == 'bu') {
            $return = '';
            foreach ($aceArr as $k => $a) {
                if (isset($Arr[$k])) {
                    $return .= '<tr class="bu-result-tr"><th style="padding:0;"><table style="border-collapse: collapse; height:100%; float:right;"><tr><th></th><td class="bu-name-td" onclick="getAceCountryResult(\'' . $id . '\', this, \'' . $Arr[$k]['id'] . '\')">' . $k . '</td></tr></table></th>' .
                            '<td class="result-date">' . $a['fwsdate'] . '</td>' .
                            '<td class="result-date">' . $a['fwedate'] . '</td>' .
                            '<td class="result-num">' . $Arr[$k]['totlenum'] . '</td>' .
                            '<td class="result-num">' . $Arr[$k]['assignPercent'] . '%(' . $Arr[$k]['assignnum'] . ')</td>' .
                            '<td class="result-num">' . $Arr[$k]['fwdonePercent'] . '%(' . $Arr[$k]['fwdonenum'] . ')</td>' .
                            '<td class="result-num">' . $Arr[$k]['validationPercent'] . '%(' . $Arr[$k]['edit'] . ')</td>' .
                            '<td class="result-date">' . $Arr[$k]['firstDate'] . '</td>' .
                            '<td class="result-date">' . $Arr[$k]['lastDate'] . '</td>' .
                            '<td class="result-date">' . $a['reportdate'] . '</td></tr>';
                } else {
                    $return .= '<tr class="bu-result-tr"><th style="padding:0;"><table style="border-collapse: collapse; height:100%; float:right;"><tr><th></th><td class="bu-name-td">' . $k . '</td></tr></table></th>' .
                            '<td class="result-date">' . $aceArr[$k]['fwsdate'] . '</td>' .
                            '<td class="result-date">' . $aceArr[$k]['fwedate'] . '</td>' .
                            '<td class="result-num">' . $aceArr[$k]['scope'] . '</td>' .
                            '<td class="result-num"></td>' .
                            '<td class="result-num"></td>' .
                            '<td class="result-num"></td>' .
                            '<td class="result-date"></td>' .
                            '<td class="result-date"></td>' .
                            '<td class="result-date">' . $aceArr[$k]['reportdate'] . '</td></tr>';
                }
            }
        } else if ($type == 'country') {
//            $return = '<tr class="show-country-result"><td colspan="10"><table class="c-result">';
            $return = '';
            foreach ($Arr as $k => $a) {
                $return .= '<tr class="c-result-tr"><th style="padding:0;"><table style="border-collapse: collapse; float:right;"><tr><th></th><td class="c-name-td">' . $k . '</td></tr></table></th>' .
                        '<td class="result-date italic-style">see by bu</td>' .
                        '<td class="result-date italic-style">see by bu</td>' .
                        '<td class="result-num">' . $a['totlenum'] . '</td>' .
                        '<td class="result-num">' . $a['assignPercent'] . '%(' . $a['assignnum'] . ')</td>' .
                        '<td class="result-num">' . $a['fwdonePercent'] . '%(' . $a['fwdonenum'] . ')</td>' .
                        '<td class="result-num">' . $a['validationPercent'] . '%(' . $a['edit'] . ')</td>' .
                        '<td class="result-date">' . $a['firstDate'] . '</td>' .
                        '<td class="result-date">' . $a['lastDate'] . '</td>' .
                        '<td class="result-date italic-style">see by bu</td></tr>';
            }
        }

        $return .= '</table></td></tr>';
        return $return;
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

    protected function getBuNumberIdArr() {
        $em = $this->getDoctrine()->getManager();
        $bu = $em->getRepository('AlbatrossAceBundle:Bu')->findAll();
        $buArr = array();
        foreach ($bu as $b) {
            $buArr[$b->getNumber()] = $b->getId();
        }
        return $buArr;
    }

}
