<?php

namespace Albatross\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('AlbatrossCoreBundle:Default:index.html.twig');
    }
}
