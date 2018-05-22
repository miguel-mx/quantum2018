<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Registration;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Registration controller.
 *
 * @Route("registration")
 */
class RegistrationController extends Controller
{
    /**
     * Lists all registration entities.
     *
     * @Route("/", name="registration_index")
     * @Method("GET")
     */
    public function indexAction()
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        $em = $this->getDoctrine()->getManager();

        $registrations = $em->getRepository('AppBundle:Registration')->findAll();

        return $this->render('registration/index.html.twig', array(
            'registrations' => $registrations,
        ));
    }

    /**
     * Creates a new registration entity.
     *
     * @Route("/new", name="registration_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $registration = new Registration();
        $form = $this->createForm('AppBundle\Form\RegistrationType', $registration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($registration);
            $em->flush();

            $mailer = $this->container->get('mailer');

            $message = \Swift_Message::newInstance()
                ->setSubject('QUANTUM 2018 Registration')
                ->setFrom('quantum2018@matmor.unam.mx')
                ->setTo(array($registration->getEmail()))
                ->setBcc(array('miguel@matmor.unam.mx'))
                ->setBody($this->container->get('templating')->render('registration/confirmation-email.txt.twig', array('registration' => $registration)))
            ;
            $mailer->send($message);

            return $this->render('registration/confirmation.html.twig');
//            return $this->redirectToRoute('registration_show', array('id' => $registration->getId()));
        }

        return $this->render('registration/new.html.twig', array(
            'registration' => $registration,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a registration entity.
     *
     * @Route("/{slug}", name="registration_show")
     * @Method("GET")
     */
    public function showAction(Registration $registration)
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        $deleteForm = $this->createDeleteForm($registration);

        return $this->render('registration/show.html.twig', array(
            'registration' => $registration,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing registration entity.
     *
     * @Route("/{slug}/edit", name="registration_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Registration $registration)
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        $deleteForm = $this->createDeleteForm($registration);
        $editForm = $this->createForm('AppBundle\Form\RegistrationType', $registration);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('registration_edit', array('slug' => $registration->getSlug()));
        }

        return $this->render('registration/edit.html.twig', array(
            'registration' => $registration,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a registration entity.
     *
     * @Route("/{id}", name="registration_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Registration $registration)
    {
        $form = $this->createDeleteForm($registration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($registration);
            $em->flush();
        }

        return $this->redirectToRoute('registration_index');
    }

    /**
     * Creates a form to delete a registration entity.
     *
     * @param Registration $registration The registration entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Registration $registration)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('registration_delete', array('id' => $registration->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
