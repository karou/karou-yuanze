<?php

namespace Albatross\ProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller {

    public function indexAction() {
        $searchArr = array('client' => '', 'type' => '', 'bu' => '', 'project_manager' => '');
        
        $scopeAndTypeOption = $this->container->getParameter('valuelist');
        $busArr = $scopeAndTypeOption['scope'];
        $arrType = $scopeAndTypeOption['type'];
        
        $client_id = '';
        if (isset($_POST['client']) and is_array($_POST['client'])) {
            $client_id = implode(',', $_POST['client']);
            $searchArr['client'] = $client_id;
        }
        $bu_id = ''; 
        if (isset($_POST['bu']) and is_array($_POST['bu'])) {
            $bu_id = implode(',', $_POST['bu']);
            $searchArr['bu'] = $bu_id;
        }
        if (isset($_POST['type']) and is_array($_POST['type'])) {
            $type = implode(',', $_POST['type']);
            $searchArr['type'] = $type;
        }
        
        $list_bu = $list_type = $list_pm = '';
        if (is_array($busArr) and count($busArr) > 0) {
            foreach ($busArr as $key => $bu) {
                $sel = '';
                if (isset($_POST['bu']) and is_array($_POST['bu']) and in_array($key, $_POST['bu']))
                    $sel = ' selected="selected"';
                $list_bu .= '<option value="' . $key . '" ' . $sel . '>' . $bu . '</option>';
            }
        }
        foreach ($arrType as $keyType => $type) {
            $sel = '';
            if (isset($_POST['type']) and is_array($_POST['type']) and in_array($keyType, $_POST['type'])) $sel = ' selected="selected"';
            $list_type .= '<option value="' . $keyType . '" ' . $sel . '>' . $type . '</option>';
        }
        $project_manager = $this->getProjectManager(); 
        if (!empty($project_manager)) {
            foreach ($project_manager as $pm) {
                $sel = '';
                if (isset($_POST['pm']) and is_array($_POST['pm']) and in_array($pm['id'], $_POST['client']))
                    $sel = ' selected="selected"';
                $list_pm .= '<option value="' . $pm['id'] . '" ' . $sel . '>' . $pm['fullname'] . '</option>';
            }
        }

        return $this->render('ProjectBundle:Default:index.html.twig', 
                array(
                    'list_bu' => $list_bu, 
                    'list_type' => $list_type, 
                    'list_pm' => $list_pm,
                    'searchArr' => $searchArr
                ));
    }

    public function viewAction() {
        if (!isset($_REQUEST['id']) or $_REQUEST['id'] == '') {
            return $this->redirect($this->generateUrl('list_project'), 301);
        }

        $em = $this->getDoctrine()->getManager();

        return $this->render('ProjectBundle:Default:project_view.html.twig');
    }

    //filter for project manager
    protected function getProjectManager() {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('u')
            ->from('AlbatrossUserBundle:User', 'u')
            ->leftJoin('u.identity', 'i')
            ->where("i.name in ('Project Manager', 'Senior Project Manager', 'BU manager')")
            ->andWhere("u.status = :active")
            ->setParameters(array('active' => 'active'));
        $query = $qb->getQuery();
        $result = $query->getArrayResult();
        return $result;
    }

}
