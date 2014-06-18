<?php

namespace Albatross\DailyBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Albatross\DailyBundle\Entity\Client;
use Albatross\DailyBundle\Form\ClientType;
use Albatross\DailyBundle\Entity\Date;
use Albatross\DailyBundle\Entity\Number;
use Albatross\DailyBundle\Entity\Rules;
use Albatross\DailyBundle\Entity\Survey;
use \SplFileObject;

/**
 * Client controller.
 *
 */
class ClientController extends Controller {

    /**
     * Lists all Client entities.
     *
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AlbatrossDailyBundle:Client')->findAll();

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $entities, $this->get('request')->query->get('page', 1), 50/* page number */
        );

        return $this->render('AlbatrossDailyBundle:Client:index.html.twig', array(
                    'entities' => $pagination,
                    'menu_bar' => 'admin',
                    'menu_cal_cur' => 'client'
        ));
    }

    /**
     * Creates a new Client entity.
     *
     */
    public function createAction(Request $request) {
        $entity = new Client();
        $form = $this->createForm(new ClientType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('client_show', array('id' => $entity->getId())));
        }

        return $this->render('AlbatrossDailyBundle:Client:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to create a new Client entity.
     *
     */
    public function newAction() {
        $entity = new Client();
        $form = $this->createForm(new ClientType(), $entity);

        return $this->render('AlbatrossDailyBundle:Client:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Client entity.
     *
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AlbatrossDailyBundle:Client')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Client entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('AlbatrossDailyBundle:Client:show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Displays a form to edit an existing Client entity.
     *
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AlbatrossDailyBundle:Client')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Client entity.');
        }

        $editForm = $this->createForm(new ClientType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('AlbatrossDailyBundle:Client:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Client entity.
     *
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AlbatrossDailyBundle:Client')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Client entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ClientType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('client_edit', array('id' => $id)));
        }

        return $this->render('AlbatrossDailyBundle:Client:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Client entity.
     *
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AlbatrossDailyBundle:Client')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Client entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('client'));
    }

    /**
     * Creates a form to delete a Client entity by id.
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

    //read client from aol and save the client name to database
    public function syncClientListAction() {
        $baseUrl = 'https://www.albatrossonline.com/open/data.asp?post={"action":"exec","dataset":{"datasetname":"/Apps/SM/DB/MystClients"},"parameters":[]}';
        $url = $this->getUrl($baseUrl);

        $fileurl = file_get_contents($url);

        $fileurl = str_replace("	", "", $fileurl);
        $res = json_decode($fileurl);

        if (isset($res->dataset->data))
            $data = $res->dataset->data;
        else
            $data = null;

        $clientList = array();

        if ($data != null) {
            foreach ($data as $d) {
                $clientList[$d->MystClientID] = $d->MystClientName;
            }
        }


        $em = $this->getDoctrine()->getEntityManager();
        foreach ($clientList as $key => $cn) {
            if (!$client = $em->getRepository('AlbatrossDailyBundle:Client')->findOneByAolId($key)) {
                $client = new Client();
                $client->setAolId($key);
                $client->setClientName($cn);
            }
            $em->persist($client);
        }
        $em->flush();
        $dbClients = $em->getRepository('AlbatrossDailyBundle:Client')->findAll();
        foreach ($dbClients as $dc) {
            if (!isset($clientList[$dc->getAolId()])) {
                $em->remove($dc);
            }
        }
        $em->flush();

        return $this->redirect($this->generateUrl('client'));
    }

    public function getSurvayByClientAction() {

        $em = $this->getDoctrine()->getEntityManager();
        $client = $em->getRepository('AlbatrossDailyBundle:Client')->findAll();

        $content = $this->getRequest()->getContent();

        $data = explode('=', $content);
        $key = (int) $content;

        $clientAolId = array();
        $per_get_num = 50;

        $need_times = ceil(count($client) / $per_get_num);
        $count = 0;

        $client_current = array_chunk($client, $per_get_num);
        foreach ($client_current[$key] as $k => $c) {
            if ($count == $per_get_num) {
                $count = 0;
            }
            if (($k % $per_get_num) == 0)
                $clientAolId = '-' . $c->getAolId();
            else
                $clientAolId .= ',-' . $c->getAolId();

            $count++;
        }
        $result = array();
        $baseUrl = 'https://www.albatrossonline.com/open/data.asp?post={"action":"exec","dataset":{"datasetname":"/Apps/SM/Analysis/AnalysisCustomRollups"},"parameters":[{"name":"QuerySpecification","value":"[ProtoSurveyID][Title][MystClientID]"},{"name":"ProtoSurveysAndClientIDsList","value":"' . $clientAolId . '"}]}';

        $url = $this->getUrl($baseUrl);

        $survey = file_get_contents($url);
        $fileurl = str_replace("	", "", $survey);
        $res = json_decode($fileurl);

        if (isset($res->dataset->data))
            $data = $res->dataset->data;
        else
            $data = null;

        foreach ($data[0] as $d) {
            $result[$d->Col001]['name'] = $d->Col002;
            $result[$d->Col001]['clientid'] = $d->Col003;
        }
        $this->saveSurvey($result);
        $prg_pct = ceil(748 * ($key / $need_times));
        $key++;
        if ($key < $need_times) {
            $result = '{"key":"' . $key . '","percent":"' . $prg_pct . '"}';
        } else {
            $result = '{"key":"stop","percent":"' . $prg_pct . '"}';
        }
        return new Response($result);
    }

    public function saveSurvey($result) {
        $em = $this->getDoctrine()->getEntityManager();
        foreach ($result as $k => $r) {
            if (!$survey = $em->getRepository('AlbatrossDailyBundle:Survey')->findByAolId($k)) {
                $survey = new Survey();
                $survey->setAolId($k);
                $client = $em->getRepository('AlbatrossDailyBundle:Client')->findOneByAolId($r['clientid']);
                $survey->setClient($client);
                $survey->setSurveyName($r['name']);
                $em->persist($survey);
            }
        }
        $em->flush();

        return;
    }

    private function getUrl($baseUrl) {
        $identityAlias = "&IdentityAlias=" . $this->container->getParameter('identity_alias');
        $baseUrl = str_replace(" ", "+", $baseUrl);
        $url = $baseUrl . $identityAlias;

        return $url;
    }

    public function readDailyExcelAction($date = null) {
        $status = $this->getStatus(); //object status. status list
        
        $referer = $this->getRequest()->headers->get('referer');
        
        if ($date == null) {
            $dirdate = date('ymd'); //read current day directory
            $date = date('Y-m-d', $date);
        } else {
            $dirdate = date('ymd', strtotime($date));
        }
        $targetDir = $this->getDailyCheckDir($dirdate); // get directory

        $buStructure = $this->getBu();
        $rules = $this->getRules();

        foreach ($status as $s) {
            if ($s->getToday() == true && $s->getEditable() == false) { //save to current day or the day before current day
                $today = 1;
            } else {
                $today = 0;
            }
            $fileName = $s->getStatus() . '.csv';
            foreach (scandir($targetDir) as $fileList) {
                //check the status name and file name
                similar_text($fileList, $fileName, $percent);
                if ($percent > 95) {
                    $countNum = $this->parseExcel($fileList, $targetDir, $buStructure, $rules, $s->getId());
                    $this->saveNumber($countNum, $date, $s, $today);
                }
            }
        }
        return $this->redirect($referer);
    }

    private function saveNumber($countNum, $date, $status, $today) {
        $em = $this->getDoctrine()->getEntityManager();

        if ($today == 1) {
            $date = date('Y-m-d', strtotime($date));
        }

        $date = new \DateTime($date);

        foreach ($countNum as $buKey => $bn) {
            if ($buKey == 'max')
                $buKey = null;
            //check if the date is exist, if not creat new date
            if (!($numdate = $em->getRepository('AlbatrossDailyBundle:Date')->findOneByDailydateAndBu($date, $buKey))) {
                $numdate = new Date();
                $numdate->setDailydate($date);
                if ($buKey == null) {
                    $numdate->setBu(null);
                } else {
                    $newdateBu = $em->getRepository('AlbatrossAceBundle:Bu')->findOneById($buKey);
                    $numdate->setBu($newdateBu);
                }
                $em->persist($numdate);
                $em->flush();
            }

            //add previous forecast
            if ($numdate->getForecast() == null || $numdate->getForecast() == 0) {
                if (($last = $em->getRepository('AlbatrossDailyBundle:Date')->findLastDateWithForecastByBu($buKey))) {
                    $numdate->setForecast($last->getForecast());
                    $em->persist($numdate);
                    $em->flush();
                }
            }

            //check if the number is exist, if not creat new
            if (!($newnum = $em->getRepository('AlbatrossDailyBundle:Number')->findOneByDateAndStatus($numdate->getId(), $status->getId()))) {
                $newnum = new Number();
                $newnum->setDate($numdate);
                $newnum->setStatus($status);
            }
            if ($buKey == null)
                $newnum->setNumber($bn);
            else
                $newnum->setNumber($bn['all']);
            $em->persist($newnum);
        }
        $em->flush();
        return;
    }

    private function parseExcel($file, $targetDir, $buStructure, $rules, $statusId) {

        $csv = new SplFileObject($targetDir . $file);
        while (!$csv->eof()) {
            $excelArr[] = $csv->fgetcsv();
        }

        foreach ($excelArr[0] as $k => $name) {
            switch ($name) {
                case 'LocCountryCode':
                    $countryCodeKey = $k;
                    break;
                case 'Client':
                    $clientKey = $k;
                    break;
                case 'LocState_Region':
                    $regionKey = $k;
                    break;
                case 'LocCity':
                    $cityCodeKey = $k;
                    break;
                case 'PayrollCurr':
                    $currKey = $k;
                    break;
                case 'Survey':
                    $surveyKey = $k;
                    break;
                case 'BillingRate':
                    $billingRate = $k;
                    break;
            }
        }
        unset($excelArr[0]);

        $result = array();
        $null = 0;
        foreach ($buStructure as $key => $bs) {
            foreach ($excelArr as $earr) {
                if (!isset($currKey)) {
                    $currKey = -1;
                }
                if (!isset($billingRate)) {
                    $billingRate = -1;
                }
                if (!isset($earr[$currKey]))
                    $earr[$currKey] = null;
                if (!isset($earr[$billingRate]))
                    $earr[$billingRate] = null;
                if (isset($earr[$countryCodeKey])) {
                    if (in_array($earr[$countryCodeKey], $bs)) {
                        if (($new_bu = $this->checkRules($rules, $key, $earr[$clientKey], $statusId, $earr[$regionKey], $earr[$currKey], true, $earr[$countryCodeKey], $earr[$cityCodeKey], $earr[$surveyKey], $earr[$billingRate]))) { //check Rules for current conditions
                            if (isset($result['max']))
                                $result['max']++;
                            else
                                $result['max'] = 1;

                            if (isset($result[$new_bu]['all']))
                                $result[$new_bu]['all']++;
                            else
                                $result[$new_bu]['all'] = 1;

                            $result[$new_bu]['name'] = $earr[$countryCodeKey] . ':' . $earr[$clientKey] . ':' . $statusId;
                        }

                        if (($new_bu = $this->checkRules($rules, $key, $earr[$clientKey], $statusId, $earr[$regionKey], $earr[$currKey], false, $earr[$countryCodeKey], $earr[$cityCodeKey], $earr[$surveyKey], $earr[$billingRate]))) { //check Rules for current conditions
                            if (isset($result['max']))
                                $result['max']++;
                            else
                                $result['max'] = 1;

                            if (isset($result[$new_bu]['all']))
                                $result[$new_bu]['all']++;
                            else
                                $result[$new_bu]['all'] = 1;

                            $result[$new_bu]['name'] = $earr[$countryCodeKey] . ':' . $earr[$clientKey] . ':' . $statusId;
                        }
                    }
                }
            }
        }

        return $result;
    }

    private function getDailyCheckDir($date) {
        //Set file directory
        $dir = 'aolExport2/';

        $targetDir = $dir . $date . '/';

        return $targetDir;
    }

    private function getStatus() {
        $em = $this->getDoctrine()->getEntityManager();
        $status = $em->getRepository('AlbatrossDailyBundle:Status')->findBy(array('editable' => '0'));

        return $status;
    }

    private function getBu() {
        $em = $this->getDoctrine()->getEntityManager();
        $bu = $em->getRepository('AlbatrossAceBundle:Bu')->findAll();
        $result = array();
        foreach ($bu as $b) {
            $country = $em->getRepository('AlbatrossAceBundle:Country')->findByBu($b);
            foreach ($country as $c) {
                $result[$b->getId()][$c->getId()] = $c->getCode();
            }
        }
        return $result;
    }

    private function getRules() {
        //get rules
        $em = $this->getDoctrine()->getEntityManager();
        $rules = $em->getRepository('AlbatrossDailyBundle:Rules')->findAll();
        if (!empty($rules)) {
            foreach ($rules as $k => $r) {
                //get bu rule
                if ($r->getBu() != null)
                    $result[$k]['bu'] = $r->getBu()->getId();
                else
                    $result[$k]['bu'] = null;

                //get status rule
                $result[$k]['status'] = null;
                foreach ($r->getStatus() as $r_sts) {
                    $result[$k]['status'][] = $r_sts->getId();
                }


                //get client rule
                $result[$k]['clients'] = null;
                foreach ($r->getClients() as $r_clt) {
                    $result[$k]['clients'][] = $r_clt->getClientName();
                }

                //get client rule
                $result[$k]['countries'] = null;
                foreach ($r->getCountries() as $r_ctr) {
                    $result[$k]['countries'][] = $r_ctr->getCode();
                }

                $result[$k]['region'] = $r->getRegion();
                $result[$k]['city'] = $r->getCity();
                $result[$k]['payrollCurr'] = $r->getPayrollCurr();

                $result[$k]['survey'] = null;
                if ($r->getSurveyKeyword())
                    $result[$k]['survey'] = explode(';', $r->getSurveyKeyword());

                //get exclude rule
                $result[$k]['exclude'] = $r->getExclude();
                $result[$k]['billingRate'] = $r->getBillingRate();
            }
        } else {
            $result = null;
        }

        return $result;
    }

    private function checkRules($rules, $buId, $clientName, $statusId, $region, $curr, $excl, $country, $city, $survey, $billingRate) {

        if ($rules == null) {
            return $buId;
        } else {
            foreach ($rules as $r) {
                if (!$r['status'] || in_array($statusId, $r['status'])) {
                    if ($excl == true && $r['exclude'] == true) {
                        if (!$r['clients'] || in_array($clientName, $r['clients'])) {
                            if (!$r['survey'] || $this->match_survey($survey, $r['survey'])) {

                                if ($r['bu'] == null || $buId == $r['bu']) {
                                    if ($r['countries'] == null || in_array($country, $r['countries'])) {
                                        if ($r['city'] == null || strcasecmp($r['city'], $city) == 0) {
                                            if (!$r['region']) {
                                                if (!$r['payrollCurr'])
                                                    return false;
                                                else {
                                                    if ($r['payrollCurr'] && strcasecmp($r['payrollCurr'], $curr) == 0)
                                                        return false;
                                                    else
                                                        continue;
                                                }
                                            } else {
                                                if (strcasecmp($r['region'], $region) == 0) {
                                                    if (!$r['payrollCurr'])
                                                        return false;
                                                    else {
                                                        if ($r['payrollCurr'] && strcasecmp($r['payrollCurr'], $curr) == 0)
                                                            return false;
                                                        else
                                                            continue;
                                                    }
                                                }
                                                else
                                                    continue;
                                            }
                                            if (!$r['billingRate']) {
                                                continue;
                                            } else {
                                                if ($r['billingRate'] == $billingRate)
                                                    return false;
                                                else
                                                    continue;
                                            }
                                            return false;
                                        } else {
                                            continue;
                                        }
                                    } else {
                                        continue;
                                    }
                                } else {
                                    continue;
                                }
                            } else {
                                continue;
                            }
                        } else {
                            continue;
                        }
                    } else {
                        if ($excl == false && $r['exclude'] == false)
                            if ((!$r['clients'] || in_array($clientName, $r['clients'])) && (!$r['countries'] || in_array($country, $r['countries'])) && (!$r['survey'] || $this->match_survey($survey, $r['survey'])) && (!$r['region'] || strcasecmp($r['region'], $region) == 0) && (!$r['city'] || strcasecmp($r['city'], $city) == 0) && (!$r['payrollCurr'] || strcasecmp($r['payrollCurr'], $curr) == 0) && (!$r['billingRate'] || $r['billingRate'] == $billingRate)) {
                                return $r['bu'];
                            }
                    }
                } else {
                    continue;
                }
            }
            if ($excl == true)
                return $buId;
            return false;
        }
    }

    function match_survey($survey, $rule) {
        $temp = false;
        foreach ($rule as $r) {
            if ($r != '' && stripos($survey, $r) != false) {
                return true;
            } else {
                $temp = false;
            }
        }
        return $temp;
    }

}