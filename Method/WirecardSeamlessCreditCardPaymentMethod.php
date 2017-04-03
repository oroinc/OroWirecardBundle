<?php

namespace Oro\Bundle\WirecardBundle\Method;

class WirecardSeamlessCreditCardPaymentMethod extends WirecardSeamlessPaymentMethod
{
    const TYPE = 'CCARD';

    /**
     * {@inheritdoc}
     */
    public function getWirecardPaymentType()
    {
        return static::TYPE;
    }
}
