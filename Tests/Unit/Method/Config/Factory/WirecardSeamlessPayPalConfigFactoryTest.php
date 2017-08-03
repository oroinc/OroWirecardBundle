<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Method\Config\Factory;

use Oro\Bundle\WirecardBundle\Method\Config\Factory\WirecardSeamlessConfigFactoryInterface;
use Oro\Bundle\WirecardBundle\Method\Config\Factory\WirecardSeamlessPayPalConfigFactory;
use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessConfig;
use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessPayPalConfig;

class WirecardSeamlessPayPalConfigFactoryTest extends AbstractWirecardSeamlessConfigFactoryTestCase
{
    /** {@inheritdoc} */
    protected function getConfigFactory(): WirecardSeamlessConfigFactoryInterface
    {
        return new WirecardSeamlessPayPalConfigFactory(
            $this->encoder,
            $this->localizationHelper,
            $this->languageCodeMapper,
            $this->identifierGenerator
        );
    }

    /** {@inheritdoc} */
    public function wirecardSeamlessConfigProvider(): array
    {
        $params = array_merge($this->getBaseConfigParameters('PayPal'), [
            WirecardSeamlessConfig::FIELD_LABEL => 'paypal label',
            WirecardSeamlessConfig::FIELD_SHORT_LABEL => 'paypal short label',
        ]);

        return [[new WirecardSeamlessPayPalConfig($params)]];
    }
}
