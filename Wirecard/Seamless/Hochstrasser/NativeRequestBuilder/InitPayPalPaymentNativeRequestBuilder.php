<?php

namespace Oro\Bundle\WirecardBundle\Wirecard\Seamless\Hochstrasser\NativeRequestBuilder;

use Hochstrasser\Wirecard\Request\Seamless\Frontend\InitPaymentRequest as WirecardInitPaymentRequest;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Request\InitPayPalPaymentRequest;

class InitPayPalPaymentNativeRequestBuilder extends AbstractNativeRequestBuilder
{
    /**
     * {@inheritdoc}
     */
    public function createNativeRequest(array $options = [])
    {
        $request = WirecardInitPaymentRequest::with();
        $request->setContext($this->buildContext($options));

        foreach ($options as $key => $value) {
            $request->addParam($key, $value);
        }

        return $request;
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestIdentifier()
    {
        return InitPayPalPaymentRequest::IDENTIFIER;
    }
}
