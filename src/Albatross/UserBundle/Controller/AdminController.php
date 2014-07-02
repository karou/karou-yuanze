<?php

namespace Albatross\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Albatross\UserBundle\Form\LogSearchType;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class AdminController extends Controller {

    public function indexAction() {
        return $this->render('AlbatrossUserBundle:Default:admin.html.twig', array(
                    'menu_bar' => 'admin',
                    'menu_cal_cur' => 'admin_index'
        ));
    }

    public function logAction(){
        $em = $this->getDoctrine()->getManager();
        $logSearchForm = $this->createForm(new LogSearchType(), array());
        $logSearchForm->bind($this->getRequest());
        $searchDate = $logSearchForm->getData();
        $dateFrom = $searchDate['logsearch_from'];
        $dateTo = $searchDate['logsearch_to'];
        $searchDown = $searchDate['search_down'];

        if($dateFrom == null && $dateTo == null){
            $dateFrom = $dateTo = date('Y-m-d', time());
        }
        $qb = $em->createQueryBuilder();
        $qb->select('log', 'bu', 'user')
                ->from('AlbatrossUserBundle:log', 'log')
                ->leftJoin('log.bu', 'bu')
                ->leftJoin('log.user', 'user')
                ->where('bu.id is not null');
        if($dateFrom != null){
            $qb->andWhere('log.date_time >= :dateFrom')
                ->setParameter('dateFrom', $dateFrom);
        }
        if($dateTo != null){
            $dateTo = date('Y-m-d', strtotime("+1 day", strtotime($dateTo)));
            $qb->andWhere('log.date_time <= :dateTo')
                ->setParameter('dateTo', $dateTo);
        }
        $query = $qb->getQuery();
        $result = $query->getArrayResult();
        $final = array();
        
        foreach ($result as $re){
            if(!isset($final[$re['bu']['name']][$re['user']['fullname']]['login_num']))
                $final[$re['bu']['name']][$re['user']['fullname']]['login_num'] = 0;
            if(!isset($final[$re['bu']['name']][$re['user']['fullname']]['operations_num']))
                $final[$re['bu']['name']][$re['user']['fullname']]['operations_num'] = 0;
            if(!isset($final[$re['bu']['name']][$re['user']['fullname']]['daily_num']))
                $final[$re['bu']['name']][$re['user']['fullname']]['daily_num'] = 0;
            if(!isset($final[$re['bu']['name']][$re['user']['fullname']]['forecast_action']))
                $final[$re['bu']['name']][$re['user']['fullname']]['forecast_action'] = 0;
            if(!isset($final[$re['bu']['name']][$re['user']['fullname']]['project_action']))
                $final[$re['bu']['name']][$re['user']['fullname']]['project_action'] = 0;
            if(!isset($final[$re['bu']['name']][$re['user']['fullname']]['project_num']))
                $final[$re['bu']['name']][$re['user']['fullname']]['project_num'] = 0;
            if(!isset($final[$re['bu']['name']][$re['user']['fullname']]['forecast_num']))
                $final[$re['bu']['name']][$re['user']['fullname']]['forecast_num'] = 0;
            if(!isset($final[$re['bu']['name']][$re['user']['fullname']]['time_first']) && $re['number_page'] == 1){
                $final[$re['bu']['name']][$re['user']['fullname']]['time_first'] = strtotime($re['date_time']->format('Y-m-d H:i:s'));
            }else if(isset($final[$re['bu']['name']][$re['user']['fullname']]['time_first']) && $re['number_page'] == 1){
                $final[$re['bu']['name']][$re['user']['fullname']]['time_first'] = 
                        ($final[$re['bu']['name']][$re['user']['fullname']]['time_first'] > strtotime($re['date_time']->format('Y-m-d H:i:s'))) ?
                        strtotime($re['date_time']->format('Y-m-d H:i:s')) : $final[$re['bu']['name']][$re['user']['fullname']]['time_first'];
            }
            if(!isset($final[$re['bu']['name']][$re['user']['fullname']]['time_last']) && $re['number_page'] == 1){
                $final[$re['bu']['name']][$re['user']['fullname']]['time_last'] = strtotime($re['date_time']->format('Y-m-d H:i:s'));
            }else if(isset($final[$re['bu']['name']][$re['user']['fullname']]['time_last']) && $re['number_page'] == 1){
                $final[$re['bu']['name']][$re['user']['fullname']]['time_last'] = 
                        ($final[$re['bu']['name']][$re['user']['fullname']]['time_last'] > strtotime($re['date_time']->format('Y-m-d H:i:s'))) ?
                        $final[$re['bu']['name']][$re['user']['fullname']]['time_last'] : strtotime($re['date_time']->format('Y-m-d H:i:s'));
            }
            //==================================================================
            if($re['number_page'] != null){
                if($re['number_page'] == 1)
                    $final[$re['bu']['name']][$re['user']['fullname']]['login_num']++;
                if($re['number_page'] == 2)
                    $final[$re['bu']['name']][$re['user']['fullname']]['operations_num']++;
                if($re['number_page'] == 3)
                    $final[$re['bu']['name']][$re['user']['fullname']]['daily_num']++;
                if($re['number_page'] == 4)
                    $final[$re['bu']['name']][$re['user']['fullname']]['project_num']++;
                if($re['number_page'] == 5)
                    $final[$re['bu']['name']][$re['user']['fullname']]['forecast_num']++;
            }else if($re['number_action'] != null){
                if($re['number_action'] == 4)
                    $final[$re['bu']['name']][$re['user']['fullname']]['forecast_action']++;
                if($re['number_action'] == 5)
                    $final[$re['bu']['name']][$re['user']['fullname']]['project_action']++;
            }
            
            if(isset($final[$re['bu']['name']][$re['user']['fullname']]['time_last']) 
                    && isset($final[$re['bu']['name']][$re['user']['fullname']]['time_first'])
                    && $re['number_page'] == 1){
                $final[$re['bu']['name']][$re['user']['fullname']]['time'] = 
                        floor(($final[$re['bu']['name']][$re['user']['fullname']]['time_last'] 
                                - $final[$re['bu']['name']][$re['user']['fullname']]['time_first'])/60);
            }else{
                $final[$re['bu']['name']][$re['user']['fullname']]['time'] = 0;
            }
        }
        if($searchDown == '1'){
            $excelName = 'User Log '.$dateFrom.'_'.$dateTo;
            $objPHPExcel = new \PHPExcel();
            $objPHPExcel->getProperties()->setTitle($excelName);
            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Group');
            $objPHPExcel->getActiveSheet()->setCellValue('B1', 'Number of Login');
            $objPHPExcel->getActiveSheet()->setCellValue('C1', 'Number of pages visited Operations');
            $objPHPExcel->getActiveSheet()->setCellValue('D1', 'Number of pages visited Daily Check');
            $objPHPExcel->getActiveSheet()->setCellValue('E1', 'Number of pages visited Project');
            $objPHPExcel->getActiveSheet()->setCellValue('F1', 'Number of pages visited Forecast');
            $objPHPExcel->getActiveSheet()->setCellValue('G1', 'Num of actions on Forecast');
            $objPHPExcel->getActiveSheet()->setCellValue('H1', 'Num of actions on Projects');
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
            $i = 2;
            foreach ($final as $key =>$f){
                $i++;
                $objPHPExcel->getActiveSheet()->setCellValue('A'.$i, $key);
                $i++;
                foreach($f as $k => $data){
                    $objPHPExcel->getActiveSheet()->setCellValue('A'.$i, $k);
                    $objPHPExcel->getActiveSheet()->setCellValue('B'.$i, $data['login_num']);
                    $objPHPExcel->getActiveSheet()->setCellValue('C'.$i, $data['operations_num']);
                    $objPHPExcel->getActiveSheet()->setCellValue('D'.$i, $data['daily_num']);
                    $objPHPExcel->getActiveSheet()->setCellValue('E'.$i, $data['project_num']);
                    $objPHPExcel->getActiveSheet()->setCellValue('F'.$i, $data['forecast_num']);
                    $objPHPExcel->getActiveSheet()->setCellValue('G'.$i, $data['forecast_action']);
                    $objPHPExcel->getActiveSheet()->setCellValue('H'.$i, $data['project_action']);
                }
            }
            $objWriter = \PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel5' );
            $excelName = urlencode ( $excelName );
            ob_end_clean ();
            header ( "Content-Type: application/force-download" );
            header ( "Content-Type: application/octet-stream" );
            header ( "Content-Type: application/download" );
            header ( "Content-Disposition: attachment; filename=" . $excelName . ".xls" );
            header ( "Content-Transfer-Encoding: binary" );
            header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
            header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
            header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
            header ( "Pragma: no-cache" );
            $objWriter->save ( 'php://output' );
        }
        return $this->render('AlbatrossUserBundle:Default:log.html.twig', array(
                    'menu_bar' => 'admin',
                    'menu_cal_cur' => 'log',
                    'logSearchForm' => $logSearchForm->createView(),
                    'final' => $final,
        ));
    }

