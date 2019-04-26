<?php

namespace Oro\Bundle\WirecardBundle\Method\Factory;

use Oro\Bundle\EntityBundle\ORM\DoctrineHelper;
use Oro\Bundle\WirecardBundle\OptionsProvider\OptionsProviderInterface;
use Oro\Bundle\WirecardBundle\Provider\PaymentTransactionProvider;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\GatewayInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;

/**
 * Abstract class for all Wirecard Seamless payment methods.
 * Defines constructor for abstract service oro_wirecard.method.factory.wirecard_seamless
 */
abstract class WirecardSeamlessPaymentMethodFactory
{
    /**
     * @var PaymentTransactionProvider
     */
    protected $transactionProvider;

    /**
     * @var GatewayInterface
     */
    protected $gateway;

    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * @var DoctrineHelper
     */
    protected $doctrineHelper;

    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * @var OptionsProviderInterface
     */
    protected $optionsProvider;

    /**
     * @param PaymentTransactionProvider $transactionProvider
     * @param GatewayInterface $gateway
     * @param RouterInterface $router
     * @param DoctrineHelper $doctrineHelper
     * @param RequestStack $requestStack
     * @param OptionsProviderInterface $optionsProvider
     */
    public function __construct(
        PaymentTransactionProvider $transactionProvider,
        GatewayInterface $gateway,
        RouterInterface $router,
        DoctrineHelper $doctrineHelper,
        RequestStack $requestStack,
        OptionsProviderInterface $optionsProvider
    ) {
        $this->transactionProvider = $transactionProvider;
        $this->gateway = $gateway;
        $this->router = $router;
        $this->doctrineHelper = $doctrineHelper;
        $this->requestStack = $requestStack;
        $this->optionsProvider = $optionsProvider;
    }
}
