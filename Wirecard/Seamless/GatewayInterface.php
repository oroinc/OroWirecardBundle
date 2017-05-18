<?php

namespace Oro\Bundle\WirecardBundle\Wirecard\Seamless;

use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Request\RequestInterface;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Response\ResponseInterface;

interface GatewayInterface
{
    /**
     * @param RequestInterface $request
     * @param array $options
     * @return ResponseInterface
     */
    public function request(RequestInterface $request, array $options = []);
}
