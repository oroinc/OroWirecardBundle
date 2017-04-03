<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Integration;

use Oro\Bundle\WirecardBundle\Entity\WirecardSeamlessSettings;
use Oro\Bundle\WirecardBundle\Form\Type\WirecardSeamlessSettingsType;
use Oro\Bundle\WirecardBundle\Integration\WirecardSeamlessTransport;

class WirecardSeamlessTransportTest extends \PHPUnit_Framework_TestCase
{
    /** @var WirecardSeamlessTransport */
    private $transport;

    protected function setUp()
    {
        $this->transport = new WirecardSeamlessTransport();
    }

    public function testInitCompiles()
    {
         $settings = new WirecardSeamlessSettings();
         $this->transport->init($settings);
         $this->assertAttributeSame($settings->getSettingsBag(), 'settings', $this->transport);
    }

    public function testGetSettingsFormType()
    {
        static::assertSame(WirecardSeamlessSettingsType::class, $this->transport->getSettingsFormType());
    }

    public function testGetSettingsEntityFQCN()
    {
        static::assertSame(WirecardSeamlessSettings::class, $this->transport->getSettingsEntityFQCN());
    }

    public function testGetLabelReturnsString()
    {
        static::assertTrue(is_string($this->transport->getLabel()));
    }
}
