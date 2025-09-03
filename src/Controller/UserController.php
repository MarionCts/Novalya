<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Form\UserType;
use App\Repository\PropertyRepository;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user_index')]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/account/{id}', name: 'app_user_account', methods: ['GET', 'POST'])]
    public function account(User $user, UserPasswordHasherInterface $passwordHasher, Request $request, TranslatorInterface $translator, EntityManagerInterface $entityManager, PropertyRepository $propertyRepository): Response
    {
        if ($user !== $this->getUser()) {
            throw new AccessDeniedException($translator->trans('errorPage.message'));
            // return $this->redirectToRoute('app_error_error');
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $newPassword = $form->get('password')->getData();

            if ($newPassword) {
                $hashed = $passwordHasher->hashPassword($user, $newPassword);
                $user->setPassword($hashed);
            }

            $entityManager->flush();
            $this->addFlash('success', $translator->trans('account.flashSuccess.info'));

            return $this->redirectToRoute('app_user_account', [
                'user' => $user,
                'id' =>$user->getId(),
            ], Response::HTTP_SEE_OTHER);
        }
        
        return $this->render('user/account.html.twig', [
            'user' => $user,
            'form' => $form,
            'properties' => $propertyRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_user_account_deactivate', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager, TranslatorInterface $translator): Response
    {
        if ($this->isCsrfTokenValid('deactivate'.$user->getId(), $request->getPayload()->getString('_token'))) {
            $user->setIsActive(false);
            $user->setDeactivatedAt(new \DateTimeImmutable());
            $entityManager->flush();
            $this->addFlash('success', $translator->trans('account.flashSuccess.delete'));
        }

        return $this->redirectToRoute('app_logout');
        }
}
