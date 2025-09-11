<?php

namespace App\Controller;

use App\Entity\Favorite;
use App\Entity\Property;
use App\Repository\FavoriteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/favorite')]
final class FavoriteController extends AbstractController
{
    #[Route('/{property}/new', name: 'app_favorite_new', methods: ['GET', 'POST'])]
    public function favoriteFromHome(Request $request, FavoriteRepository $favoriteRepository, Property $property, EntityManagerInterface $entityManager): Response
    {
        $favorite = new Favorite();
        $user = $this->getUser();

        // If the user is not logged in, he cannot mark a favorite property and is redirected to the login page
        if (!$user) {
            if ($request->isXmlHttpRequest()) { // Manages the message if the request is AJAX
                return $this->json(['message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
            }
            return $this->redirectToRoute('app_login');
        }

        // Checks if the CSRF token is valid or not before managing the data
        $token = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('favoriteBtn' . $property->getId(), $token)) {
            if ($request->isXmlHttpRequest()) {
                return $this->json(['message' => 'Invalid CSRF token'], Response::HTTP_FORBIDDEN);
            }
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        // Checks in the FavoriteRepository if there is already an existing favorite for this user & this property
        $existing = $favoriteRepository->findOneBy([
            'user' => $user,
            'property' => $property,
        ]);

        $favorited = false;
        if ($existing) {
            $entityManager->remove($existing);
            $favorited = false;
        } else {
            $favorite = new Favorite();
            $favorite->setUser($user);
            $favorite->setProperty($property);
            $entityManager->persist($favorite);
            $favorited = true;
        }
        $entityManager->flush();

        if ($request->isXmlHttpRequest()) {
            return $this->json([
                'propertyId' => $property->getId(),
                'favorited'  => $favorited,
            ]);
        }

        return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'app_favorite_show', methods: ['GET'])]
    public function show(Favorite $favorite): Response
    {
        return $this->render('favorite/show.html.twig', [
            'favorite' => $favorite,
        ]);
    }
}
