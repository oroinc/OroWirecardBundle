<?php

namespace Oro\Bundle\WirecardBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class OroWirecardExtension extends Extension
{
    const ALIAS = 'oro_wirecard';

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        $loader->load('form_types.yml');
        $loader->load('method.yml');
        $loader->load('hochstrasser.yml');
        $loader->load('callbacks.yml');

        // TODO: BB-9506 Avoid test logic in production code
        if ($container->getParameter('kernel.environment') === 'test') {
            $loader->load('payment_test.yml');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return self::ALIAS;
    }
}
