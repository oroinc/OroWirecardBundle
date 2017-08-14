<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Method\Config\Factory;

use Oro\Bundle\WirecardBundle\Method\Config\Factory\WirecardSeamlessConfigFactoryInterface;
use Oro\Bundle\WirecardBundle\Method\Config\Factory\WirecardSeamlessCreditCardConfigFactory;
use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessConfig;
use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessCreditCardConfig;

class WirecardSeamlessCreditCardConfigFactoryTest extends AbstractWirecardSeamlessConfigFactoryTestCase
{
    /** {@inheritdoc} */
    protected function getConfigFactory(): WirecardSeamlessConfigFactoryInterface
    {
        return new WirecardSeamlessCreditCardConfigFactory(
            $this->encoder,
            $this->localizationHelper,
            $this->languageCodeMapper,
            $this->identifierGenerator
        );
    }

    /** {@inheritdoc} */
    public function wirecardSeamlessConfigProvider(): array
    {
        $params = array_merge($this->getBaseConfigParameters('Credit Card'), [
            WirecardSeamlessConfig::FIELD_LABEL => 'credit card label',
            WirecardSeamlessConfig::FIELD_SHORT_LABEL => 'credit card short label',
        ]);

        return [[new WirecardSeamlessCreditCardConfig($params)]];
    }
}
