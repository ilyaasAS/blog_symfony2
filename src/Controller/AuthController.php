<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Contact;
use App\Entity\User;
use App\Form\ArticleType;
use App\Form\ContactType;
use App\Form\UserType;
use App\Repository\ContactRepository;
use App\Repository\UserRepository;
use DateTime;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class AuthController extends AbstractController
{
    #[Route('/auth', name: 'app_signup')]
    public function index(Request $req, UserRepository $repo, UserPasswordHasherInterface $paswordHasher): Response
    {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_profil');
        }

        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            // Verifier si l'utilisateur existe
            $userInDB = $repo->findUserByEmail($user->getEmail());

            if ($userInDB) {
                return $this->render('pages/auth/index.html.twig', [
                    "inscriptionForm" => $form,
                    "message" => ["status" => 'error', "content" => 'Vous êtes déja membre? Connectez-vous!']
                ]);
            }
            $hashedPassword = $paswordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);
            $repo->save($user, true);
        }

        return $this->render('pages/auth/index.html.twig', [
            "inscriptionForm" => $form,
            "message" => ["status" => 'success', "content" => 'Inscription réussie, connectez-vous!']
        ]);
    }

    #[Route('/login', name: 'app_login')]
    function connexion()
    {
        return new Response("Connexion réussie");
    }

    #[Route('/profil', name: 'app_profil')]
    function showProfile(Request $req, UserRepository $repo)
    {
        if (!$this->isGranted("IS_AUTHENTICATED_FULLY")) {
            return $this->redirectToRoute('app_signup');
        }

        $newArticle = new Article();
        $form = $this->createForm(ArticleType::class, $newArticle);

        $form->handleRequest($req);

        // Récuperer l'utilisateur depuis la DB avec son email
        $user = $repo->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);

        if ($form->isSubmitted() && $form->isValid()) {
            // Donner la date a l'article
            $newArticle->setDate(new DateTimeImmutable());



            // Ajouter l'article
            $user->addArticle($newArticle);

            // Enregistrer dans la DB
            $repo->save($user, true);
        }

        return $this->render('pages/profil/index.html.twig', ['articleForm' => $form, "articles" => $user->getArticles()]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout() {}
}