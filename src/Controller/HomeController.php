<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $req, ContactRepository $repo): Response
    {
        $message = new Contact();

        $form = $this->createForm(ContactType::class, $message);

        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            $repo->save($message, true);
        }

        return $this->render('pages/home/index.html.twig', [
            "contactForm" => $form
        ]);
    }
}

// Exercice:
// 1. Créer le Hero avec image et présentation.
// 2. Créer un Formulaire avec: email, message (Avec Validation)
// 3. Afficher le formulaire dans la page d'Accueil.
// 4. Créer une Entité et son Repository et faites une migration
// 5. Créer une route pour traiter le formulaire.
// 6. Enregistrer les données la BD.