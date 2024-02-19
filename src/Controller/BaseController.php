<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ContactType;
use App\Form\CategorieType;
use App\Form\InscriptionType;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Contact;
use App\Entity\Categorie;
use Doctrine\ORM\EntityManagerInterface;

class BaseController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {  
        return $this->render('base/index.html.twig', [
        ]);
    }

    #[Route('/contact', name: 'app_contact')]
    public function contact(Request $request,EntityManagerInterface $em): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if ($form->isSubmitted()&&$form->isValid()){
                $contact->setDateEnvoi(new \Datetime());
                $em->persist($contact);
                $em->flush();
                $this->addFlash('notice','Message envoyé');
                return $this->redirectToRoute('app_contact');
            }
        }

        return $this->render('base/contact.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/private-categorie', name: 'app_categorie')]
    public function categorie(Request $request, EntityManagerInterface $em): Response
    {  
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isSubmitted()&&$form->isValid()) {
                $em->persist($categorie);
                $em->flush();
                $this->addFlash('notice','Catégorie ajoutée');
                return $this->redirectToRoute('app_categorie');
            }
        }
        return $this->render('base/categorie.html.twig', [
            'form' => $form->createView()
        ]);
    }
    #[Route('/ap', name: 'app_ap')]
    public function ap(): Response
    {
        return $this->render('base/ap.html.twig', [
        ]);
    }
    #[Route('/ml', name: 'app_ml')]
    public function ml(): Response
    {
        return $this->render('base/ml.html.twig', [
        ]);
    }
    #[Route('/inscription', name: 'app_inscription')]
    public function inscription(): Response
    {
        $inscription = new Inscription();
        $form = $this->createForm(InscriptionType::class, $inscription);
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if ($form->isSubmitted()&&$form->isValid()){
                $em->persist($inscription);
                $em->flush();
                $this->addFlash('notice','Inscription envoyée');
                return $this->redirectToRoute('app_inscription');
            }
        }

        return $this->render('base/inscription.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
