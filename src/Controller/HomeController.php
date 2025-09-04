<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\PropertyRepository;
use App\Repository\FavoriteRepository;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(PropertyRepository $propertyRepository, FavoriteRepository $favoriteRepository): Response
    {

        // we create an array "favoriteIds" which will fetch all of the properties IDs
        // of the currently logged in user
        $favoriteIds = [];
        if ($this->getUser()) {
            $userFavorites = $favoriteRepository->findBy(['user' => $this->getUser()]);
            $favoriteIds = array_map(
                static fn(\App\Entity\Favorite $f) => $f->getProperty()->getId(),
                $userFavorites
            );
        }
        return $this->render('home/index.html.twig', [
            'properties' => $propertyRepository->findBy([], ['createdAt' => 'DESC'], 5),
            'favoriteIds' => $favoriteIds,
        ]);
    }
}
