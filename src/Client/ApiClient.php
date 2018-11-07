<?php

namespace WakeOnWeb\Bundle\KongOAuth2ServerBundle\Client;

use WakeOnWeb\Bundle\KongOAuth2ServerBundle\Exception\BadRequestException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

/**
 * @author Quentin Schuler <q.schuler@wakeonweb.com>
 */
class ApiClient
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $provisionKey;

    /**
     * @param Client $client
     * @param string $provisionKey
     */
    public function __construct(Client $client, string $provisionKey)
    {
        $this->client = $client;
        $this->provisionKey = $provisionKey;
    }

    /**
     * @param string $clientId
     * @param string $responseType
     * @param string $userId
     *
     * @return string
     *
     * @throws BadRequestException
     */
    public function authorize(string $clientId, string $responseType, string $userId): string
    {
        try {
            $response = $this->client->post('oauth2/authorize', [
                'verify' => false,
                'query' => [
                    'client_id' => $clientId,
                    'response_type' => $responseType,
                    'provision_key' => $this->provisionKey,
                    'authenticated_userid' => $userId,
                ],
            ]);
        } catch (ClientException $exception) {
            $response = json_decode((string) $exception->getResponse()->getBody(), true);

            throw BadRequestException::fromBadResponse($response);
        }

        $response = json_decode((string) $response->getBody(), true);

        return $response['redirect_uri'];
    }

    /**
     * @param string $username
     * @param string $password
     * @param string $clientId
     * @param string $clientSecret
     * @param string $userId
     *
     * @return string
     *
     * @throws BadRequestException
     */
    public function getTokenFromCredentials(string $username, string $password, string $clientId, string $clientSecret, string $userId): string
    {
        try {
            $response = $this->client->post('oauth2/token', [
                'verify' => false,
                'query' => [
                    'client_id' => $clientId,
                    'client_secret' => $clientSecret,
                    'grant_type' => 'password',
                    'provision_key' => $this->provisionKey,
                    'authenticated_userid' => $userId,
                    'username' => $username,
                    'password' => $password,
                ],
            ]);
        } catch (ClientException $exception) {
            $response = json_decode((string) $exception->getResponse()->getBody(), true);

            throw BadRequestException::fromBadResponse($response);
        }

        return (string) $response->getBody();
    }
}
