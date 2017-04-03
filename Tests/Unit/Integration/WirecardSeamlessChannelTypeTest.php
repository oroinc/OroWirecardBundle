<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Integration;

use Oro\Bundle\WirecardBundle\Integration\WirecardSeamlessChannelType;

class WirecardSeamlessChannelTypeTest extends \PHPUnit_Framework_TestCase
{
    /** @var WirecardSeamlessChannelType */
    private $channel;

    protected function setUp()
    {
        $this->channel = new WirecardSeamlessChannelType();
    }

    public function testGetLabelReturnsString()
    {
        static::assertTrue(is_string($this->channel->getLabel()));
    }

    public function testGetIconReturnsString()
    {
        static::assertTrue(is_string($this->channel->getIcon()));
    }
}
