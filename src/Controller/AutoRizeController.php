<?php

namespace WakeOnWeb\Bundle\KongOAuth2ServerBundle\Controller;

use WakeOnWeb\Bundle\KongOAuth2ServerBundle\Client\ApiClient;
use WakeOnWeb\Bundle\KongOAuth2ServerBundle\Exception\BadRequestException;
use WakeOnWeb\Bundle\KongOAuth2ServerBundle\User\IdentityResolver;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @author Quentin Schuler <q.schuler@wakeonweb.com>
 */
class AutoRizeController
{
    /**
     * @param Request          $request
     * @param Environment      $twig
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
        $data = $request->query->all();

        $uri = $apiClient->authorize(
            $data['client_id'] ?? '',
            $data['response_type'] ?? '',
            $identityResolver->resolveIdentity($user)
        );

        return new RedirectResponse($uri);
    }
}
