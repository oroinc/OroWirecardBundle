<?php

namespace Oro\Bundle\WirecardBundle\Wirecard\Seamless\Request;

use Hochstrasser\Wirecard\Request\AbstractWirecardRequest;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option\OptionsAwareInterface;

interface RequestInterface extends OptionsAwareInterface
{
    /**
     * @param array $options
     *
     * @return AbstractWirecardRequest
     */
    public function buildWirecardRequest(array $options = []);
}
