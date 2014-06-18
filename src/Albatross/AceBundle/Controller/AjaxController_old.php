<?php

namespace Albatross\AceBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Albatross\AceBundle\Entity\Country;
use Albatross\AceBundle\Form\CountryType;

/**
 * Country controller.
 *
 */
class AjaxController extends Controller {

    public function ajaxAction() {
        if (isset($_REQUEST['ajaxaction']) and $_REQUEST['ajaxaction'] == 'deleteAttachment') {
            $id = $_REQUEST['id'];

            $em = $this->getDoctrine()->getManager();

            $connection = $em->getConnection();

            $sql = 'delete from attachments where id="' . $id . '"';

            $statement = $connection->prepare($sql);
            $statement->execute();

            echo 'success';
            exit;
        }

        if (isset($_REQUEST['ajaxaction']) and $_REQUEST['ajaxaction'] == 'getSubSectionList') {
            $id = $_REQUEST['id'];

            $em = $this->getDoctrine()->getManager();

            $connection = $em->getConnection();

            $sql = 'select * from filesection where parent_id="' . $id . '" ';

            $statement = $connection->prepare($sql);
            $statement->execute();
            $results = $statement->fetchAll();
            $countTotal = count($results);

            $list_section = '';
            if ($countTotal > 0) {
                $list_section .= '<option value="">Select sub-section</option>';
                foreach ($results as $section) {
                    $list_section .= '<option value="' . $section['id'] . '">' . $section['name'] . '</option>';
                }
            } else {
                $list_section .= '<option value="0">No sub-section available</option>';
            }
            echo '<select name="sub_section" id="sub_section" data-placeholder="Sub Section" class="chzn-select span1 iBlock nBorder" tabindex="2" style="width:99%; float:left;">' . $list_section . '</select>';
            exit;
        }

        if (isset($_REQUEST['ajaxaction']) and $_REQUEST['ajaxaction'] == 'getSubSectionFileData') {
            $id = $_REQUEST['id'];

            $em = $this->getDoctrine()->getManager();

            $connection = $em->getConnection();

            $sql = 'select * from attachments where filesection_id="' . $id . '" and type=3';

            $statement = $connection->prepare($sql);
            $statement->execute();
            $results = $statement->fetchAll();
            $countTotal = count($results);

            if ($countTotal > 0) {
                echo '<table border="1" id="subSectionFileTable' . $id . '" class="subSectionFileTable responsive table table-striped" bordercolor="#dddddd" style="width:100%;margin:0;">
						<thead><th>File Label</th><th>Submit Date</th><th>Actions</th></thead>
						<tbody>';
                foreach ($results as $file) {
                    echo '<tr>
							<td width="60%">
								<a href="javascript:;" class="file-list-icon-a">
									' . stripslashes($file['label']) . '
								</a>
							</td>
							<td>' . stripslashes($file['submitteddate']) . '</td>
							<td width="10%">
								<button class="little-btn btn-info" type="button">
									<i class="icon-cloud-upload"></i>
								</button>
								<button class="little-btn btn-danger" type="button" onclick="deleteAttachment(' . $file['id'] . ',' . $id . ',\'sub\');">
									<i class="icon-remove"></i>
								</button>
							</td>
						 </tr>';
                }
                echo '</tbody></table>';
            } else {
                /* echo '<table border="1" id="subSectionFileTable'.$id.'" class="subSectionFileTable responsive table table-striped" bordercolor="#dddddd" style="width:100%;margin:0;">
                  <thead><th>File Label</th><th>Submit Date</th><th>Actions</th></thead>
                  <tbody>';

                  for($x=0;$x<3;$x++)
                  {
                  echo '<tr>
                  <td width="60%">
                  <a href="javascript:;" class="file-list-icon-a">
                  Reports-Store Performance FAQ
                  </a>
                  </td>
                  <td>2013-08-21 13:41:12</td>
                  <td width="10%">
                  <button class="little-btn btn-info" type="submit">
                  <i class="icon-cloud-upload"></i>
                  </button>
                  <button class="little-btn btn-danger" type="reset">
                  <i class="icon-remove"></i>
                  </button>
                  </td>
                  </tr>';
                  }
                  echo '</tbody></table>'; */
                echo '<div class="no-records-div">No sub-section available</div>';
            }
            exit;
        }

        if (isset($_REQUEST['ajaxaction']) and $_REQUEST['ajaxaction'] == 'getSubFileData') {
            $id = $_REQUEST['id'];

            $em = $this->getDoctrine()->getManager();

            $connection = $em->getConnection();

            $sql = 'select * from filesection where parent_id="' . $id . '" ';

            $statement = $connection->prepare($sql);
            $statement->execute();
            $results = $statement->fetchAll();
            $countTotal = count($results);

            $sqlAttach = 'select * from attachments where filesection_id="' . $id . '" and type=3';
            $statementAttach = $connection->prepare($sqlAttach);
            $statementAttach->execute();
            $resultsAttach = $statementAttach->fetchAll();
            $countAttachTotal = count($resultsAttach);

            if ($countTotal > 0 or $countAttachTotal > 0) {
                if ($countTotal > 0) {
                    echo '<table border="1" class="subSectionTable" bordercolor="#dddddd" style="width:100%;margin:0;">';
                    foreach ($results as $file) {
                        //////to get the last uploaded attachment//////////
                        $sqlAttach = 'select * from attachments where filesection_id="' . $file['id'] . '" and type=3 order by id desc limit 1';
                        $stAttach = $connection->prepare($sqlAttach);
                        $stAttach->execute();
                        $rec = $stAttach->fetchAll();

                        $lastUpload = '-';
                        if (isset($rec[0]['label']) and $rec[0]['label'] != '') {
                            $lastUpload = '<a href="web/' . $rec[0]['path'] . '" target="_blank">' . $rec[0]['label'] . '</a> (' . $rec[0]['submitteddate'] . ')'; // ' .$this->basicElements['rootPath'] . '
                        }

                        echo '<tr>
								<td colspan="2" width="31%">
									<a href="javascript:;" class="file-list-icon-a">
										<div class="dark-yellow file-list-icon-div">
											<i class="icon-dashboard file-list-icon"></i>
										</div>
										' . stripslashes($file['name']) . ' <i class="icon-plus-sign file-sub-list-plus-minus" id="' . $file['id'] . '"></i>
									</a>
								</td>
								<td width="33%">' . stripslashes($file['description']) . '</td>
								<td>' . $lastUpload . '</td>
							 </tr>';
                    }
                    echo '</table>';
                }

                if ($countAttachTotal > 0) {
                    echo '<table border="1" id="subSectionFileTable' . $id . '" class="subSectionFileTable responsive table table-striped" bordercolor="#dddddd" style="width:100%;margin:0;">
							<thead><th>File Label</th><th>Submit Date</th><th>Actions</th></thead>
							<tbody>';
                    foreach ($resultsAttach as $attach) {
                        echo '<tr>
								<td width="60%">
									<a href="javascript:;" class="file-list-icon-a">
										' . stripslashes($attach['label']) . '
									</a>
								</td>
								<td>' . stripslashes($attach['submitteddate']) . '</td>
								<td width="10%">
									<button class="little-btn btn-info" type="button">
										<i class="icon-cloud-upload"></i>
									</button>
									<button class="little-btn btn-danger" type="button" onclick="deleteAttachment(' . $attach['id'] . ',' . $id . ',\'main\');">
										<i class="icon-remove"></i>
									</button>
								</td>
							 </tr>';
                    }
                    echo '</tbody></table>';
                }
            } else {
                /* echo '<table border="1" class="subSectionTable" bordercolor="#dddddd" style="width:100%;margin:0;">';
                  for($x=0;$x<3;$x++)
                  {
                  echo '<tr>
                  <td colspan="2" width="31%">
                  <a href="javascript:;" class="file-list-icon-a">
                  <div class="dark-yellow file-list-icon-div">
                  <i class="icon-dashboard file-list-icon"></i>
                  </div>
                  Sub-section <i class="icon-plus-sign file-sub-list-plus-minus" id="22"></i>
                  </a>
                  </td>
                  <td width="33%">This is test sub description</td>
                  <td><a href="javascript:;">Reports-Store Performance FAQ</a> (2013-08-21 13:41:27)</td>
                  </tr>';
                  }
                  echo '</table>'; */
                echo '<div class="no-records-div">No records available</div>';
            }
            exit;
        }

        if (isset($_REQUEST['ajaxaction']) and $_REQUEST['ajaxaction'] == 'getFileData') {
            $em = $this->getDoctrine()->getManager();

            $connection = $em->getConnection();

            ///////Code for project listing////////
            $aColumns = array('id', 'name', 'description');
            $aColumnSort = array('id', 'name', 'description');
            $aColumnSearch = array('id', 'name', 'description');

            $sIndexColumn = "id";

            /* DB table to use */
            $sTable = " filesection";

            /*             * *** paging **** */
            $sLimit = "";
            if (isset($_REQUEST['iDisplayStart']) && $_REQUEST['iDisplayLength'] != '-1') {
                $sLimit = "LIMIT " . ( $_REQUEST['iDisplayStart'] ) . ", " .
                        ( $_REQUEST['iDisplayLength'] );
            }

            /*             * *** Ordering **** */
            $sOrder = '';
            if (isset($_REQUEST['iSortCol_0'])) {
                $sOrder = "ORDER BY  ";
                for ($i = 0; $i < intval($_REQUEST['iSortingCols']); $i++) {
                    if ($_REQUEST['bSortable_' . intval($_REQUEST['iSortCol_' . $i])] == "true") {
                        $sOrder .= $aColumns[intval($_REQUEST['iSortCol_' . $i])] . "
							" . ( $_REQUEST['sSortDir_' . $i] ) . ", ";
                    }
                }

                $sOrder = substr_replace($sOrder, "", -2);
                if ($sOrder == "ORDER BY") {
                    $sOrder = "";
                }
            }

            /*             * *** Filtering **** */
            $sWhere = "";
            if (isset($_REQUEST['sSearch']) and $_REQUEST['sSearch'] != "") {
                $sWhere = "WHERE (";
                for ($i = 0; $i < count($aColumns); $i++) {
                    $sWhere .= $aColumns[$i] . " LIKE '%" . ( $_REQUEST['sSearch'] ) . "%' OR ";
                }
                $sWhere = substr_replace($sWhere, "", -3);
                $sWhere .= ')';
            }

            /* Individual column filtering */
            for ($i = 0; $i < count($aColumns); $i++) {
                if (isset($_REQUEST['bSearchable_' . $i]) and isset($_REQUEST['sSearch_' . $i]) and $_REQUEST['bSearchable_' . $i] == "true" && $_REQUEST['sSearch_' . $i] != '') {
                    if ($sWhere == "") {
                        $sWhere = "WHERE ";
                    } else {
                        $sWhere .= " AND ";
                    }
                    $sWhere .= $aColumns[$i] . " LIKE '%" . ($_REQUEST['sSearch_' . $i]) . "%' ";
                }
            }

            $and = '';

            if ($sOrder == '')
                $sOrder = ' order by id desc ';

            $sqlCount = 'select id from  filesection where 1 ' . $sWhere . $and . ' ' . $sOrder;

            $sql = 'select * from filesection where 1 ' . $sWhere . $and . ' ' . $sOrder . ' ' . $sLimit;

            $statement = $connection->prepare($sqlCount);
            $statement->execute();
            /* $resultsCnt = $statement->fetchAll();
              $iTotal = $resultsCnt[0]['cntAttach']; */
            $iTotal = $statement->rowCount();

            $statement = $connection->prepare($sql);
            $statement->execute();
            $results = $statement->fetchAll();
            $iFilteredTotal = count($results);

            //* Output //		 
            $output = array(
                "order" => $sOrder,
                "q" => $sql,
                "sEcho" => intval((isset($_GET['sEcho']) ? $_GET['sEcho'] : 0)),
                "iTotalRecords" => $iTotal,
                "iTotalDisplayRecords" => $iTotal,
                "aaData" => array()
            );

            foreach ($results as $file) {
                //////to get the last uploaded attachment//////////
                $sqlAttach = 'select * from attachments where filesection_id="' . $file['id'] . '" and type=3 order by id desc limit 1';
                $stAttach = $connection->prepare($sqlAttach);
                $stAttach->execute();
                $rec = $stAttach->fetchAll();

                $lastUpload = '-';
                if (isset($rec[0]['label']) and $rec[0]['label'] != '') {
                    $lastUpload = '<a href="web/' . $rec[0]['path'] . '" target="_blank">' . $rec[0]['label'] . '</a> (' . $rec[0]['submitteddate'] . ')'; // ' .$this->basicElements['rootPath'] . '
                }

                $row = '';
                $row[] = $file['id'];
                $row[] = '<a href="javascript:;" class="file-list-icon-a">
							<div class="dark-yellow file-list-icon-div">
								<i class="icon-dashboard file-list-icon"></i>
							</div>
							' . stripslashes($file['name']) . ' <i class="icon-plus-sign file-list-plus-minus" id="' . $file['id'] . '"></i>
						</a>';
                $row[] = stripslashes($file['description']);
                $row[] = $lastUpload;

                $output['aaData'][] = $row;
            }
            echo json_encode($output);
            exit;
        }

        if (isset($_REQUEST['ajaxaction']) and $_REQUEST['ajaxaction'] == 'getProjectData') {
            $em = $this->getDoctrine()->getManager();
            $connection = $em->getConnection();

            ///////Code for project listing////////
//            $aColumns = array('c.id', 'c.name', 'project_manager', 'a.scope', 'type', 'status', 'date', 'actions', 'progress');
//            $aColumnSort = array('c.id', 'c.name', 'project_manager', 'a.scope', 'type', 'status', 'date');
//            $aColumnSearch = array('c.id', 'c.name', 'project_manager', 'a.scope', 'type', 'status', 'date');
            $aColumns = array('c.name', 'report_ka', 'project_manager', 'status', 'date', 'actions', 'progress');
            $aColumnSort = array('c.name', 'report_ka', 'project_manager', 'status', 'date');
            $aColumnSearch = array('c.name', 'report_ka', 'project_manager', 'status', 'date');
            $sIndexColumn = "a.id";

            /* DB table to use */
            $sTable = "customproject";

            /*             * *** paging **** */
            $sLimit = "";
            if (isset($_REQUEST['iDisplayStart']) && $_REQUEST['iDisplayLength'] != '-1') {
                $sLimit = "LIMIT " . ( $_REQUEST['iDisplayStart'] ) . ", " .
                        ( $_REQUEST['iDisplayLength'] );
            }

            /*             * *** Ordering **** */
            $sOrder = '';
            if (isset($_REQUEST['iSortCol_0'])) {
                $sOrder = "ORDER BY  ";
                for ($i = 0; $i < intval($_REQUEST['iSortingCols']); $i++) {
                    if ($_REQUEST['bSortable_' . intval($_REQUEST['iSortCol_' . $i])] == "true") {
                        $sOrder .= $aColumns[intval($_REQUEST['iSortCol_' . $i])] . "
							" . ( $_REQUEST['sSortDir_' . $i] ) . ", ";
                    }
                }

                $sOrder = substr_replace($sOrder, "", -2);
                if ($sOrder == "ORDER BY") {
                    $sOrder = "";
                }
            }

            /*             * *** Filtering **** */
            $sWhere = "";
            if (isset($_REQUEST['sSearch']) and $_REQUEST['sSearch'] != "") {
                $sWhere = "WHERE (";
                for ($i = 0; $i < count($aColumns); $i++) {
                    $sWhere .= $aColumns[$i] . " LIKE '%" . ( $_REQUEST['sSearch'] ) . "%' OR ";
                }
                $sWhere = substr_replace($sWhere, "", -3);
                $sWhere .= ')';
            }

            /* Individual column filtering */
            for ($i = 0; $i < count($aColumns); $i++) {
                if (isset($_REQUEST['bSearchable_' . $i]) and isset($_REQUEST['sSearch_' . $i]) and $_REQUEST['bSearchable_' . $i] == "true" && $_REQUEST['sSearch_' . $i] != '') {
                    if ($sWhere == "") {
                        $sWhere = "WHERE ";
                    } else {
                        $sWhere .= " AND ";
                    }
                    $sWhere .= $aColumns[$i] . " LIKE '%" . ($_REQUEST['sSearch_' . $i]) . "%' ";
                }
            }

            $and = '';
            if (isset($_REQUEST['client']) and $_REQUEST['client'] != '') {
                $and .= ' and c.customclient_id in (' . $_REQUEST['client'] . ')';
            }
            if (isset($_REQUEST['type']) and $_REQUEST['type'] != '') {
                $and .= ' and c.type in (' . $_REQUEST['type'] . ')';
            }
            if (isset($_REQUEST['bu']) and $_REQUEST['bu'] != '') {
                $and .= ' and c.scope in (' . $_REQUEST['bu'] . ')';
            }

            if ($sOrder == '')
                $sOrder = ' order by c.id asc,cw.id desc ';
            else
                $sOrder .= ',cw.id desc ';

            $sqlCount = 'select c.id from customproject c left join customwave cw on (c.id=cw.customproject_id) left join customfield cu on (cw.id=cu.customwave_id and cu.fieldtype="report") where 1 ' . $sWhere . $and . ' group by c.id ' . $sOrder;
            $sql = 'select c.*,cw.wavenum,cu.submittime,cw.id as custwave_id from customproject c left join customwave cw on (c.id=cw.customproject_id) left join customfield cu on (cw.id=cu.customwave_id and cu.fieldtype="report") where 1 ' . $sWhere . $and . ' group by c.id ' . $sOrder . ' ' . $sLimit;

            $statement = $connection->prepare($sqlCount);
            $statement->execute();
            /* $resultsCnt = $statement->fetchAll();
              $iTotal = $resultsCnt[0]['cntAttach']; */
            $iTotal = $statement->rowCount();

            $statement = $connection->prepare($sql);
            $statement->execute();
            $results = $statement->fetchAll();
            $iFilteredTotal = count($results);

            //* Output //		 
            $output = array(
                "order" => $sOrder,
                "q" => $sql,
                "sEcho" => intval((isset($_GET['sEcho']) ? $_GET['sEcho'] : 0)),
                "iTotalRecords" => $iTotal,
                "iTotalDisplayRecords" => $iTotal,
                "aaData" => array()
            );

            foreach ($results as $project) {
                $prNameArr = explode('_', stripslashes($project['name']));

                //////////project preparation percentage//////////
                $perProjectPrep = $this->getProjectPrepPer($project['id'], $project['custwave_id']);

                /////////report delivery percentage////////
                $sql = 'select id from customfield where fieldtype="report" and customwave_id="' . $project['custwave_id'] . '"';
                $statementR = $connection->prepare($sql);
                $statementR->execute();
                $reportCount = $statementR->rowCount();

                $perReportPer = 0;
                if ($reportCount > 0)
                    $perReportPer = 100;

                //////////for calculating field work percentage/////////////

                $row = '';
                $row[] = $project['id'];
                $row[] = '<a href="' . $this->generateUrl('view_project') . '?id=' . $project['id'] . '">' . stripslashes($project['name']) . '</a>';
                $row[] = '<a href="#">' . $prNameArr[0] . '</a>';
                $row[] = '<span class="label label-info">' . $prNameArr[2] . '</span>';
                $row[] = '<span class="label label-info">' . $prNameArr[1] . '</span>';
                $row[] = 'Wave ' . $project['wavenum'];
                $row[] = $project['submittime'];
                $row[] = '<div class="btn-group">
							<button class="brown noborder" data-original-title="Open Project" onclick="window.location.href=\'' . $this->generateUrl('view_project') . '?id=' . $project['id'] . '\'"><i class="icon-eye-open"></i></button>
							<button class="blue noborder" data-original-title="Follow Project"><i class="icon-twitter"></i></button>
							<button class="green noborder" data-original-title="Edit Project"><i class=" icon-pencil"></i></button>
							<button class="orange noborder" data-original-title="Remove Project"><i class=" icon-remove"></i></button>
						</div>';
                $row[] = '<div class="progress progress-info progress-striped active tipsy" data-original-title="Project Preparation">
							<div class="bar" style="width: ' . $perProjectPrep . '%"></div>
						  </div>
						  <div class="progress progress-success progress-striped active tipsy" data-original-title="Field Work">
							<div class="bar" style="width: 40%"></div>
						  </div>
						  <div class="progress progress-warning progress-striped active tipsy" data-original-title="Editing">
							<div class="bar" style="width: 70%"></div>
						  </div>
						  <div class="progress progress-danger progress-striped active tipsy" data-original-title="Report Delivery">
							<div class="bar" style="width: ' . $perReportPer . '%"></div>
						  </div>';

                $output['aaData'][] = $row;
            }
            echo json_encode($output);
            exit;
        }

        if (isset($_POST['ajaxaction']) and $_POST['ajaxaction'] == 'refreshPerTable') {
            /////////search fields///////////////
            $week_val = 0;
            if (isset($_POST['week_val']) and $_POST['week_val'] != '')
                $week_val = $_POST['week_val'];

            $bu_id = 8;
            if (isset($_POST['bu']) and is_array($_POST['bu']))
                $bu_id = $_POST['bu'];

            //////////for getting number situation as for today/////
            $thisWeekStDate = date('Y-m-d', strtotime('this week'));
            $thisWeekStDateArr = explode('-', $thisWeekStDate);

            $monDate = date('Y-m-d', mktime(0, 0, 0, $thisWeekStDateArr[1], $thisWeekStDateArr[2] + ($week_val * 7), $thisWeekStDateArr[0]));
            $monDateDisp = str_replace('-', '<br/>', date('D-d', mktime(0, 0, 0, $thisWeekStDateArr[1] + ($week_val * 7), $thisWeekStDateArr[2], $thisWeekStDateArr[0])));

            $monthDate = date('Y-m', mktime(0, 0, 0, $thisWeekStDateArr[1] - $week_val, $thisWeekStDateArr[2], $thisWeekStDateArr[0]));

            $sunDate = date('Y-m-d', mktime(0, 0, 0, $thisWeekStDateArr[1] - $week_val + 1, $thisWeekStDateArr[2], $thisWeekStDateArr[0]));

            $arrMonDate = explode('-', $monDate);

            $tuesDate = date('Y-m-d', mktime(0, 0, 0, $arrMonDate[1], $arrMonDate[2] + 1, $arrMonDate[0]));
            $tuesDateDisp = str_replace('-', '<br/>', date('D-d', mktime(0, 0, 0, $arrMonDate[1], $arrMonDate[2] + 1, $arrMonDate[0])));

            $wedDate = date('Y-m-d', mktime(0, 0, 0, $arrMonDate[1], $arrMonDate[2] + 2, $arrMonDate[0]));
            $wedDateDisp = str_replace('-', '<br/>', date('D-d', mktime(0, 0, 0, $arrMonDate[1], $arrMonDate[2] + 2, $arrMonDate[0])));

            $thursDate = date('Y-m-d', mktime(0, 0, 0, $arrMonDate[1], $arrMonDate[2] + 3, $arrMonDate[0]));
            $thursDateDisp = str_replace('-', '<br/>', date('D-d', mktime(0, 0, 0, $arrMonDate[1], $arrMonDate[2] + 3, $arrMonDate[0])));

            $friDate = date('Y-m-d', mktime(0, 0, 0, $arrMonDate[1], $arrMonDate[2] + 4, $arrMonDate[0]));
            $friDateDisp = str_replace('-', '<br/>', date('D-d', mktime(0, 0, 0, $arrMonDate[1], $arrMonDate[2] + 4, $arrMonDate[0])));


            $satDate = date('Y-m-d', mktime(0, 0, 0, $arrMonDate[1], $arrMonDate[2] + 5, $arrMonDate[0]));
            $satDateDisp = str_replace('-', '<br/>', date('D-d', mktime(0, 0, 0, $arrMonDate[1], $arrMonDate[2] + 5, $arrMonDate[0])));

            $sunDate = date('Y-m-d', mktime(0, 0, 0, $arrMonDate[1], $arrMonDate[2] + 6, $arrMonDate[0]));
            $sunDateDisp = str_replace('-', '<br/>', date('D-d', mktime(0, 0, 0, $arrMonDate[1], $arrMonDate[2] + 6, $arrMonDate[0])));

            $em = $this->getDoctrine()->getManager();
            $connection = $em->getConnection();

            ///////Code for Forecaset percentage listing////////
            $monForeSql = 'select forecast from forecastscope where bu_id="' . $bu_id . '" and month="' . $monthDate . '"';
            $monForeStatement = $connection->prepare($monForeSql);
            $monForeStatement->execute();
            $resultsMonFore = $monForeStatement->fetchAll();
            $forecastVal = (isset($resultsMonFore['forecast'])) ? $resultsMonFore['forecast'] : 0;

            $sqlPer = 'select s.id,s.status,n.number,d.daily_date from status s left join number n on (s.id=n.status_id) left join date d on (d.id=n.date_id) where d.daily_date >= "' . $monDate . '" and d.daily_date <= "' . $sunDate . '" and s.id=1  and d.bu_id in (' . $bu_id . ') group by s.status,d.daily_date order by s.weight,d.daily_date';

            $perStatement = $connection->prepare($sqlPer);
            $perStatement->execute();
            $resultsPer = $perStatement->fetchAll();

            $perResult = '<tr>
							<th width="30%">
								<br/><b>Forecast in Total</b>
							</th>
							<th width="5%" align="center">
								<b>' . $monDateDisp . '</b>
							</th>
							<th width="5%" align="center">
								<b>' . $tuesDateDisp . '</b>
							</th>
							<th width="5%" align="center">
								<b>' . $wedDateDisp . '</b>
							</th>
							<th width="5%" align="center">
								<b>' . $thursDateDisp . '</b>
							</th>
							<th width="5%" align="center">
								<b>' . $friDateDisp . '</b>
							</th>
							<th width="5%" align="center">
								<b>' . $satDateDisp . '</b>
							</th>
							<th width="5%" align="center">
								<b>' . $sunDateDisp . '</b>
							</th>
							<th align="center">&nbsp;
								
							</th>
						</tr>';

            if (count($resultsPer) > 0) {
                $arrFinal = array();
                foreach ($resultsPer as $resPer) {
                    $status = $resPer['status'];
                    $date = $resPer['daily_date'];
                    $arrFinal[$status][$date] = $resPer['number']; //($resPer['number']/$forecastVal)*100;
                }

                $x = 1;
                foreach ($arrFinal as $keyFinal => $final) {
                    $totalVal = $monVal = $tuesVal = $wedVal = $thursVal = $friVal = $satVal = $sunVal = 0;
                    if (isset($final[$monDate])) {
                        $totalVal += $final[$monDate];

                        if ($forecastVal > 0)
                            $monVal = ($final[$monDate] / $forecastVal) * 100;
                    }
                    if (isset($final[$tuesDate])) {
                        $totalVal += $final[$tuesDate];

                        if ($forecastVal > 0)
                            $tuesVal = ($final[$tuesDate] / $forecastVal) * 100;
                    }
                    if (isset($final[$wedDate])) {
                        $totalVal += $final[$wedDate];

                        if ($forecastVal > 0)
                            $wedVal = ($final[$wedDate] / $forecastVal) * 100;
                    }
                    if (isset($final[$thursDate])) {
                        $totalVal += $final[$thursDate];

                        if ($forecastVal > 0)
                            $thursVal = ($final[$thursDate] / $forecastVal) * 100;
                    }
                    if (isset($final[$friDate])) {
                        $totalVal += $final[$friDate];

                        if ($forecastVal > 0)
                            $friVal = ($final[$friDate] / $forecastVal) * 100;
                    }
                    if (isset($final[$satDate])) {
                        $totalVal += $final[$satDate];

                        if ($forecastVal > 0)
                            $satVal = ($final[$satDate] / $forecastVal) * 100;
                    }
                    if (isset($final[$sunDate])) {
                        $totalVal += $final[$sunDate];

                        if ($forecastVal > 0)
                            $sunVal = ($final[$sunDate] / $forecastVal) * 100;
                    }
                    $perResult .= '<tr>
									<td width="30%" align="center" style="text-align:center;">
										<b>' . $totalVal . '</b> &nbsp; <a href="javascript:;" class="btn btn-info btn-blue"  onclick="refreshPerTable(\'' . $bu_id . '\',\'' . $week_val . '\');" id="refreshPerLink"><i class="icon-refresh"></i> Refresh</a>
									</td>
									<td width="5%" align="center">
										' . $monVal . '%
									</td>
									<td width="5%" align="center">
										' . $tuesVal . '%
									</td>
									<td width="5%" align="center">
										' . $wedVal . '%
									</td>
									<td width="5%" align="center">
										' . $thursVal . '%
									</td>
									<td width="5%" align="center">
										' . $friVal . '%
									</td>
									<td width="5%" align="center">
										' . $satVal . '%
									</td>
									<td width="5%" align="center">
										' . $sunVal . '%
									</td>
									<td align="center">&nbsp;
										
									</td>
								</tr>';

                    $x++;
                }
            }
            else {
                $perResult .= '<tr>
									<td colspan="9" align="center">No data available.</td>
								</tr>';
            }
            echo $perResult;
            exit;
        }

        if (isset($_REQUEST['act']) and $_REQUEST['act'] != '') {
            $_SESSION['buId'] = $_REQUEST['buId'];
        } else {
            $_SESSION['buId'] = '';
            unset($_SESSION['buId']);
        }
        echo 'success';
        exit;
    }

