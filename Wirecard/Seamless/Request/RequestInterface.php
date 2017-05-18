<?php

namespace Oro\Bundle\WirecardBundle\Wirecard\Seamless\Request;

use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option\OptionsAwareInterface;

interface RequestInterface extends OptionsAwareInterface
{
    /**
     * @return string
     */
    public function getRequestIdentifier();
}
