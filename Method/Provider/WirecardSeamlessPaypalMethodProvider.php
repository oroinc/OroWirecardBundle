<?php

namespace Oro\Bundle\WirecardBundle\Method\Provider;

use Oro\Bundle\PaymentBundle\Method\Provider\AbstractPaymentMethodProvider;
use Oro\Bundle\WirecardBundle\Method\Config\Provider\WirecardSeamlessPaypalConfigProviderInterface;
use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessPaypalConfigInterface;
use Oro\Bundle\WirecardBundle\Method\Factory\WirecardSeamlessPaypalPaymentMethodFactoryInterface;

class WirecardSeamlessPaypalMethodProvider extends AbstractPaymentMethodProvider
{
    /**
     * @var WirecardSeamlessPaypalPaymentMethodFactoryInterface
     */
    private $factory;

    /**
     * @var WirecardSeamlessPaypalConfigProviderInterface
     */
    private $configProvider;

    /**
     * @param WirecardSeamlessPaypalConfigProviderInterface       $configProvider
     * @param WirecardSeamlessPaypalPaymentMethodFactoryInterface $factory
     */
    public function __construct(
        WirecardSeamlessPaypalConfigProviderInterface $configProvider,
        WirecardSeamlessPaypalPaymentMethodFactoryInterface $factory
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
            $this->addPaypalMethod($config);
        }
    }

    /**
     * @param WirecardSeamlessPaypalConfigInterface $config
     */
    protected function addPaypalMethod(WirecardSeamlessPaypalConfigInterface $config)
    {
        $this->addMethod(
            $config->getPaymentMethodIdentifier(),
            $this->factory->create($config)
        );
    }
}
