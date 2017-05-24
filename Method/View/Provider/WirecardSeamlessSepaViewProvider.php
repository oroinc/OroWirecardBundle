<?php

namespace Oro\Bundle\WirecardBundle\Method\View\Provider;

use Oro\Bundle\PaymentBundle\Method\View\AbstractPaymentMethodViewProvider;
use Oro\Bundle\WirecardBundle\Method\Config\Provider\WirecardSeamlessSepaConfigProviderInterface;
use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessSepaConfigInterface;
use Oro\Bundle\WirecardBundle\Method\View\Factory\WirecardSeamlessSepaViewFactoryInterface;

class WirecardSeamlessSepaViewProvider extends AbstractPaymentMethodViewProvider
{
    /** @var WirecardSeamlessSepaViewFactoryInterface */
    private $factory;

    /** @var WirecardSeamlessSepaConfigProviderInterface */
    private $configProvider;

    /**
     * @param WirecardSeamlessSepaViewFactoryInterface $factory
     * @param WirecardSeamlessSepaConfigProviderInterface $configProvider
     */
    public function __construct(
        WirecardSeamlessSepaViewFactoryInterface $factory,
        WirecardSeamlessSepaConfigProviderInterface $configProvider
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
            $this->addSepaView($config);
        }
    }

    /**
     * @param WirecardSeamlessSepaConfigInterface $config
     */
    protected function addSepaView(WirecardSeamlessSepaConfigInterface $config)
    {
        $this->addView(
            $config->getPaymentMethodIdentifier(),
            $this->factory->create($config)
        );
    }
}
