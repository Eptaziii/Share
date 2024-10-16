<?php

namespace App\Controller;

use App\Repository\FichierRepository;
use App\Repository\TelechargementRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class StatistiqueController extends AbstractController
{
    #[Route('/admin-statistique', name: 'app_statistique')]
    public function adminStat(UserRepository $ur, FichierRepository $fr, TelechargementRepository $tr): Response
    {
        $users = $ur->findAll();
        $fichiers = $fr->findAll();
        $telechargements = $tr->findAll();

        $nbUser = [];
        $nbFichier = [];
        $nbTelechargement = [];

        foreach ($users as $user) {
            array_push($nbUser, $user->getId());
        }

        foreach ($fichiers as $fichier) {
            array_push($nbFichier, $fichier->getId());
        }

        foreach ($telechargements as $telechargement) {
            array_push($nbTelechargement, $telechargement->getId());
        }

        $nbUser = count($nbUser);
        $nbFichier = count($nbFichier);
        $nbTelechargement = count($nbTelechargement);
        return $this->render('statistique/statistique.html.twig', [
            "nbUser"=> $nbUser,
            "nbFichier" => $nbFichier,
            "nbTelechargement" => $nbTelechargement,
        ]);
    }
}
