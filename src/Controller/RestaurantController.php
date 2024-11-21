<?php

namespace App\Controller;

use App\Document\Restaurant;
use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RestaurantController extends AbstractController
{
    #[Route('/restaurant', name: 'app_restaurant')]
    public function index(DocumentManager $dm): Response
    {
        $repository = $dm->getRepository(Restaurant::class);
        
        // récupérer tous les restaurants
        $restaurant = $repository->findAll();

        return $this->render('pages/restaurants/index.html.twig', ["restaurants" => $restaurant]);
    }


    #[Route('/restaurant/create', name: 'app_restaurant_create', methods: ['POST'])]
    function createRestaurant(Request $request, DocumentManager $dm)
    {
        // Recupérer les données dans le corp de la requête
        $nomRestaurant = $request->request->get('name');

        // Tester si elle existe
        if (isset($nomRestaurant)) {
            $restaurant = new Restaurant();
            $restaurant->setName($nomRestaurant);
            $dm->persist($restaurant);
            $dm->flush();
        }
        return $this->redirectToRoute('app_restaurant');
    }
}
