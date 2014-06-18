<?php

namespace Albatross\IofBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
		$searchArr = array('client'=>'','status'=>'','bu'=>'','assigned_to'=>'','ace_name'=>'','contract_number'=>'','submitted_date'=>'');
		if(isset($_POST['hidAct']) and $_POST['hidAct']=='searchIosList')
		{	
			if(isset($_POST['client']) and $_POST['client']!='')
				$searchArr['client'] = urlencode(implode('##',$_POST['client']));
			else
				$searchArr['client'] = '';
			
			if(isset($_POST['status']) and $_POST['status']!='')	
				$searchArr['status'] = implode('","',$_POST['status']);
			else
				$searchArr['status'] = '';
				
			if(isset($_POST['bu']) and $_POST['bu']!='')	
				$searchArr['bu'] = implode('","',$_POST['bu']);
			else
				$searchArr['bu'] = '';
				
			if(isset($_POST['assigned_to']) and $_POST['assigned_to']!='')	
				$searchArr['assigned_to'] = implode('","',$_POST['assigned_to']);
			else
				$searchArr['assigned_to'] = '';
				
			//$searchArr['bu'] = implode(',',$_POST['bu']);
			//$searchArr['assigned_to'] = implode(',',$_POST['assigned_to']);
			$searchArr['ace_name'] = $_POST['ace_name'];
			$searchArr['contract_number'] = $_POST['contract_number'];
			$searchArr['submitted_date'] = $_POST['submitted_date'];
		}
		
		$em = $this->getDoctrine()->getManager();
		
		/*$attachqb = $em->createQueryBuilder();
        $attachqb->select('a,u')
                ->from('AlbatrossAceBundle:Attachments', 'a')
				->leftJoin('a.user', 'u')
				->Where('a.type = 0');
		
		$attachquery = $attachqb->getQuery();
        $attachArr = $attachquery->getArrayResult();
		
		echo '<pre>';print_r($attachArr);exit;*/
		
		$clientsqb = $em->createQueryBuilder();
        $clientsqb->select('c')
                ->from('AlbatrossAceBundle:Customclient', 'c')
				->orderBy('c.name', 'ASC');
		
		$clientsquery = $clientsqb->getQuery();
        $clientsArr = $clientsquery->getArrayResult();
		
		$usersqb = $em->createQueryBuilder();
        $usersqb->select('u')
                ->from('AlbatrossAceBundle:User', 'u')
				->Where('u.status >= :stat')
				->setParameter('stat', "active")
				->orderBy('u.fullname', 'ASC');
		
		$usersquery = $usersqb->getQuery();
        $usersArr = $usersquery->getArrayResult();
		
		$busqb = $em->createQueryBuilder();
        $busqb->select('b')
                ->from('AlbatrossAceBundle:Bu', 'b')
				->orderBy('b.name', 'ASC');
		
		$busquery = $busqb->getQuery();
        $busArr = $busquery->getArrayResult();
		
		return $this->render('IofBundle:Default:index.html.twig', array('clientsArr' => $clientsArr,'usersArr' => $usersArr,'busArr' => $busArr, 'searchArr' => $searchArr));
    }
	
	public function viewAction()
	{
		return $this->render('IofBundle:Default:iof_view.html.twig');
	}
}
