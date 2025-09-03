<?php

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AppAuthenticator extends AbstractLoginFormAuthenticator
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
        $this->em = $em;
    }

    public function onAuthenticationSuccess(Request $request, User $user, TranslatorInterface $translator, TokenInterface $token, string $firewallName): Response
    {
        $user = $token->getUser();

        if ($user instanceof \User && !$user->isActive()) {
            $user->setActive(true);
            $user->setDeactivatedAt(null);
            $this->em->flush();

            $request->getSession()->getFlashBag()->add(
                'success',
                $this->$translator->trans('account.flashSuccess.reactivate')
            );
        }

        return $this->redirectToRoute('app_home');
    }
}

?>
