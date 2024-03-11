<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Repository\UserRepository;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ModifierRoleType;



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
    public function profil(UserRepository $userRepository): Response
    {
        $users=$userRepository->findAll();
        return $this->render('security/profil.html.twig', [
            'users'=>$users
        ]);
    }

    #[Route('/admin-modifier-role/{id}', name: 'app_modifier_role')]
    public function modifierRole(Request $request, User $user, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ModifierRoleType::class, $user);
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if ($form->isSubmitted()&&$form->isValid()){
            $em->persist($user);
            $em->flush();
            $this->addFlash('notice','Rôle modifié');
            return $this->redirectToRoute('app_liste-user');
            }
            }
        return $this->render('security/modifier-role.html.twig', [
            'form'=> $form->createView()
        ]);
    }

}