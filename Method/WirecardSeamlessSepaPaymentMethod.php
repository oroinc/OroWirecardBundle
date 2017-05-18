<?php

namespace Oro\Bundle\WirecardBundle\Method;

class WirecardSeamlessSepaPaymentMethod extends WirecardSeamlessInitiateAwarePaymentMethod
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
