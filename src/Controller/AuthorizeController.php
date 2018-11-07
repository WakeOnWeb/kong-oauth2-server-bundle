<?php

namespace WakeOnWeb\Bundle\KongOAuth2ServerBundle\Controller;

use WakeOnWeb\Bundle\KongOAuth2ServerBundle\Client\AdminClient;
use WakeOnWeb\Bundle\KongOAuth2ServerBundle\Client\ApiClient;
use WakeOnWeb\Bundle\KongOAuth2ServerBundle\Exception\BadRequestException;
use WakeOnWeb\Bundle\KongOAuth2ServerBundle\Exception\NotFoundException;
use WakeOnWeb\Bundle\KongOAuth2ServerBundle\Form\GrantType;
use WakeOnWeb\Bundle\KongOAuth2ServerBundle\User\IdentityResolver;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Twig\Environment;

/**
 * @author Quentin Schuler <q.schuler@wakeonweb.com>
 */
class AuthorizeController
{
    /**
     * @var string
     */
    private $cancelPath;

    /**
     * @param string $cancelPath
     */
    public function __construct(string $cancelPath)
    {
        $this->cancelPath = $cancelPath;
    }

    /**
     * @param Request              $request
     * @param Environment          $twig
     * @param FormFactoryInterface $factory
     * @param AdminClient          $adminClient
     * @param ApiClient            $apiClient
     * @param UserInterface        $user
     * @param IdentityResolver     $identityResolver
     *
     * @return Response
     *
     * @throws NotFoundException
     * @throws BadRequestException
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __invoke(Request $request, Environment $twig, FormFactoryInterface $factory, AdminClient $adminClient, ApiClient $apiClient, UserInterface $user, IdentityResolver $identityResolver): Response
    {
        $query = $request->query->all();

        /** @var Form $form */
        $form = $factory
            ->create(GrantType::class, $query, [
                'method' => 'POST',
            ])
            ->handleRequest($request)
        ;

        if ($form->isSubmitted() && $form->isValid() && $form->getClickedButton() !== null && $form->getClickedButton()->getName() === 'authorize') {
            $data = $form->getData();

            $uri = $apiClient->authorize(
                $data['client_id'],
                $data['response_type'],
                $identityResolver->resolveIdentity($user)
            );

            return new RedirectResponse($uri);
        }

        $app = $adminClient->findAppByClientId($query['client_id'] ?? '');

        return new Response(
            $twig->render('@WakeOnWebKongOAuth2Server/security/grant.html.twig', [
                'form' => $form->createView(),
                'app_name' => $app['name'],
                'cancel_path' => $this->cancelPath,
            ])
        );
    }
}
