<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Method\Config;

use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessSepaConfig;

class WirecardSeamlessSepaConfigTest extends AbstractWirecardConfigTestCase
{
    /** {@inheritdoc} */
    protected function getPaymentConfig()
    {
        return new WirecardSeamlessSepaConfig($this->getPaymentConfigParams());
    }
}
