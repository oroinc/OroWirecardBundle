<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Method\Config\Factory;

use Oro\Bundle\WirecardBundle\Method\Config\Factory\WirecardSeamlessConfigFactoryInterface;
use Oro\Bundle\WirecardBundle\Method\Config\Factory\WirecardSeamlessSepaConfigFactory;
use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessConfig;
use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessSepaConfig;

class WirecardSeamlessSepaConfigFactoryTest extends AbstractWirecardSeamlessConfigFactoryTestCase
{
    /** {@inheritdoc} */
    protected function getConfigFactory(): WirecardSeamlessConfigFactoryInterface
    {
        return new WirecardSeamlessSepaConfigFactory(
            $this->encoder,
            $this->localizationHelper,
            $this->languageCodeMapper,
            $this->identifierGenerator
        );
    }

    /** {@inheritdoc} */
    public function wirecardSeamlessConfigProvider(): array
    {
        $params = array_merge($this->getBaseConfigParameters('SEPA'), [
            WirecardSeamlessConfig::FIELD_LABEL => 'sepa label',
            WirecardSeamlessConfig::FIELD_SHORT_LABEL => 'sepa short label',
        ]);

        return [[new WirecardSeamlessSepaConfig($params)]];
    }
}
