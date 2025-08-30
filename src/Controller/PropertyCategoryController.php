<?php

namespace App\Controller;

use App\Entity\PropertyCategory;
use App\Form\PropertyCategoryType;
use App\Repository\PropertyCategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/property/category')]
final class PropertyCategoryController extends AbstractController
{
    #[Route(name: 'app_property_category_index', methods: ['GET'])]
    public function index(PropertyCategoryRepository $propertyCategoryRepository): Response
    {
        return $this->render('property_category/index.html.twig', [
            'property_categories' => $propertyCategoryRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_property_category_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, TranslatorInterface $translator): Response
    {
        $propertyCategory = new PropertyCategory();
        $form = $this->createForm(PropertyCategoryType::class, $propertyCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($propertyCategory);
            $entityManager->flush();

            $this->addFlash('success', $translator->trans('addCategoryPage.flashSuccess'));
            return $this->redirectToRoute('app_property_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('property_category/new.html.twig', [
            'property_category' => $propertyCategory,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_property_category_show', methods: ['GET'])]
    public function show(PropertyCategory $propertyCategory): Response
    {
        return $this->render('property_category/show.html.twig', [
            'property_category' => $propertyCategory,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_property_category_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PropertyCategory $propertyCategory, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PropertyCategoryType::class, $propertyCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_property_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('property_category/edit.html.twig', [
            'property_category' => $propertyCategory,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_property_category_delete', methods: ['POST'])]
    public function delete(Request $request, PropertyCategory $propertyCategory, EntityManagerInterface $entityManager, TranslatorInterface $translator): Response
    {
        if ($this->isCsrfTokenValid('delete'.$propertyCategory->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($propertyCategory);
            $entityManager->flush();
            $this->addFlash('success', $translator->trans('deleteCategoryPage.flashSuccess'));
        }

        return $this->redirectToRoute('app_property_category_index', [], Response::HTTP_SEE_OTHER);
    }
}
