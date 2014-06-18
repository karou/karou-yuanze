<?php

namespace Albatross\CustomBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Albatross\CustomBundle\Entity\Poslist;
use Albatross\CustomBundle\Form\PoslistType;
use Albatross\CustomBundle\Entity\Poslistdata;
use \SplFileObject;
/**
 * Poslist controller.
 *
 */
class PoslistController extends Controller
{
    /**
     * Lists all Poslist entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AlbatrossCustomBundle:Poslist')->findAll();

        return $this->render('AlbatrossCustomBundle:Poslist:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new Poslist entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new Poslist();
        $form = $this->createForm(new PoslistType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('poslist_show', array('id' => $entity->getId())));
        }

        return $this->render('AlbatrossCustomBundle:Poslist:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to create a new Poslist entity.
     *
     */
    public function newAction()
    {
        $entity = new Poslist();
        $form   = $this->createForm(new PoslistType(), $entity);

        return $this->render('AlbatrossCustomBundle:Poslist:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Poslist entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AlbatrossCustomBundle:Poslist')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Poslist entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('AlbatrossCustomBundle:Poslist:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Poslist entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AlbatrossCustomBundle:Poslist')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Poslist entity.');
        }

        $editForm = $this->createForm(new PoslistType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('AlbatrossCustomBundle:Poslist:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Poslist entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AlbatrossCustomBundle:Poslist')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Poslist entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new PoslistType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('poslist_edit', array('id' => $id)));
        }

        return $this->render('AlbatrossCustomBundle:Poslist:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Poslist entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AlbatrossCustomBundle:Poslist')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Poslist entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('poslist'));
    }

    /**
     * Creates a form to delete a Poslist entity by id.
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

    public function uploadAction($id){
        $em = $this->getDoctrine()->getManager();
        $cwave = $em->getRepository('AlbatrossCustomBundle:Customwave')->findOneById($id);
        $pname = $cwave->getCustomproject()->getName();
        $wname = $cwave->getName();
        $form = $this->createForm(new PoslistType());
        return $this->render('AlbatrossCustomBundle:Poslist:upload.html.twig', array(
                    'form' => $form->createView(),
                    'wid' => $id,
                    'pname' => $pname,
                    'wname' => $wname
        ));
    }

    public function saveuploadAction($wid){
        $em = $this->getDoctrine()->getManager();
        $entity = new Poslist();
        $form = $this->createForm(new PoslistType(), $entity);
        $form->bindRequest($this->getRequest());
        $cwave = $em->getRepository('AlbatrossCustomBundle:Customwave')->findOneById($wid);
        $pid = $cwave->getCustomproject()->getId();
        $entity->setCustomwave($cwave);
        $entity->upload();
        $path = $entity->getWebPath();
        $entity->setPath($path);
        $entity->setSubmittime(date('Y-m-d', time()));
        $em->persist($entity);
        $em->flush();

        $this->readExcelFile($path, $entity);
        return $this->redirect($this->generateUrl('customproject_show', array('id' => $pid)));
    }

    protected function readExcelFile($path, $poslistobj) {
        $referer = $this->getRequest()->headers->get('referer');
        $pathInfo = explode('/', $path);
        $targetDir = $pathInfo[0].'/'.$pathInfo[1].'/'.$pathInfo[2];
        if (is_dir($targetDir)) {
            $file = scandir($targetDir);
            foreach ($file as $f) {
                if (pathinfo($targetDir . $f, PATHINFO_EXTENSION) && pathinfo($targetDir . $f, PATHINFO_EXTENSION) == 'csv') {
                        $this->analysis($path, $poslistobj);
                }
            }
        }

        return;
    }

    protected function analysis($path, $poslistobj){
        $em = $this->getDoctrine()->getManager();

        $csv = new SplFileObject($path);
        $excelArr = array();
        while (!$csv->eof()) {
            $excelArr[] = $csv->fgetcsv();
        }

        $titleLine = '';
        foreach ( $excelArr as $key => $line) {
            if($line[0] == 'Store ID'){
                $titleLine = $key;
                break;
            }
        }

        $titleArr = array();

        foreach ($excelArr[$titleLine] as $k => $t){
            $titleArr[$t] = $k;
        }

        foreach ( $excelArr as $key => $line){
            if( $key > $titleLine ){
                if(($line[0] != null) && ($line[0] != '')){
                    $entity = new Poslistdata();
                    $entity->setPoslist($poslistobj);
                    $entity->setStoreId($line[$titleArr['Store ID']]);
                    $entity->setLocationName($line[$titleArr['Location Name']]);
                    $entity->setNumberOfVisits($line[$titleArr['Number of visits']]);
                    $entity->setStoreName($line[$titleArr['Store name']]);
                    $entity->setAddress($line[$titleArr['Address']]);
                    $entity->setAddress2($line[$titleArr['Address 2']]);
                    $entity->setCity($line[$titleArr['City']]);
                    $entity->setCounty($line[$titleArr['County']]);
                    $entity->setStateRegion($line[$titleArr['State/Region']]);
                    $entity->setPostalCode($line[$titleArr['Postal Code']]);
                    $entity->setCountrys($line[$titleArr['Country']]);
                    $entity->setPhone($line[$titleArr['Phone']]);
                    $entity->setFax($line[$titleArr['Fax']]);
                    $entity->setLocationHours($line[$titleArr['Location Hours']]);
                    $entity->setExportEmail($line[$titleArr['Export Email']]);
                    $entity->setExportEmailName($line[$titleArr['Export Email Name']]);
                    $entity->setExportLanguage($line[$titleArr['Export Language']]);
                    $entity->setLocationStatus($line[$titleArr['Location Status']]);
                    $entity->setLocationPhotoUrl($line[$titleArr['Location Photo URL']]);
                    $entity->setLatitude($line[$titleArr['Latitude']]);
                    $entity->setLongitude($line[$titleArr['Longitude']]);
                    $entity->setDateGeocodeAcquired($line[$titleArr['Date Geocode Acquired']]);
                    $entity->setGeocodeSource($line[$titleArr['Geocode Source']]);
                    $entity->setAdditionalComments($line[$titleArr['Additional Comments']]);
                    $entity->setSummaryLabel($line[$titleArr['Summary Label']]);
                    $entity->setSummaryContent($line[$titleArr['Summary Content']]);
                    $entity->setSummaryDisplay($line[$titleArr['Summary Display']]);
                    $entity->setRegion($line[$titleArr['Region']]);
                    
                    $countryEntity = $em->getRepository('AlbatrossAceBundle:Country')->findOneByCode($line[$titleArr['Country']]);
                    $entity->setCountry($countryEntity);
                    $em->persist($entity);
                }
            }
        }
        $em->flush();

        return;
    }
}