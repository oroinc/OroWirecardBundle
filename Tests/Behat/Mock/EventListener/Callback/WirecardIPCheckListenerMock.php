<?php

namespace Oro\Bundle\WirecardBundle\Tests\Behat\Mock\EventListener\Callback;

use Oro\Bundle\WirecardBundle\EventListener\Callback\WirecardIPCheckListener;

class WirecardIPCheckListenerMock extends WirecardIPCheckListener
{
    /**
     * @var string[]
     */
    protected $allowedIPs = [
        '0.0.0.0/0',
    ];
}
