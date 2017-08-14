<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Method\Config;

use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessCreditCardConfig;

class WirecardSeamlessCreditCardConfigTest extends AbstractWirecardConfigTestCase
{
    /** {@inheritdoc} */
    protected function getPaymentConfig()
    {
        return new WirecardSeamlessCreditCardConfig($this->getPaymentConfigParams());
    }
}
