<?php

namespace Oro\Bundle\WirecardBundle\Method\Provider;

use Oro\Bundle\PaymentBundle\Method\Provider\AbstractPaymentMethodProvider;
use Oro\Bundle\WirecardBundle\Method\Config\Provider\WirecardSeamlessSepaConfigProviderInterface;
use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessSepaConfigInterface;
use Oro\Bundle\WirecardBundle\Method\Factory\WirecardSeamlessSepaPaymentMethodFactoryInterface;

class WirecardSeamlessSepaMethodProvider extends AbstractPaymentMethodProvider
{
    /**
     * @var WirecardSeamlessSepaPaymentMethodFactoryInterface
     */
    private $factory;

    /**
     * @var WirecardSeamlessSepaConfigProviderInterface
     */
    private $configProvider;

    /**
     * @param WirecardSeamlessSepaConfigProviderInterface $configProvider
     * @param WirecardSeamlessSepaPaymentMethodFactoryInterface $factory
     */
    public function __construct(
        WirecardSeamlessSepaConfigProviderInterface $configProvider,
        WirecardSeamlessSepaPaymentMethodFactoryInterface $factory
    ) {
        parent::__construct();
        $this->configProvider = $configProvider;
        $this->factory = $factory;
    }

    /**
     * {@inheritdoc}
     */
    protected function collectMethods()
    {
        $configs = $this->configProvider->getPaymentConfigs();
        foreach ($configs as $config) {
            $this->addSepaMethod($config);
        }
    }

    /**
     * @param WirecardSeamlessSepaConfigInterface $config
     */
    protected function addSepaMethod(WirecardSeamlessSepaConfigInterface $config)
    {
        $this->addMethod(
            $config->getPaymentMethodIdentifier(),
            $this->factory->create($config)
        );
    }
}
