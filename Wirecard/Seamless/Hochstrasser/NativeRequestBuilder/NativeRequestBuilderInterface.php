<?php

namespace Oro\Bundle\WirecardBundle\Wirecard\Seamless\Hochstrasser\NativeRequestBuilder;

use Hochstrasser\Wirecard\Request\WirecardRequestInterface;

interface NativeRequestBuilderInterface
{
    /**
     * @param array $options
     * @return WirecardRequestInterface
     */
    public function createNativeRequest(array $options = []);

    /**
     * Returns identifier of request which will be handled by this builder
     *
     * @return string
     */
    public function getRequestIdentifier();
}
