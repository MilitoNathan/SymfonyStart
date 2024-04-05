<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'pageTitle' => 'Bienvenue sur le projet !',
            'message' => 'Ceci est la page d\'accueil du projet.',
            'path' => 'src/Controller/HomeController.php',
        ]);
    }
}
