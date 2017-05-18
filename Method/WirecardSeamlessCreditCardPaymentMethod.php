<?php

namespace Oro\Bundle\WirecardBundle\Method;

class WirecardSeamlessCreditCardPaymentMethod extends WirecardSeamlessInitiateAwarePaymentMethod
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
