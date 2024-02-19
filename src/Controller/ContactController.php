<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ContactRepository;
use App\Entity\Contact;

class ContactController extends AbstractController
{
    #[Route('/mod-liste-contacts', name: 'app_liste-contacts')]
    public function listeContacts(ContactRepository $contactRepository): Response
    {
        $contacts=$contactRepository->findAll();
        return $this->render('contact/liste-contacts.html.twig', [
            'contacts'=>$contacts
        ]);
    }

    #[Route('/mod-lire-contact/{id}', name: 'app_lire-contact')]
    public function lireContact(Contact $contact): Response
    {
        return $this->render('contact/lire-contact.html.twig', [
            'contact'=>$contact
        ]);
    }
}
