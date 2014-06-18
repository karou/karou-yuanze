<?php

// src/Albatross/UserBundle/EventListener/KernelRequestListener.php
namespace Albatross\UserBundle\EventListener;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\SecurityContext;
use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;
use Albatross\UserBundle\Entity\Log;
class KernelRequestListener
{
    private $context;
    
    private $em;
    
    public function __construct(SecurityContext $context, Doctrine $doctrine) {
        $this->context = $context;
        $this->em = $doctrine->getEntityManager();
    }
    
    public function onKernelRequest(GetResponseEvent $event)
    {
        $param = $this->getPageInfo($event->getRequest()->getPathInfo());

        if(!empty($param)){
            $this->saveLog($param[0], $param[1]);
        }
    }
    
    protected function getPageInfo($path){
        //save visited pages
        if($path == '/projectStatus')
            return array(1, 2);
        if($path == '/daily')
            return array(1, 3);
        preg_match("/^\/Customproject\/\d*\/show/", $path, $matchesVisitProject);
        if(!empty($matchesVisitProject))
            return array(1, 4);
        if($path == '/forecast')
            return array(1, 5);
        
        
        //save actions
        if($path == '/savepmedit')
            return array(2, 4);
        preg_match("/^\/Customproject\/saveField\/\w*/", $path, $matchesSaveField);
        preg_match("/^\/Customproject\/saveupload\/w*/", $path, $matchesSavePos);
        preg_match("/^\/Customproject\/IOFFileUpload\/$/", $path, $matchesSaveIOF);
        preg_match("/^\/saverecap/", $path, $matchesSaveRecap);
        preg_match("/^\/Customwave\/create/", $path, $matchesWaveCreate);
        preg_match("/^\/Customwave\/\d*\/update/", $path, $matchesWaveUpdate);

        if(!empty($matchesSaveField) || !empty($matchesSavePos) || !empty($matchesSaveIOF)
                || !empty($matchesSaveRecap) || !empty($matchesWaveCreate) || !empty($matchesWaveUpdate)){
            return array(2, 5);
        }
        
        return array();
    }
    
    protected function saveLog($type, $index){
        $logEntity = new Log();
        $user = $this->context->getToken()->getUser();
        $positionParameter = $user->getPosition()->getParameters();
        $logEntity->setUser($user);
        $dateTime = date('Y-m-d H:i:s', time());
        if($positionParameter != null && $positionParameter != 'client'){
            $buEntity = $this->em->getRepository('AlbatrossAceBundle:Bu')->findOneByCode($positionParameter);
            $logEntity->setBu($buEntity);
        }
        $logEntity->setDateTime(new \DateTime($dateTime));
        if($type == 1){
            $logEntity->setNumberPage($index);
        }else if($type == 2){
            $logEntity->setNumberAction($index);
        }
        $this->em->persist($logEntity);
        $this->em->flush();
    }
}