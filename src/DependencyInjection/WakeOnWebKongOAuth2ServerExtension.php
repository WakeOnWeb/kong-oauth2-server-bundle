<?php

namespace WakeOnWeb\Bundle\KongOAuth2ServerBundle\DependencyInjection;

use WakeOnWeb\Bundle\KongOAuth2ServerBundle\Client\ApiClient;
use WakeOnWeb\Bundle\KongOAuth2ServerBundle\Controller\AuthorizeController;
use WakeOnWeb\Bundle\KongOAuth2ServerBundle\User\IdentityResolver;
use Exception;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

/**
 * @author Quentin Schuler <q.schuler@wakeonweb.com>
 */
class WakeOnWebKongOAuth2ServerExtension extends Extension
{
    /**
     * {@inheritdoc}
     *
     * @throws Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $config = $this->processConfiguration(new Configuration(), $configs);

        $locator = new FileLocator(__DIR__.'/../Resources/config/');
        $loader = new XmlFileLoader($container, $locator);

        $loader->load('services.xml');

        $container->getDefinition('wakeonweb_kong_oauth2_server.client.admin')->replaceArgument(0, [
            'base_uri' => $config['kong']['admin_url'],
        ]);

        $container->getDefinition('wakeonweb_kong_oauth2_server.client.api')->replaceArgument(0, [
            'base_uri' => $config['kong']['api_url'],
        ]);

        $container->getDefinition(AuthorizeController::class)->replaceArgument(0, $config['cancel_path']);

        $container->getDefinition(ApiClient::class)->replaceArgument(1, $config['kong']['provision_key']);

        $container->setAlias(IdentityResolver::class, $config['identity_resolver']);
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias(): string
    {
        return 'wakeonweb_kong_oauth2_server';
    }
}
