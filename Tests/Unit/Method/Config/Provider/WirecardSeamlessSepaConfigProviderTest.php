<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Method\Config\Provider;

use Oro\Bundle\WirecardBundle\Method\Config\Factory\WirecardSeamlessSepaConfigFactory;
use Oro\Bundle\WirecardBundle\Method\Config\Provider\WirecardSeamlessSepaConfigProvider;
use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessSepaConfig;

class WirecardSeamlessSepaConfigProviderTest extends AbstractWirecardSeamlessConfigProviderTestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->factory = $this->createMock(WirecardSeamlessSepaConfigFactory::class);
        $this->factory->expects($this->once())->method('createConfig')->with($this->wireCardSettings)
            ->willReturn(
                new WirecardSeamlessSepaConfig(
                    [
                        WirecardSeamlessSepaConfig::FIELD_PAYMENT_METHOD_IDENTIFIER =>
                            'test_payment_method_identifier',
                    ]
                )
            );

        $this->configProvider = new WirecardSeamlessSepaConfigProvider(
            $this->doctrine,
            $this->logger,
            $this->factory,
            $this->type
        );
    }

    /** {@inheritdoc} */
    public function expectedConfigDataProvider(): array
    {
        return [[WirecardSeamlessSepaConfig::class]];
    }
}
