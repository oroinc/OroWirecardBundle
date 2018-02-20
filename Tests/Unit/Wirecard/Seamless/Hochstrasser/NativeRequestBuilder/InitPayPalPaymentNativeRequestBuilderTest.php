<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Wirecard\Seamless\Hochstrasser\NativeRequestBuilder;

use Hochstrasser\Wirecard\Request\Seamless\Frontend\InitPaymentRequest as WirecardInitPaymentRequest;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Hochstrasser\NativeRequestBuilder\InitPayPalPaymentNativeRequestBuilder;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Request\InitPayPalPaymentRequest;

class InitPayPalPaymentNativeRequestBuilderTest extends AbstractNativeRequestBuilderTest
{
    public function setUp()
    {
        $this->nativeRequestBuilder = new InitPayPalPaymentNativeRequestBuilder();
    }

    /** {@inheritdoc} */
    public function expectedClassDataProvider(): array
    {
        return [[WirecardInitPaymentRequest::class]];
    }

    /** {@inheritdoc} */
    public function expectedRequestIdentifierDataProvider(): array
    {
        return [[InitPayPalPaymentRequest::IDENTIFIER]];
    }
}