    public function menuAction($active) {
        $secu = $this->container->get('security.context');
        $menu = array();
        $menu['user'] = array(
            'label' => 'User', 'children' => array( 'user' => array( 'label' => 'User List','route' => 'user'), 'add_user' => array( 'label' => 'New User', 'route' => 'user_new'))
        );
        $menu['group'] = array(
            'label' => 'Group', 'children' => array( 'group' => array( 'label' => 'Group List', 'route' => 'clientgroup' ), 'add_group' => array( 'label' => 'New Group', 'route' => 'clientgroup_new'))
        );
        $menu['filesection'] = array(
            'label' => 'File Section', 'children' => array( 'filesection' => array( 'label' => 'File Section List', 'route' => 'filesection'), 'add_filesection' => array( 'label' => 'New File Section', 'route' => 'filesection_new'))
        );
        $menu['status'] = array(
            'label' => 'Survey status', 'children' => array( 'status' => array( 'label' => 'Survey Status List', 'route' => 'status' ), 'add_status' => array( 'label' => 'New & Edit Status', 'route' => 'status_new'))
        );
        $menu['client'] = array(
            'label' => 'Client', 'children' => array( 'client' => array( 'label' => 'Client List', 'route' => 'client'), 'add_client' => array( 'label' => 'New Client', 'route' => 'client_new'))
        );
        $menu['rules'] = array(
            'label' => 'Rule', 'children' => array( 'rules' => array( 'label' => 'Rules List', 'route' => 'rules'), 'add_rule' => array( 'label' => 'New Rule', 'route' => 'rules_new'))
        );
        $menu['log'] = array(
            'label' => 'User Log', 'children' => array( 'log' => array( 'label' => 'Log List', 'route' => 'log' ))
        );
        if ($secu->isGranted('ROLE_ACE_BU_LIST'))
            $menu['countryandbu'] = array(
                'label' => 'Country & Bu', 'children' => array( 'log' => array( 'label' => 'Country & Bu', 'route' => 'bu' ))
            );
        return $this->render('AlbatrossUserBundle:Default:menu.html.twig', array(
                    'menu' => $menu, 'active' => $active,
        ));
    }
}