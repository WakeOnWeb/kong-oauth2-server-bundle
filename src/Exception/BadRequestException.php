<?php

namespace WakeOnWeb\Bundle\KongOAuth2ServerBundle\Exception;

use Exception;

/**
 * @author Quentin Schuler <q.schuler@wakeonweb.com>
 */
class BadRequestException extends Exception
{
    /**
     * @param array $payload
     *
     * @return BadRequestException
     */
    public static function fromBadResponse(array $payload): self
    {
        return new self(sprintf(
            'The API failed to handle the authorization request due to "%s (%s)".',
            $payload['error'],
            $payload['error_description']
        ));
    }
}
