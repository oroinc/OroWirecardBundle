<?php

namespace Oro\Bundle\WirecardBundle\Method\Provider;

use Oro\Bundle\PaymentBundle\Method\Provider\AbstractPaymentMethodProvider;
use Oro\Bundle\WirecardBundle\Method\Config\Provider\WirecardSeamlessCreditCardConfigProviderInterface;
use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessCreditCardConfigInterface;
use Oro\Bundle\WirecardBundle\Method\Factory\WirecardSeamlessCreditCardPaymentMethodFactoryInterface;

class WirecardSeamlessCreditCardMethodProvider extends AbstractPaymentMethodProvider
{
    /**
     * @var WirecardSeamlessCreditCardPaymentMethodFactoryInterface
     */
    private $factory;

    /**
     * @var WirecardSeamlessCreditCardConfigProviderInterface
     */
    private $configProvider;

    /**
     * @param WirecardSeamlessCreditCardConfigProviderInterface       $configProvider
     * @param WirecardSeamlessCreditCardPaymentMethodFactoryInterface $factory
     */
    public function __construct(
        WirecardSeamlessCreditCardConfigProviderInterface $configProvider,
        WirecardSeamlessCreditCardPaymentMethodFactoryInterface $factory
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
            $this->addCreditCardMethod($config);
        }
    }

    /**
     * @param WirecardSeamlessCreditCardConfigInterface $config
     */
    protected function addCreditCardMethod(WirecardSeamlessCreditCardConfigInterface $config)
    {
        $this->addMethod(
            $config->getPaymentMethodIdentifier(),
            $this->factory->create($config)
        );
    }
}
