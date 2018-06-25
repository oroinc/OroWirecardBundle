<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Method\View\Factory;

use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessCreditCardConfigInterface;
use Oro\Bundle\WirecardBundle\Method\View\Factory\WirecardSeamlessCreditCardViewFactory;
use Oro\Bundle\WirecardBundle\Method\View\WirecardSeamlessCreditCardView;
use Symfony\Component\Form\FormFactoryInterface;

class WirecardSeamlessCreditCardViewFactoryTest extends \PHPUnit\Framework\TestCase
{
    /** @var FormFactoryInterface|\PHPUnit\Framework\MockObject\MockObject */
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
