<?php

namespace Oro\Bundle\WirecardBundle;

use Oro\Bundle\WirecardBundle\DependencyInjection\OroWirecardExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class OroWirecardBundle extends Bundle
{
    /**
     * @return OroWirecardExtension
     */
    public function getContainerExtension()
    {
        if (!$this->extension) {
            $this->extension = new OroWirecardExtension();
        }

        return $this->extension;
    }
}
