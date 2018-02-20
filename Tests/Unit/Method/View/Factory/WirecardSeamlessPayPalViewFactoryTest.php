<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Method\View\Factory;

use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessPayPalConfigInterface;
use Oro\Bundle\WirecardBundle\Method\View\Factory\WirecardSeamlessPayPalViewFactory;
use Oro\Bundle\WirecardBundle\Method\View\WirecardSeamlessPayPalView;
use Symfony\Component\Form\FormFactoryInterface;

class WirecardSeamlessPayPalViewFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var FormFactoryInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $formFactory;

    /** @var WirecardSeamlessPayPalViewFactory */
    protected $factory;

    protected function setUp()
    {
        $this->formFactory = $this->createMock(FormFactoryInterface::class);
        $this->factory = new WirecardSeamlessPayPalViewFactory($this->formFactory);
    }

    public function testCreate()
    {
        $config = $this->createMock(WirecardSeamlessPayPalConfigInterface::class);
        $expectedView = new WirecardSeamlessPayPalView($this->formFactory, $config);
        $this->assertEquals($expectedView, $this->factory->create($config));
    }
}
