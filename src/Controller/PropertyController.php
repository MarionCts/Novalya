<?php

namespace App\Controller;

use App\Entity\Property;
use App\Enum\Status;
use App\Entity\PropertyImage;
use App\Form\PropertyType;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/property')]
final class PropertyController extends AbstractController
{
    #[Route(name: 'app_property_index', methods: ['GET'])]
    public function index(PropertyRepository $propertyRepository): Response
    {
        return $this->render('property/index.html.twig', [
            'properties' => $propertyRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_property_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, TranslatorInterface $translator, SluggerInterface $slugger): Response
    {
        $property = new Property();
        $property->setCreatedAt(new \DateTime());
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($property);
            $entityManager->flush();

            $imageFile = $form->get('images')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();
                $imageFile->move(
                    $this->getParameter('images_directory'),
                    $newFilename
                );
                $media = new PropertyImage;
                $media->setUrl('/uploads/images/' . $newFilename);
                $media->setProperty($property);
                $media->setIsFeatured(false);

                $entityManager->persist($media);
                $entityManager->flush();
                $property->addPropertyImage($media);
            }

            $entityManager->flush();

            $this->addFlash('success', $translator->trans('addPropertyPage.flashSuccess'));
            return $this->redirectToRoute('app_property_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('property/new.html.twig', [
            'property' => $property,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_property_show', methods: ['GET'])]
    public function show(Property $property): Response
    {
        $status = $property->getStatus();

        if ($status === Status::Published || $this->isGranted('ROLE_ADMIN')) {
            return $this->render('property/show.html.twig', [
                'property' => $property,
            ]);
        }
        return $this->redirectToRoute('app_error_error', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/edit', name: 'app_property_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Property $property, EntityManagerInterface $entityManager, TranslatorInterface $translator, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $imageFile = $form->get('images')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();
                $imageFile->move(
                    $this->getParameter('images_directory'),
                    $newFilename
                );
                $media = new PropertyImage;
                $media->setUrl('/uploads/images/' . $newFilename);
                $media->setProperty($property);
                $media->setIsFeatured(false);

                $entityManager->persist($media);
                $entityManager->flush();
                $property->addPropertyImage($media);
            }

            $entityManager->flush();

            $this->addFlash('success', $translator->trans('updatePropertyPage.flashSuccess'));
            return $this->redirectToRoute('app_property_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('property/edit.html.twig', [
            'property' => $property,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_property_delete', methods: ['POST'])]
    public function delete(Request $request, Property $property, EntityManagerInterface $entityManager, TranslatorInterface $translator): Response
    {
        if ($this->isCsrfTokenValid('delete' . $property->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($property);
            $entityManager->flush();
            $this->addFlash('success', $translator->trans('deletePropertyPage.flashSuccess'));
        }

        return $this->redirectToRoute('app_property_index', [], Response::HTTP_SEE_OTHER);
    }
}
