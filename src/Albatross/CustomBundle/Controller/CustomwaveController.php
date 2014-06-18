<?php

namespace Albatross\CustomBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Albatross\CustomBundle\Entity\Customwave;
use Albatross\CustomBundle\Form\CustomwaveType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Customwave controller.
 *
 */
class CustomwaveController extends Controller
{
    /**
     * Lists all Customwave entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AlbatrossCustomBundle:Customwave')->findAll();
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $entities, $this->get('request')->query->get('page', 1), 20/* page number */
        );
        return $this->render('AlbatrossCustomBundle:Customwave:index.html.twig', array(
            'entities' => $pagination,
            'current' => 'custom_project',
            'menu_bar' => 'custom',
            'menu_cal_cur' => 'wave',
        ));
    }

    /**
     * Creates a new Customwave entity.
     *
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(new CustomwaveType());
        $form->bind($request);

        $entity = new Customwave();
        $data = $form->getData();
        
        $questionnaire = $request->get('surveySelection');
        $questionnaire_uniq = array_unique($questionnaire);
        $campaign = $request->get('selectCampaign');

        $projects = $data['aceproject'];
        $customProjectId = $data['customproject']->getId();
        $curdate = $data['date'];
        $entity->setCustomproject($data['customproject']);
        if($data['number'] == 100){
            $number = 'DEMO';
        }else{
            $number = $data['number'];
        }
        $name = $data['projectname'].'_w'.$number.'_'.$curdate;
        $entity->setName($name);
        $entity->setWavenum($data['number']);
        $entity->setTotalnum($data['totalnum']);
        if($data['bis'] !== null){
            $entity->setBis('bis');
        }
        $yearAndMonth = explode(' ',$curdate);
        $entity->setMonth(date('m',strtotime($yearAndMonth[0])));
        $entity->setYear($yearAndMonth[1]);
        if(!empty($campaign) && $campaign != null){
            foreach($campaign as $c){
                $campaign_entity = $em->getRepository('AlbatrossAceBundle:Campaign')->findOneById($c);
                $campaign_entity->addCustomwave($entity);
                $entity->addCampaign($campaign_entity);
                $em->persist($campaign_entity);
            }
        }
        if(!empty($questionnaire_uniq) && $questionnaire_uniq[0] != ''){
            foreach($questionnaire_uniq as $q){
                $questionnaire_entity = $em->getRepository('AlbatrossAceBundle:Questionnaire')->findOneById($q);
                $questionnaire_entity->addCustomwave($entity);
                $entity->addQuestionnaire($questionnaire_entity);
                $em->persist($questionnaire_entity);
            }
        }
        foreach($projects as $p){
            $p->setCustomwave($entity);
            $em->persist($p);
        }
        $em->persist($entity);
        $em->flush();
        return $this->redirect($this->generateUrl('customproject_show', array('id' => $customProjectId)));
    }

    public function saveWaveYearAndMonthAction(){
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AlbatrossCustomBundle:Customwave')->findAll();
        foreach( $entities as $entity ){
            $name = $entity->getName();
            $yearAndMonthArr = explode('_', $name);
            $yearAndMonthStr = $yearAndMonthArr[4];
            $yearAndMonth = explode(' ', $yearAndMonthStr);
            $month = date('m',strtotime($yearAndMonth[0]));
            $year = $yearAndMonth[1];
            $entity->setMonth($month);
            $entity->setYear($year);
            $em->persist($entity);
        }
        $em->flush();
        echo 'finish save year and month';
        exit();
    }
    
    /**
     * Displays a form to create a new Customwave entity.
     *
     */
    public function newAction()
    {
        $form = $this->createForm(new CustomwaveType());
        $date = date('ym');
        $em = $this->getDoctrine()->getManager();
        $aolsurveyQb = $em->createQueryBuilder();
        $aolsurveyQb->select('q')
                ->from('AlbatrossAceBundle:Questionnaire', 'q')
                ->leftJoin('q.campaign', 'cam')
                ->leftJoin('cam.customwave', 'cw')
                ->groupBy('q.name')
                ->where('cw.id is null OR q.name like :apac OR q.name like :ww');
        $aolsurveyQb->setParameter('apac', 'APAC%');
        $aolsurveyQb->setParameter('ww', 'WW%');
        $aolsurveyQuery = $aolsurveyQb->getQuery();
        $aolsurvey = $aolsurveyQuery->getArrayResult();
        $getAolHtml = $this->getAolHtml($aolsurvey);
        return $this->render('AlbatrossCustomBundle:Customwave:new.html.twig', array(
            'form'   => $form->createView(),
            'current' => 'custom_project',
            'menu_bar' => 'custom',
            'menu_cal_cur' => 'wave',
            'datename' => $date,
            'aolform' => $getAolHtml
        ));
    }

    protected function getAolHtml($aolsurvey){
        $surveyHtml = '<select id="surveySelection_1" onchange="surveyChange(this);" class="surveySelection" name="surveySelection[]"><option value=""></option>';    //html form to show
//        $aolsurvey = array_unique($aolsurvey);

        foreach($aolsurvey as $aol){
                $surveyHtml .= '<option value="'.$aol['id'].'">'.$aol['name'].'</option>';
        }

        return $surveyHtml.'</select>';
    }

    public function getCampaignArrAction(){
        $questionnaireId = $this->getRequest()->getContent();
        $em = $this->getDoctrine()->getManager();
        $aolsurveyQb = $em->createQueryBuilder();
        $aolsurveyQb->select('q', 'c')
                ->from('AlbatrossAceBundle:Questionnaire', 'q')
                ->leftJoin('q.campaign', 'c')
                ->where('q.id = :qid');
        $aolsurveyQb->setParameters(array(
            'qid' => $questionnaireId
        ));
        $aolsurveyQuery = $aolsurveyQb->getQuery();
        $aolsurvey = $aolsurveyQuery->getArrayResult();
        $campaignHtml = '<select class="campaignSelection" name="selectCampaign[]"><option value=""></option>';
        foreach($aolsurvey[0]['campaign'] as $cam){
                $campaignHtml .= '<option value="'.$cam['id'].'">'.$cam['name'].'</option>';
        }
        return new Response($campaignHtml.'</select>');
    }

    protected function findCustomporjectByCustomclient($customclientId){

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

    public function getcpbyccAction(){
        $re = $this->getRequest()->getContent();
        $result = $this->findCustomporjectByCustomclient($re);
        $html = '<option value=""></option>';
        
        foreach($result as $re) {
            $html .= '<option value="'.$re['id'].'">'.$re['name'].'</option>';
        }
        return new Response($html);
    }
    /**
     * Finds and displays a Customwave entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AlbatrossCustomBundle:Customwave')->find($id);
        $aceproject = $em->getRepository('AlbatrossAceBundle:Project')->findByCustomwave($entity);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Customwave entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $surveys = $entity->getCampaign()->toArray();
        
        return $this->render('AlbatrossCustomBundle:Customwave:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(), 
            'current' => 'custom_project',
            'menu_bar' => 'custom',
            'menu_cal_cur' => 'wave',
            'aceproject' => $aceproject,
            'survey_result' => $surveys
            ));
    }

    /**
     * Displays a form to edit an existing Customwave entity.
     *
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AlbatrossCustomBundle:Customwave')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Customwave entity.');
        }

        $nameStr = $entity->getName();
        $name = explode('_', $nameStr);
        $wave = substr($name[3], 1, strlen($name[3]));
        $date = $name[4];
        $customClient = $entity->getCustomproject()->getCustomclient()->getId();
        $customproject = $entity->getCustomproject()->getId();
        $project  = $em->getRepository('AlbatrossAceBundle:Project')->findByCustomwave($id);
        $projArr = array();
        foreach($project as $p){
            $projArr[] = $p->getId();
        }
        $editForm = $this->createForm(new CustomwaveType());
        $deleteForm = $this->createDeleteForm($id);

        $aolsurveyQb = $em->createQueryBuilder();
        $aolsurveyQb->select('q')
                ->from('AlbatrossAceBundle:Questionnaire', 'q')
                ->leftJoin('q.campaign', 'cam')
                ->leftJoin('cam.customwave', 'cw')
                ->groupBy('q.name')
                ->where('cw.id is null OR cw.id = :wid OR q.name like :apac OR q.name like :ww');
        $aolsurveyQb->setParameters(array(
            'wid' => $id,
            'apac' => 'APAC%',
            'ww' => 'WW%'
        ));
        $aolsurveyQuery = $aolsurveyQb->getQuery();
        $aolsurvey = $aolsurveyQuery->getArrayResult();
        $getAolHtml = $this->getAolHtml($aolsurvey);
        $selectedList = $this->getAolEditHtml($id);
        if(empty($selectedList)){
            $selectedList = 0;
        }

        return $this->render('AlbatrossCustomBundle:Customwave:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'custom_client' => $customClient,
            'customproject' => $customproject,
            'projArr' => $projArr,
            'current' => 'custom_project',
            'menu_bar' => 'custom',
            'menu_cal_cur' => 'wave',
            'number' => $wave,
            'date' => $date,
            'aolform' => $getAolHtml,
            'selectedList' => $selectedList
        ));
    }

    protected function getAolEditHtml($wid){
        $em = $this->getDoctrine()->getManager();
        $aolsurveyQb = $em->createQueryBuilder();
        $aolsurveyQb->select('c', 'q')
                ->from('AlbatrossAceBundle:Campaign', 'c')
                ->leftJoin('c.questionnaire', 'q')
                ->leftJoin('c.customwave', 'cw')
                ->where('cw.id = :wid');
        $aolsurveyQb->setParameters(array(
            'wid' => $wid
        ));
        $aolsurveyQuery = $aolsurveyQb->getQuery();
        $aolsurvey = $aolsurveyQuery->getArrayResult();

        return $aolsurvey;
    }
    /**
     * Edits an existing Customwave entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(new CustomwaveType());
        $form->bind($request);

        $entity = $em->getRepository('AlbatrossCustomBundle:Customwave')->find($id);
        $data = $form->getData();

        $curdate = $data['date'];
        $entity->setCustomproject($data['customproject']);
        $customProjectId = $data['customproject']->getId();
        if($data['number'] == 100){
            $number = 'DEMO';
        }else{
            $number = $data['number'];
        }
        $name = $data['projectname'].'_w'.$number.'_'.$curdate;
        $entity->setName($name);
        $entity->setWavenum($data['number']);
        $entity->setTotalnum($data['totalnum']);
        
        if($data['bis'] !== null){
            $entity->setBis('bis');
        }
        $yearAndMonth = explode(' ',$curdate);
        $entity->setMonth(date('m',strtotime($yearAndMonth[0])));
        $entity->setYear($yearAndMonth[1]);
        $projects = $data['aceproject'];
        $preproject = $em->getRepository('AlbatrossAceBundle:Project')->findByCustomwave($entity);

        foreach($preproject as $pre){
            $pre->setCustomwave(null);
            $em->persist($pre);
        }

        foreach($projects as $p){
            $p->setCustomwave($entity);
            $em->persist($p);
        }
        
        $questionnaire = $request->get('surveySelection');
        if($questionnaire != null)
            $questionnaire_uniq = array_unique($questionnaire);
        else
            $questionnaire_uniq = '';
        $campaign = $request->get('selectCampaign');
        
        //remove previous links to this wave====================================
        $pre_questionnaire = $entity->getQuestionnaire()->toArray();
        if($pre_questionnaire != null){
            foreach($pre_questionnaire as $pq){
                $pq->removeCustomwave($entity);
                $entity->removeQuestionnaire($pq);
                $em->persist($pq);
            }
        }
        $pre_campaign = $entity->getCampaign()->toArray();
        if($pre_campaign != null){
            foreach($pre_campaign as $pc){
                $pc->removeCustomwave($entity);
                $entity->removeCampaign($pc);
                $em->persist($pc);
            }
        }

        //set new link to this wave ============================================
        if(!empty($campaign) && $campaign != null){
            foreach($campaign as $c){
                $campaign_entity = $em->getRepository('AlbatrossAceBundle:Campaign')->findOneById($c);
                $campaign_entity->addCustomwave($entity);
                $entity->addCampaign($campaign_entity);
                $em->persist($campaign_entity);
            }
        }
        if(!empty($questionnaire_uniq) && $questionnaire_uniq[0] != ''){
            foreach($questionnaire_uniq as $q){
                $questionnaire_entity = $em->getRepository('AlbatrossAceBundle:Questionnaire')->findOneById($q);
                $questionnaire_entity->addCustomwave($entity);
                $entity->addQuestionnaire($questionnaire_entity);
                $em->persist($questionnaire_entity);
            }
        }
        $em->persist($entity);
        $em->flush();
        return $this->redirect($this->generateUrl('customproject_show', array('id' => $customProjectId)));
    }

    /**
     * Deletes a Customwave entity.
     *
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AlbatrossCustomBundle:Customwave')->find($id);
        $projEntity = $entity->getProject()->toArray();
        if(empty($projEntity)){
            $em->remove($entity);
            $em->flush();

            return new Response('success');
        }else{
            return new Response('confirm');
        }
    }
    public function deleteWaveWithProjectAction($id){
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AlbatrossCustomBundle:Customwave')->find($id);
        $em->remove($entity);
        $em->flush();
        return new Response('success');
    }
    /**
     * Creates a form to delete a Customwave entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}