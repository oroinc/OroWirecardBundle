<?php

namespace Oro\Bundle\WirecardBundle\Method\View\Provider;

use Oro\Bundle\PaymentBundle\Method\View\AbstractPaymentMethodViewProvider;
use Oro\Bundle\WirecardBundle\Method\Config\Provider\WirecardSeamlessPayPalConfigProviderInterface;
use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessPayPalConfigInterface;
use Oro\Bundle\WirecardBundle\Method\View\Factory\WirecardSeamlessPayPalViewFactoryInterface;

class WirecardSeamlessPayPalViewProvider extends AbstractPaymentMethodViewProvider
{
    /** @var WirecardSeamlessPayPalViewFactoryInterface */
    private $factory;

    /** @var WirecardSeamlessPayPalConfigProviderInterface */
    private $configProvider;

    /**
     * @param WirecardSeamlessPayPalViewFactoryInterface $factory
     * @param WirecardSeamlessPayPalConfigProviderInterface $configProvider
     */
    public function __construct(
        WirecardSeamlessPayPalViewFactoryInterface $factory,
        WirecardSeamlessPayPalConfigProviderInterface $configProvider
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
            $this->addPayPalView($config);
        }
    }

    /**
     * @param WirecardSeamlessPayPalConfigInterface $config
     */
    protected function addPayPalView(WirecardSeamlessPayPalConfigInterface $config)
    {
        $this->addView(
            $config->getPaymentMethodIdentifier(),
            $this->factory->create($config)
        );
    }
}
