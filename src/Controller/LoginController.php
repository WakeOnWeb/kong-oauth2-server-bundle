<?php

namespace WakeOnWeb\Bundle\KongOAuth2ServerBundle\Controller;

use WakeOnWeb\Bundle\KongOAuth2ServerBundle\Client\ApiClient;
use WakeOnWeb\Bundle\KongOAuth2ServerBundle\Exception\BadRequestException;
use WakeOnWeb\Bundle\KongOAuth2ServerBundle\User\IdentityResolver;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @author Quentin Schuler <q.schuler@wakeonweb.com>
 */
class LoginController
{
    /**
     * @param Request          $request
     * @param ApiClient        $apiClient
     * @param UserInterface    $user
     * @param IdentityResolver $identityResolver
     *
     * @return Response
     *
     * @throws BadRequestException
     */
    public function __invoke(Request $request, ApiClient $apiClient, UserInterface $user, IdentityResolver $identityResolver): Response
    {
        $payload = json_decode($request->getContent(), true);

        $response = $apiClient->getTokenFromCredentials(
            $payload['username'],
            $payload['password'],
            $payload['client_id'],
            $payload['client_secret'],
            $identityResolver->resolveIdentity($user)
        );

        return new Response($response, Response::HTTP_OK, [
            'Content-Type' => 'application/json',
        ]);
    }
}
