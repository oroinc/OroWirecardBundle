<?php

namespace Oro\Bundle\WirecardBundle\Method;

class WirecardSeamlessSepaPaymentMethod extends WirecardSeamlessPaymentMethod
{
    const TYPE = 'SEPA-DD';

    /**
     * {@inheritdoc}
     */
    public function getWirecardPaymentType()
    {
        return static::TYPE;
    }
}
