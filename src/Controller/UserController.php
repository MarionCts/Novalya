<?php

namespace App\Controller;

use App\Entity\Favorite;
use App\Entity\User;
use App\Entity\Property;
use App\Repository\UserRepository;
use App\Form\UserType;
use App\Repository\FavoriteRepository;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\ExpressionLanguage\Expression;

class UserController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/user', name: 'app_user_index')]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [

            // Sorts the users by their deactivation date in descending order
            'users' => $userRepository->findBy([], ['deactivatedAt' => 'DESC']),
        ]);
    }

    #[Route('/account/{id}', name: 'app_user_account', methods: ['GET', 'POST'])]
    public function account(User $user, UserPasswordHasherInterface $passwordHasher, Request $request, TranslatorInterface $translator, EntityManagerInterface $entityManager): Response
    {
        if ($user !== $this->getUser()) {
            throw new AccessDeniedException($translator->trans('errorPage.message'));
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
                'id' => $user->getId(),
            ], Response::HTTP_SEE_OTHER);
        }

        // Pagination: defining a limit number of properties to be shown (7) per page

        $result = $entityManager->getRepository(Property::class)
            ->createQueryBuilder('p')
            ->select('DISTINCT p')
            ->innerJoin(Favorite::class, 'f', 'WITH', 'f.property = p')
            ->where('f.user = :user')
            ->andWhere('p.status = :status')
            ->setParameter('user', $user)
            ->setParameter('status', 'Published')
            ->orderBy('p.createdAt', 'DESC');

        $countResult = clone $result;
        $countResult->select('COUNT(DISTINCT p.id)');
        $totalResult = (int) $countResult->getQuery()->getSingleScalarResult();

        $limit = 4;
        $page = max(1, (int) $request->query->get('page', 1));
        $offset = ($page - 1) * $limit;

        $result->setFirstResult($offset)
            ->setMaxResults($limit);

        $properties = $result->getQuery()->getResult();
        $totalPages = (int) ceil($totalResult / $limit);

        return $this->render('user/account.html.twig', [
            'user' => $user,
            'form' => $form,
            'favorites' => $properties,
            'currentPage' => $page,
            'totalPages' => $totalPages,
        ]);
    }

    // Preventing other users from deactivating someone else's account
    #[IsGranted(new Expression('user == subject'), 'user')]
    #[Route('/{id}', name: 'app_user_account_deactivate', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager, TranslatorInterface $translator): Response
    {
        if ($this->isCsrfTokenValid('deactivate' . $user->getId(), $request->getPayload()->getString('_token'))) {
            $user->setIsActive(false);
            $user->setDeactivatedAt(new \DateTimeImmutable());
            $entityManager->flush();
            $this->addFlash('success', $translator->trans('account.flashSuccess.delete'));
        }

        return $this->redirectToRoute('app_logout');
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/user/{id}', name: 'app_user_delete')]
    public function deleteUser(Request $request, User $user, TranslatorInterface $translator, EntityManagerInterface $entityManager): Response
    {

        // Allows the administrator to delete a user
        if ($this->isCsrfTokenValid('deleteUser' . $user->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
            $this->addFlash('success', $translator->trans('userIndex.deleteMessage'));
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
