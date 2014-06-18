<?php

namespace Albatross\UserBundle\EventListener;

use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Core\SecurityContext;
use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;
use Albatross\UserBundle\Entity\Log;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;

/**
 * Custom login listener.
 */
class LoginListener {

    /** @var \Symfony\Component\Security\Core\SecurityContext */
    private $securityContext;

    /** @var \Doctrine\ORM\EntityManager */
    private $em;

    /**
     * Constructor
     *
     * @param SecurityContext $securityContext
     * @param Doctrine $doctrine
     */
    public function __construct(SecurityContext $securityContext, Doctrine $doctrine) {
        $this->securityContext = $securityContext;
        $this->em = $doctrine->getEntityManager();
    }

    /**
     * Do the magic.
     *
     * @param InteractiveLoginEvent $event
     */
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event) {
//        if ($this->securityContext->isGranted('IS_AUTHENTICATED_FULLY')) {
//            $uinfo = $event->getAuthenticationToken()->getUser();
//            $aol_username = $uinfo->getAolUsername();
//            $aol_password = $uinfo->getAolPassword();
//            $aol_data = array('loginPage' => '', 'login' => $aol_username, 'password' => $aol_password);
//            $aol_data = 'loginPage=&login='.$aol_username.'&password='.$aol_password;
//            $aol_data = 'login='.$aol_username.'&password='.$aol_password.'&loginPage=';
//            $aol_data = 'login=yjin213&password=albatross123&loginPage=';
//
//            $url = 'https://www.albatrossonline.com/document.asp?alias=login';
//            $url = 'http://localhost:8081/testmysql.php';
//
//            $ch = curl_init();
//
//            curl_setopt($ch, CURLOPT_URL, $url);
//            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//            curl_setopt($ch, CURLOPT_HEADER, 1);
//            curl_setopt($ch, CURLINFO_HEADER_OUT, true);
//            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/html; Charset=UTF-8'));
//            curl_setopt($ch, CURLOPT_POST, true);
//            curl_setopt($ch, CURLOPT_POSTFIELDS, $aol_data);
//
//            $result = curl_exec($ch);
//            var_dump(curl_getinfo($ch));
//            print_r($_POST);
//            exit();
//            preg_match('/^Set-Cookie:\s*([^;]*)/mi', $result, $m);
//
//            parse_str($m[1], $cookies);
//            var_dump($aol_data);
//            var_dump($cookies);
//            foreach ($cookies as $k => $v)
//            $cookie = new Cookie($k, $v, time() + 3600 * 24 * 7, "/", "www.albatrossonline.com", false);
//            $response = new Response();
//            $response->headers->setCookie($cookie);
//            
//            return $response->send();

//        }
//        if ($this->securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
//            // user has logged in using remember_me cookie
//        }
        // do some other magic here
        $logEntity = new Log();
        $user = $event->getAuthenticationToken()->getUser();
        $positionParameter = $user->getPosition()->getParameters();
        $logEntity->setUser($user);
        $dateTime = date('Y-m-d H:i:s', time());
        if($positionParameter != null && $positionParameter != 'client'){
            $buEntity = $this->em->getRepository('AlbatrossAceBundle:Bu')->findOneByCode($positionParameter);
            $logEntity->setBu($buEntity);
        }
        $logEntity->setDateTime(new \DateTime($dateTime));
        $logEntity->setNumberPage(1);
        $this->em->persist($logEntity);
        $this->em->flush();
        // ...
    }

}