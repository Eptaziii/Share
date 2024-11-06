<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AjoutAmiType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AmisController extends AbstractController
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    #[Route("/api/search-users", name:"api_search_users", methods:"GET")]
    public function searchUsers(Request $request): JsonResponse
    {
        $query = $request->query->get('q', '');
        $users = $this->userRepository->searchByNameOrFirstnameOrEmail($query);

        $result = [];
        foreach ($users as $user) {
            if (!$this->getUser()->getUsersAccepte()->contains($user)) {
                if ($this->getUser() != $user) {
                    $result[] = [
                        'id' => $user->getId(),
                        'nom' => $user->getNom(),
                        'prenom' => $user->getPrenom(),
                        'email' => $user->getEmail(),
                    ];
                }
            }
        }

        return new JsonResponse($result);
    }

    #[Route('/private-amis', name: 'app_amis')]
    public function amis(Request $request, EntityManagerInterface $em, UserRepository $userRepository): Response
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
                    $this->addFlash('notice','Ami introuvable');
                    return $this->redirectToRoute('app_amis');
                } else {
                    $this->getUser()->addDemander($ami);
                    $em->persist($this->getUser());
                    $em->flush();
                    $this->addFlash('notice','Invitation envoyée');
                    return $this->redirectToRoute('app_amis');
                }
            }
        }
        return $this->render('amis/amis.html.twig', [
            'form'=>$form
        ]);
    }

    #[Route('/private-supprimer-ami/{id}', name: 'app_supprimer_ami')]
    public function supprimerAmi(Request $request, EntityManagerInterface $em, UserRepository $userRepository): Response
    {
        $id = $request->get('id');
        $userAccepter = $userRepository->find($id);
        if($userAccepter){
            $this->getUser()->removeAccepter($userAccepter);
            $userAccepter->removeAccepter($this->getUser());
            $em->persist($this->getUser());
            $em->persist($userAccepter);
            $em->flush();
        }
    return $this->redirectToRoute('app_amis');
    
    }
}

