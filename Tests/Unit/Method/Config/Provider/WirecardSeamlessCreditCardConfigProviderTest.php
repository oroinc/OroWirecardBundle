<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Method\Config\Provider;

use Oro\Bundle\WirecardBundle\Method\Config\Factory\WirecardSeamlessCreditCardConfigFactory;
use Oro\Bundle\WirecardBundle\Method\Config\Provider\WirecardSeamlessCreditCardConfigProvider;
use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessCreditCardConfig;

class WirecardSeamlessCreditCardConfigProviderTest extends AbstractWirecardSeamlessConfigProviderTestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->factory = $this->createMock(WirecardSeamlessCreditCardConfigFactory::class);
        $this->factory->expects($this->once())->method('createConfig')->with($this->wireCardSettings)
            ->willReturn(
                new WirecardSeamlessCreditCardConfig(
                    [
                        WirecardSeamlessCreditCardConfig::FIELD_PAYMENT_METHOD_IDENTIFIER =>
                            'test_payment_method_identifier',
                    ]
                )
            );

        $this->configProvider = new WirecardSeamlessCreditCardConfigProvider(
            $this->doctrine,
            $this->logger,
            $this->factory,
            $this->type
        );
    }

    /** {@inheritdoc} */
    public function expectedConfigDataProvider(): array
    {
        return [[WirecardSeamlessCreditCardConfig::class]];
    }
}
