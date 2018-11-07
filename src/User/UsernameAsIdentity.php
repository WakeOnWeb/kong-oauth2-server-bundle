<?php

namespace WakeOnWeb\Bundle\KongOAuth2ServerBundle\User;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @author Quentin Schuler <q.schuler@wakeonweb.com>
 */
class UsernameAsIdentity implements IdentityResolver
{
    /**
     * {@inheritdoc}
     */
    public function resolveIdentity(UserInterface $user): string
    {
        return $user->getUsername();
    }
}
