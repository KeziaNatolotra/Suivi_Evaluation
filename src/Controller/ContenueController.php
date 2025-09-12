<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ContenueController extends AbstractController
{
    #[Route('/contenue', name: 'app_contenue')]
    public function index(): Response
    {
        return $this->render('contenue/index.html.twig', [
            'controller_name' => 'ContenueController',
        ]);
    }
}
