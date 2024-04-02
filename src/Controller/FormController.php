<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class FormController extends AbstractController
{
    private $formFactory;

    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    #[Route('/form/delete-responses', name: 'delete_responses')]
    public function deleteResponses(Request $request, SessionInterface $session): Response
    {
        $session->remove('responses');
        return $this->redirectToRoute('app_form');
    }

    #[Route('/form', name: 'app_form')]
    public function index(Request $request, SessionInterface $session): Response
    {
        $responses = $session->get('responses', []);

        $form = $this->createFormBuilder()
            ->add('name', TextType::class)
            ->add('prenom', TextType::class)
            ->add('message', TextareaType::class)
            ->add('submit', SubmitType::class, ['label' => 'Envoyer'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            if ($form->isValid()) {
                $data = $form->getData();
                $responses[] = $data;
                $session->set('responses', $responses);
                return $this->redirectToRoute('app_form');
            }
        }

        return $this->render('form/index.html.twig', [
            'form' => $form->createView(),
            'responses' => $responses,
        ]);
    }
}
