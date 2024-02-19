<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\FichierType;
use App\Entity\Fichier;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class FichierController extends AbstractController
{
    #[Route('/admin-ajout-fichier', name: 'app_ajout_fichier')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $fichier = new Fichier();
        $form = $this->createForm(FichierType::class, $fichier);
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if ($form->isSubmitted()&&$form->isValid()){
                $em->persist($fichier);
                $em->flush();
                $this->addFlash('notice','Fichier envoyé');
                return $this->redirectToRoute('app_ajout_fichier');
            }
        }
        return $this->render('fichier/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}