<?php

namespace WakeOnWeb\Bundle\KongOAuth2ServerBundle\Client;

use WakeOnWeb\Bundle\KongOAuth2ServerBundle\Exception\NotFoundException;
use GuzzleHttp\Client;

/**
 * @author Quentin Schuler <q.schuler@wakeonweb.com>
 */
class AdminClient
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $id
     *
     * @return array|null
     *
     * @throws NotFoundException
     */
    public function findAppByClientId(string $id): ?array
    {
        $response = $this->client->get('oauth2', [
            'query' => [
                'client_id' => $id,
            ]
        ]);

        $response = json_decode((string) $response->getBody(), true);

        if ($response['total'] === 1) {
            return array_shift($response['data']);
        }

        throw NotFoundException::fromInvalidClientId($id);
    }
}
