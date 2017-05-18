<?php

namespace Oro\Bundle\WirecardBundle\Wirecard\Seamless\Hochstrasser\NativeRequestBuilder;

use Hochstrasser\Wirecard\Request\WirecardRequestInterface;
use Hochstrasser\Wirecard\Request\Seamless\Frontend\InitDataStorageRequest as WirecardInitDataStorageRequest;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Request\InitDataStorageRequest;

class InitDataStorageNativeRequestBuilder extends AbstractNativeRequestBuilder
{
    /**
     * @param array $options
     * @return WirecardRequestInterface
     */
    public function createNativeRequest(array $options = [])
    {
        $request = WirecardInitDataStorageRequest::withOrderIdentAndReturnUrl(
            $options[Option\OrderIdent::ORDERIDENT],
            $options[Option\ReturnUrl::RETURNURL]
        );
        $request->setContext($this->buildContext($options));

        return $request;
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestIdentifier()
    {
        return InitDataStorageRequest::IDENTIFIER;
    }
}
