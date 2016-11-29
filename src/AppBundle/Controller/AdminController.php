<?php
/**
 * Created by PhpStorm.
 * User: kaanburaksener
 * Date: 14/10/16
 * Time: 17:20
 */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AdminController extends Controller
{
    /**
     * @Route("/admin", name="user_admin")
     */
    public function indexAction()
    {



        return $this->render(':admin:index.html.twig');


    }

    /**
     * @Route("/admin/users", name="users")
     */
    public function showuserAction()
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('AppBundle:User')->findAll();

        return $this->render('admin/showusers.html.twig', array(
            'users' => $users,
        ));
    }
}