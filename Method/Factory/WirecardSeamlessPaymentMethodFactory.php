<?php

namespace Oro\Bundle\WirecardBundle\Method\Factory;

use Oro\Bundle\WirecardBundle\Provider\PaymentTransactionProvider;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Gateway;
use Symfony\Component\Routing\RouterInterface;

abstract class WirecardSeamlessPaymentMethodFactory
{
    /**
     * @var PaymentTransactionProvider
     */
    protected $transactionProvider;

    /**
     * @var Gateway
     */
    protected $gateway;

    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * @param PaymentTransactionProvider $transactionProvider
     * @param Gateway                    $gateway
     * @param RouterInterface            $router
     */
    public function __construct(
        PaymentTransactionProvider $transactionProvider,
        Gateway $gateway,
        RouterInterface $router
    ) {
        $this->transactionProvider = $transactionProvider;
        $this->gateway = $gateway;
        $this->router = $router;
    }
}
