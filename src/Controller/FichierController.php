<?php

namespace App\Controller;

use App\Entity\Fichier;
use App\Form\FichierType;
use App\Form\FichierUserType;
use App\Repository\UserRepository;
use App\Repository\FichierRepository;
use App\Repository\ScategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FichierController extends AbstractController
{
    #[Route('/admin-ajout-fichier', name: 'app_ajout_fichier')]
    public function ajoutFichier(Request $request, ScategorieRepository $scategorieRepository, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $fichier = new Fichier();
        $scategories = $scategorieRepository->findBy([], ['categorie'=>'asc', 'numero'=>'asc']);
        $form = $this->createForm(FichierUserType::class, $fichier, ['scategories'=>$scategories]);
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if ($form->isSubmitted()&&$form->isValid()){
                $selectedScategories = $form->get('scategories')->getData();
                foreach ($selectedScategories as $scategorie) {
                    $fichier->addScategorie($scategorie);
                }
                $file = $form->get('fichier')->getData();
                if($file){
                    $nomFichierServeur = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $nomFichierServeur = $slugger->slug($nomFichierServeur);
                    $nomFichierServeur = $nomFichierServeur.'-'.uniqid().'.'.$file->guessExtension();
                    try{
                        $fichier->setNomServeur($nomFichierServeur);
                        $fichier->setNomOriginal($file->getClientOriginalName());
                        $fichier->setDateEnvoi(new \Datetime());
                        $fichier->setExtension($file->guessExtension());
                        $fichier->setTaille($file->getSize());
                        $fichier->setUser($this->getuser());
                        $em->persist($fichier);
                        $em->flush();
                        $file->move($this->getParameter('file_directory'), $nomFichierServeur);
                        $this->addFlash('notice', 'Fichier envoyé');
                        return $this->redirectToRoute('app_ajout_fichier');
                    }
                    catch(FileException $e){
                        $this->addFlash('notice', 'Erreur d\'envoi');
                    }
                $em->persist($fichier);
                $em->flush();
                $this->addFlash('notice','Fichier envoyé');
                }
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

    #[Route('/private-telechargement-fichier/{id}', name: 'app_telechargement_fichier', requirements: ["id"=>"\d+"] )]
    public function telechargementFichier(Fichier $fichier) {
        if ($fichier == null){
            $this->redirectToRoute('app_liste_fichiers_par_utilisateur');
        } else{
            return $this->file($this->getParameter('file_directory').'/'.$fichier->getNomServeur(),
            $fichier->getNomOriginal());
        }
    }

    #[Route('/private-supprimer-fichier/{id}', name: 'app_supprimer_fichier')]
    public function supprimerFichier(Request $request, Fichier $fichier,EntityManagerInterface $em) 
    {
        if($fichier!=null){
            unlink("../uploads/fichiers/".$fichier->getNomServeur());
            $em->remove($fichier);
            $em->flush();
            $this->addFlash('noticer','Fichier '.$fichier->getNomOriginal().' supprimé');
        }
        return $this->redirectToRoute('app_liste_fichiers_par_utilisateur');
    }

    #[Route('/private-supprimer-fichier-user/{id}', name: 'app_supprimer_fichier_user')]
    public function supprimerFichierUser(Request $request, Fichier $fichier,EntityManagerInterface $em) 
    {
        if($fichier!=null){
            unlink("../uploads/fichiers/".$fichier->getNomServeur());
            $em->remove($fichier);
            $em->flush();
            $this->addFlash('noticer','Fichier '.$fichier->getNomOriginal().' supprimé');
        }
        return $this->redirectToRoute('app_liste_fichiers');
    }
}