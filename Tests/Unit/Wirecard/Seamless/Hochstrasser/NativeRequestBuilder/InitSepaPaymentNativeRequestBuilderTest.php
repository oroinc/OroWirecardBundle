<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Wirecard\Seamless\Hochstrasser\NativeRequestBuilder;

use Hochstrasser\Wirecard\Request\Seamless\Frontend\InitPaymentRequest as WirecardInitPaymentRequest;

use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Hochstrasser\NativeRequestBuilder\InitSepaPaymentNativeRequestBuilder;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Request\InitSepaPaymentRequest;

class InitSepaPaymentNativeRequestBuilderTest extends AbstractNativeRequestBuilderTest
{
    public function setUp()
    {
        $this->nativeRequestBuilder = new InitSepaPaymentNativeRequestBuilder();
    }

    /** {@inheritdoc} */
    public function expectedClassDataProvider(): array
    {
        return [[WirecardInitPaymentRequest::class]];
    }

    /** {@inheritdoc} */
    public function expectedRequestIdentifierDataProvider(): array
    {
        return [[InitSepaPaymentRequest::IDENTIFIER]];
    }
}
