<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Method\Config;

use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessPayPalConfig;

class WirecardSeamlessPayPalConfigTest extends AbstractWirecardConfigTestCase
{
    /** {@inheritdoc} */
    protected function getPaymentConfig()
    {
        return new WirecardSeamlessPayPalConfig($this->getPaymentConfigParams());
    }
}
