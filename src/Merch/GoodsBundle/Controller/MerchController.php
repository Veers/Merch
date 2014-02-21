<?php

namespace Merch\GoodsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Merch\GoodsBundle\Entity\Merch;

use Merch\GoodsBundle\Form\MerchType;


/**
 * Merch controller.
 *
 */
class MerchController extends Controller
{

    /**
     * Lists all Merch entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MerchGoodsBundle:Merch')->findByIsdeleted(0);

        $form = $this->createNewItemForm();

        return $this->render('MerchGoodsBundle:Merch:index.html.twig', array(
            'entities' => $entities,
            'new_form' => $form->createView(),
        ));
    }

    /**
     * Creates a new Merch entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Merch();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        $entity->setIsdeleted(0);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setIsdeleted(0);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('merch_show', array('id' => $entity->getId())));
        }

        return $this->render('MerchGoodsBundle:Merch:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Merch entity.
     *
     * @param Merch $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Merch $entity)
    {
        $form = $this->createFormBuilder($entity)
            ->setAction($this->generateUrl('merch_create'))
            ->setMethod('POST')
            ->add('title', 'text')
            ->add('price', 'integer')
            ->add('info', 'textarea')
            ->add('create', 'submit')
            ->getForm();
        return $form;
    }

    /**
     * Returns to preview elements
     */
    private function createBackForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('merch', array('id' => $id)))
            ->add('submit', 'submit', array('label' => 'Back to the list'))
            ->getForm();

        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MerchGoodsBundle:Merch')->findByIsdeleted(0);

        return $this->render('MerchGoodsBundle:Merch:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    private function createNewItemForm()
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('merch_new'))
            ->add('submit', 'submit', array('label' => 'Add new title'))
            ->getForm();
    }

    /**
     * Displays a form to create a new Merch entity.
     *
     */
    public function newAction()
    {
        $entity = new Merch();
        $form = $this->createCreateForm($entity);

        return $this->render('MerchGoodsBundle:Merch:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Merch entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MerchGoodsBundle:Merch')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Merch entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MerchGoodsBundle:Merch:show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Displays a form to edit an existing Merch entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MerchGoodsBundle:Merch')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Merch entity.');
        }

        $editForm = $this->createEditForm($entity);
        $backForm = $this->createBackForm($id);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MerchGoodsBundle:Merch:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'back_form' => $backForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Merch entity.
     *
     * @param Merch $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Merch $entity)
    {
        $form = $this->createForm(new MerchType(), $entity, array(
            'action' => $this->generateUrl('merch_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Merch entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MerchGoodsBundle:Merch')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Merch entity');
        }

        $deleteForm = $this->createDeleteForm($id);

        $editForm = $this->createEditForm($entity);

        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('merch_edit', array('id' => $id)));
        }

        return $this->render('MerchGoodsBundle:Merch:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Merch entity.
     *
     */

    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MerchGoodsBundle:Merch')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Merch entity.');
            }

            $entity->setIsdeleted(1);

            $em->flush();
        }

        return $this->redirect($this->generateUrl('merch'));
    }

    public function newdeleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('MerchGoodsBundle:Merch')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('ERROR TO FIND ENTITY');
        }

        $entity->setIsdeleted(1);
        $em->flush();

        return $this->redirect($this->generateUrl('merch'));
    }

    /**
     * Creates a form to delete a Merch entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('merch_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->setAttribute('role', 'form')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm();
    }
}
