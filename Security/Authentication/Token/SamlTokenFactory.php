<?php

namespace Hslavich\OneloginSamlBundle\Security\Authentication\Token;

/**
 * @deprecated since 2.1
 */
class SamlTokenFactory implements SamlTokenFactoryInterface
{
    /**
     * @inheritdoc
     */
    public function createToken($user, array $attributes, array $roles): SamlTokenInterface
    {
        $token = new SamlToken($roles);
        $token->setUser($user);
        $token->setAttributes($attributes);

        return $token;
    }
}
