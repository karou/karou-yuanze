<?php

namespace Albatross\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Albatross\UserBundle\Entity\User;
use Albatross\UserBundle\Form\UserType;
use Albatross\UserBundle\Form\PasswordType;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * User controller.
 *
 */
class UserController extends Controller {

    public function loginAction($current) {
        $request = $this->getRequest();
        $session = $request->getSession();
        $secu = $this->container->get('security.context');
        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                    SecurityContext::AUTHENTICATION_ERROR
            );
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        if ($secu->isGranted('ROLE_USER') || $secu->isGranted('ROLE_CLIENT')) {
            return $this->render(
                            'AlbatrossUserBundle:Default:index.html.twig', array(
                        'current' => 'homepage'
                            )
            );
        } else {
            return $this->render(
                            'AlbatrossUserBundle:User:login.html.twig', array(
                        'last_username' => $session->get(SecurityContext::LAST_USERNAME),
                        'error' => $error,
                        'current' => $current
                            )
            );
        }
    }

    /**
     * Lists all User entities.
     *
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AlbatrossUserBundle:User')->findBy(array(),array('status' => 'asc','username'=>'asc'));

        return $this->render('AlbatrossUserBundle:User:index.html.twig', array(
                    'entities' => $entities,
                    'menu_bar' => 'admin',
                    'menu_cal_cur' => 'user',
        ));
    }

    /**
     * Creates a new User entity.
     *
     */
    public function createAction(Request $request) {
        $entity = new User();
        $form = $this->createForm(new UserType(), $entity);
        $form->bind($request);
        $data = $form->getData();
        
        $em = $this->getDoctrine()->getManager();
        if ($data->getType() == 1) {
            $idEntity = $em->getRepository('AlbatrossUserBundle:Identity')->findOneByParameters('client');
            $positionEntity = $em->getRepository('AlbatrossUserBundle:Position')->findOneByParameters('client');
            $entity->setIdentity($idEntity);
            $entity->setPosition($positionEntity);
        }
        if ($form->isValid()) {
            $entity->setStatus('active');
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('user'));
        }else{
            var_dump($form->getErrorsAsString());
            exit();
        }
        $menu_bar = 'admin';
        return $this->render('AlbatrossUserBundle:User:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                    'menu_bar' => $menu_bar,
                    'menu_cal_cur' => 'user',
        ));
    }

    public function adminEditAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AlbatrossUserBundle:User')->find($id);

        $form = $this->createForm(new UserType(), $entity);
        $pw = $entity->getPassword();
        $pwAol = $entity->getAolPassword(true);
        $pwAce = $entity->getAcePassword(true);
        $pwCrm = $entity->getCrmPassword(true);
        $form->bind($request);
        if ($form->isValid()) {
            $entity->setPassword($pw, false);
            $entity->setAolPassword($pwAol);
            $entity->setAcePassword($pwAce);
            $entity->setCrmPassword($pwCrm);
            $entity->upload();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('user'));
        }
    }

    /**
     * Displays a form to create a new User entity.
     *
     */
    public function newAction($type = 1, $current = 'admin') {
        $entity = new User();
        $form = $this->createForm(new UserType(), $entity);
        $menu_bar = 'admin';
        if ($type == 0) {
            $menu_bar = 'register';
        }
        return $this->render('AlbatrossUserBundle:User:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                    'menu_bar' => $menu_bar,
                    'current' => $current,
                    'menu_cal_cur' => 'user',
        ));
    }

    /**
     * Finds and displays a User entity.
     *
     */
    public function showAction($id, $current) {
        if ($id == 0) {
            $secu = $this->container->get('security.context');

            $entity = $secu->getToken()->getUser();
        } else {
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('AlbatrossUserBundle:User')->find($id);
        }
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $menu_bar = 'user';

        $editForm = $this->createForm(new PasswordType(), $entity);
        // for check old password
        if ($entity->getPassword() == null) {
            $platform = 0;
        } else {
            $platform = 1;
        }

        if ($entity->getAolPassword() == null) {
            $aol = 0;
        } else {
            $aol = 1;
        }

        if ($entity->getAcePassword() == null) {
            $ace = 0;
        } else {
            $ace = 1;
        }

        if ($entity->getCrmPassword() == null) {
            $crm = 0;
        } else {
            $crm = 1;
        }
        return $this->render('AlbatrossUserBundle:User:show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),
                    'current' => $current,
                    'menu_bar' => $menu_bar,
                    'menu_cal_cur' => 'user_show',
                    'edit_form' => $editForm->createView(),
                    'platform' => $platform,
                    'aol' => $aol,
                    'ace' => $ace,
                    'crm' => $crm
        ));
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     */
    public function editAction($id, $key, $current) {
        if ($id == 0) {
            $secu = $this->container->get('security.context');

            $entity = $secu->getToken()->getUser();
        } else {
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('AlbatrossUserBundle:User')->find($id);
        }

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }
        $old = false;
        if ($key == '') {
            $editForm = $this->createForm(new UserType(), $entity);
        } else {
            $editForm = $this->createForm(new PasswordType(), $entity);
            if (($key == 'password' && $entity->getPassword()) || ($key == 'ace_password' && $entity->getAcePassword()) || ($key == 'aol_password' && $entity->getAolPassword()))
                $old = true;
        }

        $deleteForm = $this->createDeleteForm($id);
        $menu_bar = 'user';
        return $this->render('AlbatrossUserBundle:User:edit.html.twig', array(
                    'entity' => $entity,
                    'form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
                    'key' => $key,
                    'current' => $current,
                    'old' => $old,
                    'menu_bar' => $menu_bar,
                    'menu_cal_cur' => 'user_edit',
        ));
    }

    /**
     * Edits an existing User entity.
     *
     */
    public function updateAction(Request $request, $id, $key = '', $current) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AlbatrossUserBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        $old = false;
        if ($key == '') {
            $editForm = $this->createForm(new UserType(), $entity);
        } else {
            $editForm = $this->createForm(new PasswordType(), $entity);
            if (($key == 'password' && $entity->getPassword()) || ($key == 'ace_password' && $entity->getAcePassword()) || ($key == 'aol_password' && $entity->getAolPassword()))
                $old = true;
        }

        $pw = $entity->getPassword();
        $pwAol = $entity->getAolPassword(true);
        $pwAce = $entity->getAcePassword(true);

        $op = $request->request->get('old_password');
        if ($key == '' || ($key == 'password' && sha1($op) == $pw) || ($key == 'aol_password' && $op == $pwAol) || ($key == 'ace_password' && $op == $pwAce)) {
            $editForm->bind($request);
            if ($editForm->isValid()) {
                if ($key != 'password')
                    $entity->setPassword($pw, false);
                if ($key != 'aol_password')
                    $entity->setAolPassword($pwAol);
                if ($key != 'ace_password')
                    $entity->setAcePassword($pwAce);
                //file upload
                $entity->upload();

                $validator = $this->get('validator');
                $errors = $validator->validate($entity);
                if (count($errors) > 0) {
                    return new Response(print_r($errors, true));
                }
                $em->persist($entity);
                $em->flush();

                return $this->redirect($this->generateUrl('user_profile'));
            }
        }
        return $this->render('AlbatrossUserBundle:User:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
                    'key' => $key,
                    'current' => $current,
                    'old' => $old,
        ));
    }

    /**
     * Deletes a User entity.
     *
     */
