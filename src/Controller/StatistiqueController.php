<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\FichierRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class StatistiqueController extends AbstractController
{
    #[Route('/admin-statistique', name: 'app_statistique')]
    public function adminStat(UserRepository $ur, FichierRepository $fr): Response
    {
        $users = $ur->findAll();
        $fichiers = $fr->findAll();

        $nbUser = [];
        $nbFichier = [];
        foreach ($users as $user) {
            array_push($nbUser, $user->getId());
        }

        foreach ($fichiers as $fichier) {
            array_push($nbFichier, $fichier->getId());
        }

        $nbUser = count($nbUser);
        $nbFichier = count($nbFichier);
        return $this->render('statistique/statistique.html.twig', [
            "nbUser"=> $nbUser,
            "nbFichier" => $nbFichier,
        ]);
    }
}
