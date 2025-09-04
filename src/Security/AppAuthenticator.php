<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final class AppAuthenticator implements UserCheckerInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private ?RequestStack $requestStack = null,
        private TranslatorInterface $translator,
    ) {}

    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof User) {
            return;
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
        $this->checkPreAuth($user);

        if (!$user instanceof User) {
            return;
        }

        if ($user->isActive() === false) {
            $user->setIsActive(true);
            $user->setDeactivatedAt(null);
            $this->em->flush();

            $this->requestStack?->getSession()?->getFlashBag()->add(
                'success',
                $this->translator->trans('account.flashSuccess.reactivate')
            );
        }
    }
}