    public function iof_list_ajaxAction() {
        $aColumns = array('a.id', 'a.label', 'a.submitteddate', 'a.status', 'u.fullname');
        $aColumnSort = array('a.id', 'a.label', 'a.submitteddate', 'a.status', 'u.fullname');
        $aColumnSearch = array('a.id', 'a.label', 'a.submitteddate', 'a.status', 'u.fullname');

        $sIndexColumn = "a.id";

        /* DB table to use */
        $sTable = "attachments";

        /*         * *** paging **** */
        $sLimit = "";
        if (isset($_REQUEST['iDisplayStart']) && $_REQUEST['iDisplayLength'] != '-1') {
            $sLimit = "LIMIT " . ( $_REQUEST['iDisplayStart'] ) . ", " .
                    ( $_REQUEST['iDisplayLength'] );
        }

        /*         * *** Ordering **** */
        $sOrder = '';
        if (isset($_REQUEST['iSortCol_0'])) {
            $sOrder = "ORDER BY  ";
            for ($i = 0; $i < intval($_REQUEST['iSortingCols']); $i++) {
                if ($_REQUEST['bSortable_' . intval($_REQUEST['iSortCol_' . $i])] == "true") {
                    $sOrder .= $aColumns[intval($_REQUEST['iSortCol_' . $i])] . "
						" . ( $_REQUEST['sSortDir_' . $i] ) . ", ";
                }
            }

            $sOrder = substr_replace($sOrder, "", -2);
            if ($sOrder == "ORDER BY") {
                $sOrder = "";
            }
        }

        /*         * *** Filtering **** */
        $sWhere = "";
        if (isset($_REQUEST['sSearch']) and $_REQUEST['sSearch'] != "") {
            $sWhere = "WHERE (";
            for ($i = 0; $i < count($aColumns); $i++) {
                $sWhere .= $aColumns[$i] . " LIKE '%" . ( $_REQUEST['sSearch'] ) . "%' OR ";
            }
            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere .= ')';
        }

        /* Individual column filtering */
        for ($i = 0; $i < count($aColumns); $i++) {
            if (isset($_REQUEST['bSearchable_' . $i]) and isset($_REQUEST['sSearch_' . $i]) and $_REQUEST['bSearchable_' . $i] == "true" && $_REQUEST['sSearch_' . $i] != '') {
                if ($sWhere == "") {
                    $sWhere = "WHERE ";
                } else {
                    $sWhere .= " AND ";
                }
                $sWhere .= $aColumns[$i] . " LIKE '%" . ($_REQUEST['sSearch_' . $i]) . "%' ";
            }
        }

        $and = '';
        if (isset($_REQUEST['client']) and $_REQUEST['client'] != '') {
            $arr = array();
            $arrCl = explode('##', $_REQUEST['client']);
            foreach ($arrCl as $valCl) {
                $arrCl1 = explode(' ', $valCl);
                foreach ($arrCl1 as $valCl1) {
                    if ($valCl1 != '')
                        $arr[] = 'cw.name like ("%' . $valCl1 . '%")';
                }
            }
            $and .= ' and (' . implode(' or ', $arr) . ')';
        }
        if (isset($_REQUEST['status']) and $_REQUEST['status'] != '') {
            $and .= ' and a.status in ("' . $_REQUEST['status'] . '") ';
        }
        if (isset($_REQUEST['bu']) and $_REQUEST['bu'] != '') {
            $and .= ' and ai.bu_id in (' . $_REQUEST['bu'] . ') ';
        }
        if (isset($_REQUEST['assigned_to']) and $_REQUEST['assigned_to'] != '') {
            $and .= ' and a.user_id in (' . $_REQUEST['assigned_to'] . ') ';
        }
        if (isset($_REQUEST['ace_name']) and $_REQUEST['ace_name'] != '') {
            $and .= ' and p.name like "%' . $_REQUEST['ace_name'] . '%" ';
        }
        if (isset($_REQUEST['contract_number']) and $_REQUEST['contract_number'] != '') {
            $and .= ' and p.number="' . $_REQUEST['contract_number'] . '" ';
        }
        if (isset($_REQUEST['submitted_date']) and $_REQUEST['submitted_date'] != '') {
            $and .= ' and a.submitteddate="' . $_REQUEST['submitted_date'] . '" ';
        }

        $sqlCount = 'select a.id from attachments a left join user u on (a.user_id=u.id) left join attachinfo ai on (a.id=ai.attachments_id) left join project p on (ai.project_id=p.id) left join customwave cw on (cw.id=a.customwave_id) where a.type=0 ' . $sWhere . $and . ' group by a.id ' . $sOrder;

        $sql = 'select a.id,a.label,a.submitteddate,a.status,u.fullname from attachments a left join user u on (a.user_id=u.id) left join attachinfo ai on (a.id=ai.attachments_id) left join project p on (ai.project_id=p.id) left join customwave cw on (cw.id=a.customwave_id) where a.type=0 ' . $sWhere . $and . ' group by a.id ' . $sOrder . ' ' . $sLimit;

        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();

        $statement = $connection->prepare($sqlCount);
        $statement->execute();
        /* $resultsCnt = $statement->fetchAll();
          $iTotal = $resultsCnt[0]['cntAttach']; */
        $iTotal = $statement->rowCount();

        $statement = $connection->prepare($sql);
        $statement->execute();
        $results = $statement->fetchAll();
        $iFilteredTotal = count($results);

        //* Output //		 
        $output = array(
            "order" => $sOrder,
            "q" => $sql,
            "sEcho" => intval((isset($_GET['sEcho']) ? $_GET['sEcho'] : 0)),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iTotal,
            "aaData" => array()
        );

        foreach ($results as $attach) {
            $status = '';
            if ($attach['status'] == 'approved')
                $status = '<span class="status green">Approved</span>';

            $row = '';
            $row[] = $attach['id'];
            $row[] = $attach['label'];
            $row[] = $attach['submitteddate'];
            $row[] = $status;
            $row[] = $attach['fullname'];

            $output['aaData'][] = $row;
        }
        echo json_encode($output);
        exit;
    }

