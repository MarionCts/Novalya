<?php

namespace App\Controller;

use App\Entity\Favorite;
use App\Entity\Property;
use App\Form\FavoriteType;
use App\Repository\FavoriteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\ExpressionLanguage\Expression;

#[Route('/favorite')]
final class FavoriteController extends AbstractController
{
    #[Route(name: 'app_favorite_index', methods: ['GET'])]
    public function index(FavoriteRepository $favoriteRepository): Response
    {
        return $this->render('favorite/index.html.twig', [
            'favorites' => $favoriteRepository->findAll(),
        ]);
    }

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

    #[Route('/{id}/edit', name: 'app_favorite_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Favorite $favorite, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FavoriteType::class, $favorite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_favorite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('favorite/edit.html.twig', [
            'favorite' => $favorite,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_favorite_delete', methods: ['POST'])]
    public function delete(Request $request, Favorite $favorite, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $favorite->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($favorite);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_favorite_index', [], Response::HTTP_SEE_OTHER);
    }
}
