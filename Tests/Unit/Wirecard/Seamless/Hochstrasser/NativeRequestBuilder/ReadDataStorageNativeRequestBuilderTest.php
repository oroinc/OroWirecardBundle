<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Wirecard\Seamless\Hochstrasser\NativeRequestBuilder;

use Hochstrasser\Wirecard\Request\Seamless\Frontend\ReadDataStorageRequest as WirecardReadDataStorageRequest;

use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Hochstrasser\NativeRequestBuilder\ReadDataStorageNativeRequestBuilder;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Request\ReadDataStorageRequest;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option;

class ReadDataStorageNativeRequestBuilderTest extends AbstractNativeRequestBuilderTest
{
    public function setUp()
    {
        $this->options[Option\StorageId::STORAGEID] = 123;
        $this->nativeRequestBuilder = new ReadDataStorageNativeRequestBuilder();
    }

    /** {@inheritdoc} */
    public function expectedClassDataProvider(): array
    {
        return [[WirecardReadDataStorageRequest::class]];
    }

    /** {@inheritdoc} */
    public function expectedRequestIdentifierDataProvider(): array
    {
        return [[ReadDataStorageRequest::IDENTIFIER]];
    }
}