    public function getProjectPrepPer($project_id, $wave_id) {
        $em = $this->getDoctrine()->getManager();

        $connection = $em->getConnection();

        $per = 0;
        //////////for getting questionnaire per////////
        $sql = 'select id from customfield where fieldtype="questionnaire" and question_status=8 and customwave_id="' . $wave_id . '"';
        $statement = $connection->prepare($sql);
        $statement->execute();
        $quesCount = $statement->rowCount();

        if ($quesCount > 0) {
            $per+=25;
        }

        //////////for getting kick of meeting per////////
        $sql = 'select id from meeting_recap where customwave_id="' . $wave_id . '"';
        $statementK = $connection->prepare($sql);
        $statementK->execute();
        $kickCount = $statementK->rowCount();

        if ($kickCount > 0) {
            $per+=25;
        }

        //////////for getting SPE Brief per////////
        $sql = 'select id from attachments where customwave_id="' . $wave_id . '" and type=0';
        $statementS = $connection->prepare($sql);
        $statementS->execute();
        $speCount = $statementS->rowCount();

        if ($speCount > 0) {
            $per+=25;
        }

        //////////for getting IOF per////////
        $sql = 'select id from customfield where fieldtype="brief" and customwave_id="' . $wave_id . '"';
        $statementI = $connection->prepare($sql);
        $statementI->execute();
        $iofCount = $statementI->rowCount();

        if ($speCount > 0) {
            $per+=25;
        }

        return $per;
    }

}
