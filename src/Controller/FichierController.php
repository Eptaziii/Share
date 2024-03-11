<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\FichierType;
use App\Entity\Fichier;
use App\Repository\UserRepository;
use App\Repository\ScategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class FichierController extends AbstractController
{
    #[Route('/admin-ajout-fichier', name: 'app_ajout_fichier')]
    public function ajoutFichier(Request $request, ScategorieRepository $scategorieRepository, EntityManagerInterface $em): Response
    {
        $fichier = new Fichier();
        $scategories = $scategorieRepository->findBy([], ['categorie'=>'asc', 'numero'=>'asc']);
        $form = $this->createForm(FichierType::class, $fichier, ['scategories'=>$scategories]);
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if ($form->isSubmitted()&&$form->isValid()){
                $selectedScategories = $form->get('scategories')->getData();
                foreach ($selectedScategories as $scategorie) {
                    $fichier->addScategorie($scategorie);
                }
                $em->persist($fichier);
                $em->flush();
                $this->addFlash('notice','Fichier envoyé');
                return $this->redirectToRoute('app_ajout_fichier');
            }
        }
        return $this->render('fichier/ajout-fichier.html.twig', [
            'form' => $form->createView(),
            'scategories'=> $scategories
        ]);
    }

    #[Route('/admin-liste-fichiers', name: 'app_liste_fichiers')]
    public function listeFichiers(FichierRepository $fichierRepository): Response
    {
        $fichiers=$fichierRepository->findAll();
        return $this->render('fichier/liste-fichiers.html.twig', [
            'fichiers'=>$fichiers
        ]);
    }

    #[Route('/admin-liste-fichiers-par-utilisateur', name: 'app_liste_fichiers_par_utilisateur')]
    public function listeFichiersParUtilisateur(UserRepository $userRepository): Response
    {
        $users = $userRepository->findBy([], ['nom'=>'asc', 'prenom'=>'asc']);
        return $this->render('fichier/liste-fichiers-par-utilisateur.html.twig', ['users'=>$users]);
    }
}