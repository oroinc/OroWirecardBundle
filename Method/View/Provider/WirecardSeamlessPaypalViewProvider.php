<?php

namespace Oro\Bundle\WirecardBundle\Method\View\Provider;

use Oro\Bundle\PaymentBundle\Method\View\AbstractPaymentMethodViewProvider;
use Oro\Bundle\WirecardBundle\Method\Config\Provider\WirecardSeamlessPaypalConfigProviderInterface;
use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessPaypalConfigInterface;
use Oro\Bundle\WirecardBundle\Method\View\Factory\WirecardSeamlessPaypalViewFactoryInterface;

class WirecardSeamlessPaypalViewProvider extends AbstractPaymentMethodViewProvider
{
    /** @var WirecardSeamlessPaypalViewFactoryInterface */
    private $factory;

    /** @var WirecardSeamlessPaypalConfigProviderInterface */
    private $configProvider;

    /**
     * @param WirecardSeamlessPaypalViewFactoryInterface    $factory
     * @param WirecardSeamlessPaypalConfigProviderInterface $configProvider
     */
    public function __construct(
        WirecardSeamlessPaypalViewFactoryInterface $factory,
        WirecardSeamlessPaypalConfigProviderInterface $configProvider
    ) {
        $this->factory = $factory;
        $this->configProvider = $configProvider;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function buildViews()
    {
        $configs = $this->configProvider->getPaymentConfigs();
        foreach ($configs as $config) {
            $this->addPaypalView($config);
        }
    }

    /**
     * @param WirecardSeamlessPaypalConfigInterface $config
     */
    protected function addPaypalView(WirecardSeamlessPaypalConfigInterface $config)
    {
        $this->addView(
            $config->getPaymentMethodIdentifier(),
            $this->factory->create($config)
        );
    }
}
