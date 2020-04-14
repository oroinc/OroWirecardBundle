<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Method\Config\Provider;

use Oro\Bundle\WirecardBundle\Method\Config\Factory\WirecardSeamlessPayPalConfigFactory;
use Oro\Bundle\WirecardBundle\Method\Config\Provider\WirecardSeamlessPayPalConfigProvider;
use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessPayPalConfig;

class WirecardSeamlessPayPalConfigProviderTest extends AbstractWirecardSeamlessConfigProviderTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->factory = $this->createMock(WirecardSeamlessPayPalConfigFactory::class);
        $this->factory->expects($this->once())->method('createConfig')->with($this->wireCardSettings)
            ->willReturn(
                new WirecardSeamlessPayPalConfig(
                    [
                        WirecardSeamlessPayPalConfig::FIELD_PAYMENT_METHOD_IDENTIFIER =>
                            'test_payment_method_identifier',
                    ]
                )
            );

        $this->configProvider = new WirecardSeamlessPayPalConfigProvider(
            $this->doctrine,
            $this->logger,
            $this->factory,
            $this->type
        );
    }

    /** {@inheritdoc} */
    public function expectedConfigDataProvider(): array
    {
        return [[WirecardSeamlessPayPalConfig::class]];
    }
}
