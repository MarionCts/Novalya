<?php

namespace App\Controller;

use App\Entity\Property;
use App\Enum\Status;
use App\Entity\PropertyImage;
use App\Form\PropertyType;
use App\Form\FilterType;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Repository\FavoriteRepository;

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

    #[Route('/filter', name: 'app_property_filter', methods: ['GET'])]
    public function filter(EntityManagerInterface $entityManager, PropertyRepository $propertyRepository, Request $request, FavoriteRepository $favoriteRepository): Response
    {
        // ***** SHOWING FULL HEART ICON ON FAVORITED PROPERTIES *****

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

        // ***** MAKING THE FILTER BAR *****

        // We fetch all of the cities that exist in the database thanks to the properties
        $allCities = $entityManager->getRepository(Property::class)
            ->createQueryBuilder('p')
            ->select('DISTINCT p.city')
            ->where('p.city IS NOT NULL')
            ->orderBy('p.city', 'ASC')
            ->getQuery()
            ->getScalarResult();

        // We convert the cities into an array for the render
        $cities = array_map(fn($r) => $r['city'], $allCities);
        $choices = array_combine($cities, $cities) ?: [];

        $form = $this->createForm(FilterType::class, null, ['cities' => $choices]);
        $form->handleRequest($request);

        // Before using the filters, we make it mandatory that the properties found are published
        $result = $entityManager->getRepository(Property::class)
            ->createQueryBuilder('p')
            ->where('p.status = :status')
            ->orderBy('p.createdAt', 'DESC')
            ->setParameter('status', 'Published');

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            if (!empty($data['category'])) {
                $result->andWhere('p.category = :category')
                    ->setParameter('category', $data['category']);
            }

            if (!empty($data['value'])) {
                $result->andWhere('p.value = :value')
                    ->setParameter('value', $data['value']);
            }

            $city = $form->get('city')->getData();
            if (!empty($city)) {
                $result->andWhere('p.city = :city')
                    ->setParameter('city', $city);
            }
        }

        $filteredProperties = $result->getQuery()->getResult();

        return $this->render('property/filter.html.twig', [
            'favoriteIds' => $favoriteIds,
            'form' => $form->createView(),
            'filteredProperties' => $filteredProperties,
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

            // ⚠️ Parcours des sous-formulaires de la collection
            foreach ($form->get('propertyImages') as $imageForm) {
                /** @var \Symfony\Component\HttpFoundation\File\UploadedFile|null $file */
                $file = $imageForm->get('file')->getData();
                if (!$file) {
                    continue;
                }

                // Générer le nom de fichier
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = (string) $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

                // Déplacer le fichier
                $file->move($this->getParameter('images_directory'), $newFilename);

                // Récupérer l'entité PropertyImage mappée par le sous-form
                /** @var PropertyImage $img */
                $img = $imageForm->getData();

                // Renseigner l'URL / path (adapte selon ton besoin)
                $img->setUrl('/uploads/images/' . $newFilename);

                // Associer au parent (normalement déjà fait via by_reference=false + add)
                $img->setProperty($property);
                $property->addPropertyImage($img);
            }

            // Grâce à cascade: ['persist'], persister seulement le parent suffit
            $entityManager->persist($property);
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
    public function show(Property $property, FavoriteRepository $favoriteRepository): Response
    {
        $status = $property->getStatus();

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

        if ($status === Status::Published || $this->isGranted('ROLE_ADMIN')) {
            return $this->render('property/show.html.twig', [
                'property' => $property,
                'favoriteIds' => $favoriteIds,
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

            // First, we set the date we are modifying the property
            $property->setModifiedAt(new \DateTimeImmutable());

            foreach ($form->get('propertyImages') as $imageForm) {

                $img = $imageForm->getData();
                if (!$img) {
                    continue;
                }

                $file = $imageForm->get('file')->getData();

                if ($file) {
                    $newFilename = bin2hex(random_bytes(8)) . '.' . $file->guessExtension();
                    $file->move($this->getParameter('images_directory'), $newFilename);
                    $img->setUrl('/uploads/images/' . $newFilename);
                }

                $img->setProperty($property);
            }

            $entityManager->persist($property);
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
