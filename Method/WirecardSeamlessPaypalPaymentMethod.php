<?php

namespace Oro\Bundle\WirecardBundle\Method;

class WirecardSeamlessPaypalPaymentMethod extends WirecardSeamlessPaymentMethod
{
    const TYPE = 'PAYPAL';

    /**
     * {@inheritdoc}
     */
    public function getWirecardPaymentType()
    {
        return static::TYPE;
    }
}
