<?php

namespace Oro\Bundle\WirecardBundle\Wirecard\Seamless\Hochstrasser\NativeRequestBuilder;

use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Request\InitSepaPaymentRequest;

class InitSepaPaymentNativeRequestBuilder extends InitPaymentNativeRequestBuilder
{
    /**
     * {@inheritdoc}
     */
    public function getRequestIdentifier()
    {
        return InitSepaPaymentRequest::IDENTIFIER;
    }
}
