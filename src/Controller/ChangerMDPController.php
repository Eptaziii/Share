<?php

namespace App\Controller;

use App\Form\ModifPassType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;

class ChangerMDPController extends AbstractController
{
    #[Route('/changer-mdp', name: 'app_changer_mdp')]
    public function changermdp(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $uph): Response
    {
        $form = $this->createForm(ModifPassType::class, $this->getUser());
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $password = $form->get('password')->getData();
                if($uph->isPasswordValid($this->getUser(), $password)){

                    $newPassword = $form->get('newpassword')->getData();
                    $confirmNewPassword = $form->get('confirmnewpassword')->getData();
                    
                    if($newPassword == $confirmNewPassword){
                        if(!$uph->isPasswordValid($this->getUser(), $newPassword)){
                            $newPassword = $uph->hashPassword($this->getUser(), $newPassword);
                            $this->getUser()->setPassword($newPassword);
                            $em->persist($this->getUser());
                            $em->flush();
                            $this->addFlash('notice', 'Le mot de passe a bien été modifié');
                        }else{
                            $this->addFlash('noticer', 'Vous n\'avez entrer le même mot de passe utilisé actuellement');
                        }
                       
                        
                    }else{
                        $this->addFlash('noticer', 'Vous n\'avez pas entrer le même mot de passe');
                    }

                }else{
                    $this->addFlash('noticer', 'Vous n\'avez pas entrer le bon mot de passe');
                }
                
                
                return $this->redirectToRoute('app_profil');
            }
        }
        return $this->render('changer_mdp/changermdp.html.twig', [
            "modifmdp"=> $form->createView(),

        ]);
    }
}
