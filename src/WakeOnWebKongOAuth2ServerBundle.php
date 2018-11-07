<?php

namespace WakeOnWeb\Bundle\KongOAuth2ServerBundle;

use WakeOnWeb\Bundle\KongOAuth2ServerBundle\DependencyInjection\WakeOnWebKongOAuth2ServerExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Quentin Schuler <q.schuler@wakeonweb.com>
 */
class WakeOnWebKongOAuth2ServerBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getContainerExtension()
    {
        return new WakeOnWebKongOAuth2ServerExtension();
    }
}
