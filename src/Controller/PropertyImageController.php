<?php

namespace App\Controller;

use App\Entity\PropertyImage;
use App\Form\PropertyImageType;
use App\Repository\PropertyImageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/property/image')]
final class PropertyImageController extends AbstractController
{
    #[Route(name: 'app_property_image_index', methods: ['GET'])]
    public function index(PropertyImageRepository $propertyImageRepository): Response
    {
        return $this->render('property_image/index.html.twig', [
            'property_images' => $propertyImageRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_property_image_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $propertyImage = new PropertyImage();
        $form = $this->createForm(PropertyImageType::class, $propertyImage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($propertyImage);
            $entityManager->flush();

            return $this->redirectToRoute('app_property_image_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('property_image/new.html.twig', [
            'property_image' => $propertyImage,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_property_image_show', methods: ['GET'])]
    public function show(PropertyImage $propertyImage): Response
    {
        return $this->render('property_image/show.html.twig', [
            'property_image' => $propertyImage,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_property_image_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PropertyImage $propertyImage, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PropertyImageType::class, $propertyImage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_property_image_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('property_image/edit.html.twig', [
            'property_image' => $propertyImage,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_property_image_delete', methods: ['POST'])]
    public function delete(Request $request, PropertyImage $propertyImage, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$propertyImage->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($propertyImage);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_property_image_index', [], Response::HTTP_SEE_OTHER);
    }
}
