<?php

namespace Oro\Bundle\WirecardBundle\Method\View\Provider;

use Oro\Bundle\PaymentBundle\Method\View\AbstractPaymentMethodViewProvider;
use Oro\Bundle\WirecardBundle\Method\Config\Provider\WirecardSeamlessCreditCardConfigProviderInterface;
use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessCreditCardConfigInterface;
use Oro\Bundle\WirecardBundle\Method\View\Factory\WirecardSeamlessCreditCardViewFactoryInterface;

class WirecardSeamlessCreditCardViewProvider extends AbstractPaymentMethodViewProvider
{
    /** @var WirecardSeamlessCreditCardViewFactoryInterface */
    private $factory;

    /** @var WirecardSeamlessCreditCardConfigProviderInterface */
    private $configProvider;

    /**
     * @param WirecardSeamlessCreditCardViewFactoryInterface    $factory
     * @param WirecardSeamlessCreditCardConfigProviderInterface $configProvider
     */
    public function __construct(
        WirecardSeamlessCreditCardViewFactoryInterface $factory,
        WirecardSeamlessCreditCardConfigProviderInterface $configProvider
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
            $this->addCreditCardView($config);
        }
    }

    /**
     * @param WirecardSeamlessCreditCardConfigInterface $config
     */
    protected function addCreditCardView(WirecardSeamlessCreditCardConfigInterface $config)
    {
        $this->addView(
            $config->getPaymentMethodIdentifier(),
            $this->factory->create($config)
        );
    }
}
