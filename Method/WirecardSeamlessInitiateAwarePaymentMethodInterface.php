<?php

namespace Oro\Bundle\WirecardBundle\Method;

use Oro\Bundle\PaymentBundle\Method\PaymentMethodInterface;

/**
 * This interface is used to verify that payment method is wirecard initiate-aware class
 * It allows to prevent using of initiateAction with any other disallowed payment method
 */
interface WirecardSeamlessInitiateAwarePaymentMethodInterface extends PaymentMethodInterface
{
    const INITIATE = 'initiate';
}