//    public function deleteAction(Request $request, $id) {
//        $form = $this->createDeleteForm($id);
//        $form->bind($request);
//
//        if ($form->isValid()) {
//            $em = $this->getDoctrine()->getManager();
//            $entity = $em->getRepository('AlbatrossUserBundle:User')->find($id);
//
//            if (!$entity) {
//                throw $this->createNotFoundException('Unable to find User entity.');
//           }
//
//            $em->remove($entity);
//            $em->flush();
//        }

//        return $this->redirect($this->generateUrl('user'));
//    }
    public function deleteAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AlbatrossUserBundle:User')->findOneById($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }
        $entity->setStatus('deleted');
        $em->persist($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('user'));
    }
    public function enableAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AlbatrossUserBundle:User')->findOneById($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }
        $entity->setStatus('active');
        $em->persist($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('user'));
    }
    public function editAjaxAction($id) {
        $content = $this->getRequest()->getContent();
        $content = json_decode(trim($content, '\''), true);
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AlbatrossUserBundle:User')->find($id);
        foreach ($content as $key => $c) {
            switch (trim($key)) {
                case 'fullname':
                    $entity->setFullname(trim($c));
                    break;
                case 'username':
                    $entity->setUsername(trim($c));
                    break;
                case 'aolusername':
                    $entity->setAolUsername(trim($c));
                    break;
                case 'aceusername':
                    $entity->setAceUsername(trim($c));
                    break;
                case 'crmusername':
                    $entity->setCrmUsername(trim($c));
                    break;
                case 'email':
                    $entity->setEmail(trim($c));
                    break;
                case 'title':
                    $entity->setTitle(trim($c));
                    break;
                case 'skype':
                    $entity->setSkype(trim($c));
                    break;
                case 'mobile':
                    $entity->setMobile(trim($c));
                    break;
                case 'officephone':
                    $entity->setOfficePhone(trim($c));
                    break;
                case 'country':
                    $entity->setCountry(trim($c));
                    break;
                case 'officeaddress':
                    $entity->setOfficeAddress(trim($c));
                    break;
            }
        }
        $em->persist($entity);
        $em->flush();
        $new_user = $em->getRepository('AlbatrossUserBundle:User')->find($id);
        $result = '{"fullname":"' . $new_user->getFullname() . '",';
        $result .= '"username":"' . $new_user->getUsername() . '",';
        $result .= '"aolusername":"' . $new_user->getAolUsername() . '",';
        $result .= '"aceusername":"' . $new_user->getAceUsername() . '",';
        $result .= '"crmusername":"' . $new_user->getCrmUsername() . '",';
        $result .= '"email":"' . $new_user->getEmail() . '",';
        $result .= '"title":"' . $new_user->getTitle() . '",';
        $result .= '"skype":"' . $new_user->getSkype() . '",';
        $result .= '"mobile":"' . $new_user->getMobile() . '",';
        $result .= '"officephone":"' . $new_user->getOfficePhone() . '",';
        $result .= '"country":"' . $new_user->getCountry() . '",';
        $result .= '"officeaddress":"' . $new_user->getOfficeAddress() . '"}';
        return new Response($result);
    }

    public function savePicAction($id){
        $em = $this->getDoctrine()->getManager();
        $content = $this->getRequest()->files->get('file0'); //to get file object
        $entity = $em->getRepository('AlbatrossUserBundle:User')->find($id);
        $entity->setFile($content);
        $entity->upload();
        $pic = $entity->getWebPath();
        $em->persist($entity);
        $em->flush();
        return new Response($pic);
    }
    /**
     * Creates a form to delete a User entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {
        return $this->createFormBuilder(array('id' => $id))
                        ->add('id', 'hidden')
                        ->getForm()
        ;
    }

    public function changePasswordAction($type, $id) {
        $content = $this->getRequest()->getContent();
        $content = rtrim($content, ',');
        $content = explode(',', $content);

        if ($content[1] !== $content[2]) {
            return new Response('<font style="color:red; font-size:12px">New Password and confirm password must be the same.</font>');
        }
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('AlbatrossUserBundle:User')->find($id);
        $pw = $entity->getPassword();
        $aolpw = $entity->getAolPassword(true);
        $acepw = $entity->getAcePassword(true);
        $crmpw = $entity->getCrmPassword(true);

        switch ($type) {
            case 'platform':
                if (sha1($content[0]) == $pw || $content[0] == '')
                    $entity->setPassword($content[1], true);
                else
                    return new Response('<font style="color:red; font-size:12px">The old password is incorrect.</font>');
                break;
            case 'aol':
                if ($content[0] == $aolpw || $content[0] == '')
                    $entity->setAolPassword($content[1], true);
                else
                    return new Response('<font style="color:red; font-size:12px">The old password is incorrect.</font>');
                break;
            case 'ace':
                if ($content[0] == $acepw || $content[0] == '')
                    $entity->setAcePassword($content[1], true);
                else
                    return new Response('<font style="color:red; font-size:12px">The old password is incorrect.</font>');
                break;
            case 'crm':
                if ($content[0] == $crmpw || $content[0] == '')
                    $entity->setCrmPassword($content[1], true);
                else
                    return new Response('<font style="color:red; font-size:12px">The old password is incorrect.</font>');
                break;
        }
        $em->persist($entity);
        $em->flush();

        return new Response('<font style="color:green; font-size:12px">Change the password successfully.</font>');
    }

    public function getBindProjectAction() {
        $data = $this->getRequest()->getContent();
        $dataArr = explode(':', $data);
        $em = $this->getDoctrine()->getManager();

        $qb = $em->createQueryBuilder();
        $qb->add('select', 'c')
                ->add('from', 'AlbatrossCustomBundle:Customproject c')
                ->leftJoin('c.customclient', 'client')
                ->where('client.id = :sql_0');
        foreach ($dataArr as $k => $d) {
            if ($k != 0)
                $qb->orWhere("client.id = :sql_$k");
        }
        $parameter = array();
        foreach ($dataArr as $k => $d) {
            $parameter = array_merge($parameter, array(
                "sql_$k" => $d));
        }
        $qb->setParameters($parameter);
        $result = $qb->getQuery()->getArrayResult();
        $resultArr = '';
        foreach ($result as $re) {
            $resultArr .= '<option value="' . $re['id'] . '">' . $re['name'] . '</option>';
        }
        return new Response($resultArr);
    }

}
