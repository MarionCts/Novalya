<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Property;
use App\Repository\PropertyRepository;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(PropertyRepository $propertyRepository): Response
    {
        return $this->render('home/index.html.twig', [
            'properties' => $propertyRepository->findBy([], ['createdAt' => 'DESC'], 5)
        ]);
    }
}