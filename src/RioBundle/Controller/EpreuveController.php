<?php

namespace RioBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use RioBundle\Entity\Epreuve;
use RioBundle\Form\EpreuveType;

/**
 * Epreuve controller.
 *
 */
class EpreuveController extends Controller
{
    /**
     * Lists all Epreuve entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $epreuves = $em->getRepository('RioBundle:Epreuve')->findAll();

        return $this->render('epreuve/index.html.twig', array(
            'epreuves' => $epreuves,
        ));
    }

    /**
     * Creates a new Epreuve entity.
     *
     */
    public function newAction(Request $request)
    {
        $epreuve = new Epreuve();
        $form = $this->createForm('RioBundle\Form\EpreuveType', $epreuve);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $file = $epreuve->getImage();

            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            $file->move(
               $this->getParameter('images_directory'),
               $fileName
           );

           $epreuve->setImage($fileName);


            $em->persist($epreuve);
            $em->flush();

            return $this->redirectToRoute('epreuve_show', array('id' => $epreuve->getId()));
        }

        return $this->render('epreuve/new.html.twig', array(
            'epreuve' => $epreuve,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Epreuve entity.
     *
     */
    public function showAction(Epreuve $epreuve)
    {
        $deleteForm = $this->createDeleteForm($epreuve);

        return $this->render('epreuve/show.html.twig', array(
            'epreuve' => $epreuve,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Epreuve entity.
     *
     */
    public function editAction(Request $request, Epreuve $epreuve)
    {
        $deleteForm = $this->createDeleteForm($epreuve);
        $editForm = $this->createForm('RioBundle\Form\EpreuveType', $epreuve);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($epreuve);
            $em->flush();

            return $this->redirectToRoute('epreuve_edit', array('id' => $epreuve->getId()));
        }

        return $this->render('epreuve/edit.html.twig', array(
            'epreuve' => $epreuve,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Epreuve entity.
     *
     */
    public function deleteAction(Request $request, Epreuve $epreuve)
    {
        $form = $this->createDeleteForm($epreuve);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($epreuve);
            $em->flush();
        }

        return $this->redirectToRoute('epreuve_index');
    }

    /**
     * Creates a form to delete a Epreuve entity.
     *
     * @param Epreuve $epreuve The Epreuve entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Epreuve $epreuve)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('epreuve_delete', array('id' => $epreuve->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
