<?php

namespace Albatross\FileBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
		$em = $this->getDoctrine()->getManager();
		
		 //
        //user access
        //
        $secu = $this->container->get('security.context');
        $user = $secu->getToken()->getUser();
		
		if(isset($_POST['hidAct']) and $_POST['hidAct']=='uploadFileList')
		{
			$connection = $em->getConnection();
			
			if(!is_dir($this->basicElements['rootPath'] . "web/otherFiles/".date('Y-m-d')))
			{
				mkdir($this->basicElements['rootPath'] . "web/otherFiles/".date('Y-m-d'),0777);
			}
	
			$this->save_image($_FILES['file_upload']['name'],"file_upload",$this->basicElements['rootPath'] . "web/otherFiles/".date('Y-m-d')."/");
			
			$path = "otherFiles/" . date('Y-m-d') . "/" . $_FILES['file_upload']['name'];
			
			if($_POST['sub_section']!='' and $_POST['sub_section']!=0)
				$filesection_id = $_POST['sub_section'];
			else
				$filesection_id = $_POST['section'];
				
			$sql = 'insert into attachments(user_id,type,status,submitteddate,filesection_id,label,path) values('.$_SESSION['user_id'].',3,"approved","'.date('Y-m-d H:i:s').'",'.$filesection_id.',"'.$_POST['file_name'].'","'.$path.'")';
			
			$statement = $connection->prepare($sql);
			$statement->execute();
			
			return $this->redirect($this->generateUrl('file_list') . '?msg=success',301);
		}
		
		$msgClass = $successOrErrMsg = '';
		if(isset($_GET['msg']) and $_GET['msg']=='success')
		{
			$msgClass = 'successMsg';
			$successOrErrMsg = 'File uploaded successfully';
		}
		
		//////////for getting custom client detail////////////
		$sectionqb = $em->createQueryBuilder();
        $sectionqb->select('f')
                ->from('AlbatrossAceBundle:Filesection', 'f')
				->orderBy('f.name', 'ASC');
		
		$sectionQry = $sectionqb->getQuery();
        $sectionArr = $sectionQry->getArrayResult();
		
		$list_section = '';
		if(is_array($sectionArr) and count($sectionArr) > 0)
		{
			foreach($sectionArr as $section)
			{
				$list_section .= '<option value="'.$section['id'].'">'.$section['name'].'</option>';
			}
		}
		
		return $this->render('FileBundle:Default:index.html.twig', array('list_section' => $list_section,'msgClass' => $msgClass,'successOrErrMsg' => $successOrErrMsg));
    }
}
