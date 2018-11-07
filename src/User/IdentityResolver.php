<?php

namespace WakeOnWeb\Bundle\KongOAuth2ServerBundle\User;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @author Quentin Schuler <q.schuler@wakeonweb.com>
 */
interface IdentityResolver
{
    /**
     * @param UserInterface $user
     *
     * @return string
     */
    public function resolveIdentity(UserInterface $user): string;
}
