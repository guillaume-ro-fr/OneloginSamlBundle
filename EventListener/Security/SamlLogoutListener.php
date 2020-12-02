<?php

declare(strict_types=1);

namespace Hslavich\OneloginSamlBundle\EventListener\Security;

use Hslavich\OneloginSamlBundle\Security\Authentication\Token\SamlTokenInterface;
use OneLogin\Saml2\Auth;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class SamlLogoutListener
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Sets the container.
     */
    public function setContainer(ContainerInterface $container = null): void
    {
        $this->container = $container;
    }

    public function __invoke(LogoutEvent $event)
    {
        $token = $event->getToken();
        if (!$token instanceof SamlTokenInterface) {
            return;
        }

        /** @var Auth $samlAuth */
        $samlAuth = $this->container->get('onelogin_auth.'.$token->getAttribute('idp'));
        try {
            $samlAuth->processSLO();

        } catch (\OneLogin\Saml2\Error $e) {
            if (!empty($samlAuth->getSLOurl())) {
                $sessionIndex = $token->hasAttribute('sessionIndex') ? $token->getAttribute('sessionIndex') : null;
                $samlAuth->logout(null, [], $token->getUsername(), $sessionIndex);
            }
        }
    }
}
