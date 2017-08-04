<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Method\View\Factory;

use Symfony\Component\Form\FormFactoryInterface;

use Oro\Bundle\WirecardBundle\Method\View\Factory\WirecardSeamlessCreditCardViewFactory;
use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessCreditCardConfigInterface;
use Oro\Bundle\WirecardBundle\Method\View\WirecardSeamlessCreditCardView;

class WirecardSeamlessCreditCardViewFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var FormFactoryInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $formFactory;

    /** @var WirecardSeamlessCreditCardViewFactory */
    protected $factory;

    protected function setUp()
    {
        $this->formFactory = $this->createMock(FormFactoryInterface::class);
        $this->factory = new WirecardSeamlessCreditCardViewFactory($this->formFactory);
    }

    public function testCreate()
    {
        $config = $this->createMock(WirecardSeamlessCreditCardConfigInterface::class);
        $expectedView = new WirecardSeamlessCreditCardView($this->formFactory, $config);
        $this->assertEquals($expectedView, $this->factory->create($config));
    }
}
