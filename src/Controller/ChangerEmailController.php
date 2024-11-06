<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ChangerEmailController extends AbstractController
{
    #[Route('/changer/email', name: 'app_changer_email')]
    public function index(): Response
    {
        return $this->render('changer_email/index.html.twig', [
            'controller_name' => 'ChangerEmailController',
        ]);
    }
}
