<?php

namespace Hslavich\OneloginSamlBundle\Security\User;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class SamlUserProvider implements UserProviderInterface
{
    protected $userClass;
    protected $defaultRoles;

    public function __construct($userClass, array $defaultRoles)
    {
        $this->userClass = $userClass;
        $this->defaultRoles = $defaultRoles;
    }

    public function loadUserByUsername($username): UserInterface
    {
        return new $this->userClass($username, $this->defaultRoles);
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        return $user;
    }

    public function supportsClass($class): bool
    {
        return $this->userClass === $class || is_subclass_of($class, $this->userClass);
    }
}
