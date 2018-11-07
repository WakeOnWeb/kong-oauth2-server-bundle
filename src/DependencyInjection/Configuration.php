<?php

namespace WakeOnWeb\Bundle\KongOAuth2ServerBundle\DependencyInjection;

use WakeOnWeb\Bundle\KongOAuth2ServerBundle\User\UsernameAsIdentity;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Quentin Schuler <q.schuler@wakeonweb.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $tree = new TreeBuilder();
        $root = $tree->root('wakeonweb_kong_oauth2_server');

        $root
            ->children()
                ->arrayNode('kong')
                    ->children()
                        ->scalarNode('admin_url')->isRequired()->end()
                        ->scalarNode('api_url')->isRequired()->end()
                        ->scalarNode('provision_key')->isRequired()->end()
                    ->end()
                ->end()
                ->scalarNode('cancel_path')->isRequired()->end()
                ->scalarNode('identity_resolver')->defaultValue(UsernameAsIdentity::class)->end()
            ->end()
        ;

        return $tree;
    }
}
