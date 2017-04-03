<?php

namespace Oro\Bundle\WirecardBundle\Method;

interface WirecardSeamlessPaymentMethodInterface
{
    /**
     * @return string
     */
    public function getWirecardPaymentType();
}
