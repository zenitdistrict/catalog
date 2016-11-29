<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use AppBundle\Form\UserRegistrationType;
use AppBundle\Form\ForgotPasswordType;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use AppBundle\Form\RecoveryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * User controller.
 *
 * @Route("/user")
 */
class UserController extends Controller
{
    /**
     * @Route("/login", name="user_login")
     */
    public function loginAction()
    {
        if ($this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('homepage');
        }

        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();



        return $this->render(':user:login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error
        ]);
    }

    /**
     * @Route("/register", name="user_register")
     */
    public function registerAction(Request $request)
    {
        if ($this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('homepage');
        }
        // create a new user
        $user = new User();

        $form = $this->createForm(UserRegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // encrypt the password entered
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());

            $user->setPassword($password);

            // save the user
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('user_login');
        }

        return $this->render(':user:register.html.twig', [
            'registrationForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/forgotpassword", name="forgotpassword")
     */
    public function forgotpasswordAction(Request $request)
    {
        if ($this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('homepage');
        }

        $user = new User();
        $form = $this->createForm(ForgotPasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            //$user1 = new User();
            $em = $this->getDoctrine()->getRepository('AppBundle:User');
            $user = $em->findOneBy(['email' => $user->getEmail()]);
            $user->setToken($user->generateToken());
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
//            $dom = new \DOMDocument();
//            $e = $dom->createElement('a', 'click here to recover your password');
//            $e->setAttribute('href', $this->generateUrl('user_recovery', array('token' => $user1->getToken()), UrlGeneratorInterface::ABSOLUTE_URL));
//            $dom->appendChild($e);
//            $dom->saveHTML();




            $message = \Swift_Message::newInstance()
                ->setSubject('Hello Loh')
                ->setFrom('zenitdistrict@gmail.com')
                ->setTo($user->getEmail())

                ->setBody('Hi there, ' . '<a href=' . $this->generateUrl('user_recovery', array('token' => $user->getToken()), UrlGeneratorInterface::ABSOLUTE_URL) . '>click here to change your password</a>', 'text/html');
            $this->get('mailer')->send($message);

            //$em = $this->getDoctrine()->getRepository('AppBundle:Product');
            //$product = $em->findOneBy(['description' => 'sdfgsdfgsd']);


            return $this->redirectToRoute('user_question');
        }

        return $this->render(':user:forgotpassword.html.twig', [
            'forgotpasswordForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/question", name="user_question")
     */
    public function questionAction()
    {
        if ($this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('homepage');
        }


        return $this->render(':user:question.html.twig');
    }
    /**
     * @Route("/recovery/{token}", name="user_recovery")
     */
    public function recoveryAction(Request $request, $token)
    {
        if ($this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('homepage');
        }

        $user = new User();
        $em = $this->getDoctrine()->getRepository('AppBundle:User');
        $user = $em->findOneBy(['token' => $token]);

        $form = $this->createForm(RecoveryType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted())
        {
            // encrypt the password entered
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());

            $user->setPassword($password);
            $user->setToken(null);

            // save the user
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('user_login');
        }

        return $this->render(':user:recovery.html.twig', [
            'recoveryForm' => $form->createView()
        ]);
    }



    /**
     * Lists all User entities.
     *
     * @Route("/", name="user_index")
     * @Security("has_role('ROLE_ADMIN')")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em    = $this->get('doctrine.orm.entity_manager');
        $dql   = "SELECT a FROM AppBundle:User a";
        $query = $em->createQuery($dql);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            9/*limit per page*/
        );
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('AppBundle:User')->findAll();
        return $this->render('user/index.html.twig', array(
            'users' => $users,
            'pagination' => $pagination,
        ));
    }



    /**
     * Finds and displays a User entity.
     *
     * @Route("/{id}", name="user_show")
     * @Security("has_role('ROLE_ADMIN')")
     * @Method("GET")
     */
    public function showAction(User $user)
    {
        $deleteForm = $this->createDeleteForm($user);

        return $this->render('user/show.html.twig', array(
            'user' => $user,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     * @Route("/{id}/edit", name="user_edit")
     * @Security("has_role('ROLE_ADMIN')")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, User $user)
    {
        $deleteForm = $this->createDeleteForm($user);
        $editForm = $this->createForm('AppBundle\Form\UserType', $user);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('user_edit', array('id' => $user->getId()));
        }

        return $this->render('user/edit.html.twig', array(
            'user' => $user,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a User entity.
     *
     * @Route("/{id}", name="user_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, User $user)
    {
        $form = $this->createDeleteForm($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
        }

        return $this->redirectToRoute('user_index');
    }

    /**
     * Creates a form to delete a User entity.
     *
     * @param User $user The User entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(User $user)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('user_delete', array('id' => $user->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
