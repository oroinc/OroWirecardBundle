<?php

namespace Oro\Bundle\WirecardBundle\Wirecard\Seamless\Hochstrasser\NativeRequestBuilder;

use Hochstrasser\Wirecard\Request\WirecardRequestInterface;
use Hochstrasser\Wirecard\Request\Seamless\Frontend\ReadDataStorageRequest as WirecardReadDataStorageRequest;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Request\ReadDataStorageRequest;

class ReadDataStorageNativeRequestBuilder extends AbstractNativeRequestBuilder
{
    /**
     * @param array $options
     * @return WirecardRequestInterface
     */
    public function createNativeRequest(array $options = [])
    {
        $storageId = $options[Option\StorageId::STORAGEID];
        $request = WirecardReadDataStorageRequest::withStorageId($storageId);

        $request->setContext($this->buildContext($options));

        return $request;
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestIdentifier()
    {
        return ReadDataStorageRequest::IDENTIFIER;
    }
}
