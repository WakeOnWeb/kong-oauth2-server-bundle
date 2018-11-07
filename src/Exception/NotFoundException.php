<?php

namespace WakeOnWeb\Bundle\KongOAuth2ServerBundle\Exception;

use Exception;

/**
 * @author Quentin Schuler <q.schuler@wakeonweb.com>
 */
class NotFoundException extends Exception
{
    /**
     * @param string $id
     *
     * @return NotFoundException
     */
    public static function fromInvalidClientId(string $id): self
    {
        return new self(sprintf('The application identified by the client_id "%s" couldn\'t be found.', $id));
    }
}
