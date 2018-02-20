<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Wirecard\Seamless\Hochstrasser\NativeRequestBuilder;

use Hochstrasser\Wirecard\Request\Seamless\Frontend\InitDataStorageRequest as WirecardInitDataStorageRequest;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Hochstrasser\NativeRequestBuilder\InitDataStorageNativeRequestBuilder;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Request\InitDataStorageRequest;

class InitDataStorageNativeRequestBuilderTest extends AbstractNativeRequestBuilderTest
{
    public function setUp()
    {
        $this->options[Option\OrderIdent::ORDERIDENT] = 'indent';
        $this->options[Option\ReturnUrl::RETURNURL] = 'returnUrl';
        $this->nativeRequestBuilder = new InitDataStorageNativeRequestBuilder();
    }

    /** {@inheritdoc} */
    public function expectedClassDataProvider(): array
    {
        return [[WirecardInitDataStorageRequest::class]];
    }

    /** {@inheritdoc} */
    public function expectedRequestIdentifierDataProvider(): array
    {
        return [[InitDataStorageRequest::IDENTIFIER]];
    }
}
