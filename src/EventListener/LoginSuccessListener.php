<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class LoginSuccessListener
{
    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

    public function onLoginSuccess(LoginSuccessEvent $event): void
    {
        $user = $event->getAuthenticatedToken()->getUser();
        if (in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            $event->setResponse(new RedirectResponse($this->urlGenerator->generate('admin')));
            return;
        }

        if (in_array('ROLE_DIRECTOR', $user->getRoles(), true)) {
            $event->setResponse(new RedirectResponse($this->urlGenerator->generate('director')));
            return;
        }

        // остальные — на главную
        $event->setResponse(new RedirectResponse('/bomb'));
    }
}