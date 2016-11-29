<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\AddProduct;
use AppBundle\Form\AddProductType;

/**
 * AddProduct controller.
 *
 * @Route("/addproduct")
 */
class AddProductController extends Controller
{
    /**
     * Lists all AddProduct entities.
     *
     * @Route("/", name="addproduct_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $addProducts = $em->getRepository('AppBundle:AddProduct')->findAll();

        return $this->render('addproduct/index.html.twig', array(
            'addProducts' => $addProducts,
        ));
    }

    /**
     * Creates a new AddProduct entity.
     *
     * @Route("/new", name="addproduct_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $addProduct = new AddProduct();
        $form = $this->createForm('AppBundle\Form\AddProductType', $addProduct);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($addProduct);
            $em->flush();

            return $this->redirectToRoute('addproduct_show', array('id' => $addProduct->getId()));
        }

        return $this->render('addproduct/new.html.twig', array(
            'addProduct' => $addProduct,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a AddProduct entity.
     *
     * @Route("/{id}", name="addproduct_show")
     * @Method("GET")
     */
    public function showAction(AddProduct $addProduct)
    {
        $deleteForm = $this->createDeleteForm($addProduct);

        return $this->render('addproduct/show.html.twig', array(
            'addProduct' => $addProduct,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing AddProduct entity.
     *
     * @Route("/{id}/edit", name="addproduct_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, AddProduct $addProduct)
    {
        $deleteForm = $this->createDeleteForm($addProduct);
        $editForm = $this->createForm('AppBundle\Form\AddProductType', $addProduct);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($addProduct);
            $em->flush();

            return $this->redirectToRoute('addproduct_edit', array('id' => $addProduct->getId()));
        }

        return $this->render('addproduct/edit.html.twig', array(
            'addProduct' => $addProduct,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a AddProduct entity.
     *
     * @Route("/{id}", name="addproduct_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, AddProduct $addProduct)
    {
        $form = $this->createDeleteForm($addProduct);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($addProduct);
            $em->flush();
        }

        return $this->redirectToRoute('addproduct_index');
    }

    /**
     * Creates a form to delete a AddProduct entity.
     *
     * @param AddProduct $addProduct The AddProduct entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(AddProduct $addProduct)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('addproduct_delete', array('id' => $addProduct->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
