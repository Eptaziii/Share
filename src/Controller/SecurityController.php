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
        $users=$userRepository->findAll();
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
                        return $this->redirectToRoute('app_profil');
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
        return $this->render('security/profil.html.twig', [
            'form' => $form->createView(),
            'scategories'=> $scategories,
            'users'=>$users
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

    #[Route('/private-ajout-ami', name:'app_ajout_ami')]
    public function ajoutAmi(Request $request, EntityManagerInterface $em, UserRepository $userRepository): Response 
    {
        if($request->get('id')!=null){
            $id = $request->get('id');
            $userDemande = $userRepository->find($id);
            if($userDemande){
                $this->getUser()->removeDemander($userDemande);
                $em->persist($this->getUser());
                $em->flush();
            }
        }
        if($request->get('idRefuser')!=null){
            $id = $request->get('idRefuser');
            $userRefuser = $userRepository->find($id);
            if($userRefuser){
                $this->getUser()->removeUsersDemande($userRefuser);
                $em->persist($this->getUser());
                $em->flush();
            }
        }
        if($request->get('idAccepter')!=null){
            $id = $request->get('idAccepter');
            $userAccepter = $userRepository->find($id);
            if($userAccepter){
                $this->getUser()->addAccepter($userAccepter);
                $userAccepter->addAccepter($this->getUser());
                $this->getUser()->removeUsersDemande($userAccepter);
                $em->persist($this->getUser());
                $em->persist($userAccepter);
                $em->flush();
            }
        }
        $form = $this->createForm(AjoutAmiType::class);
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if ($form->isSubmitted()&&$form->isValid()){
                $ami = $userRepository->findOneBy(array('email'=>$form->get('email')->getData()));
                if(!$ami){
                    $this->addFlash('noticer','Ami introuvable');
                    return $this->redirectToRoute('app_ajout_ami');
                } else{
                    $this->getUser()->addDemander($ami);
                    $em->persist($this->getUser());
                    $em->flush();
                    $this->addFlash('notice','Invitation envoyée');
                    return $this->redirectToRoute('app_ajout_ami');
                }
            }
        }

        return $this->render('security/ajout-ami.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/private-supp-ami/{idAccepter}', name:'app_supp_ami')]
    public function SuppAmi(Request $request, EntityManagerInterface $em, UserRepository $userRepository): Response 
    {
        if($request->get('idAccepter')!=null){
            $id = $request->get('idAccepter');
            $userAccepter = $userRepository->find($id);
            if($userAccepter){
                $this->getUser()->removeAccepter($userAccepter);
                $userAccepter->removeAccepter($this->getUser());
                $em->persist($this->getUser());
                $em->persist($userAccepter);
                $em->flush();
            }
        }
        return $this->redirectToRoute('app_ajout_ami');
    }

}