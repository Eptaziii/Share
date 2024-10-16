<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Fichier;
use App\Form\AjoutAmiType;
use App\Form\FichierUserType;
use App\Form\ModifierRoleType;
use App\Repository\UserRepository;
use App\Repository\ScategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/admin-liste-users', name: 'app_liste-users')]
    public function listeUsers(UserRepository $userRepository): Response
    {
        $users=$userRepository->findAll();
        return $this->render('security/liste-users.html.twig', [
            'users'=>$users
        ]);
    }

    #[Route('/profil', name:'app_profil')]
    public function profil(UserRepository $userRepository, Request $request, ScategorieRepository $scategorieRepository, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        return $this->render('security/profil.html.twig', [
        ]);
    }

    #[Route('/private-telechargement-fichier-user/{id}', name: 'app_telechargement_fichier_user', requirements: ["id"=>"\d+"] )]
    public function telechargementFichierUser(Fichier $fichier) {
        if ($fichier == null){
            return $this->redirectToRoute('app_profil'); 
        }
        else{
            if($fichier->getUser()!==$this->getUser()){
                $this->addFlash('notice', 'Vous n\'êtes pas le propriétaire de ce fichier');
                return $this->redirectToRoute('app_profil'); 
            }
            return $this->file($this->getParameter('file_directory').'/'.$fichier->getNomServeur(), $fichier->getNomOriginal());
        } 
    }
}