<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Method\View;

use Oro\Bundle\PaymentBundle\Context\PaymentContextInterface;
use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessConfigInterface;
use Oro\Bundle\WirecardBundle\Method\View\WirecardSeamlessView;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

abstract class WirecardSeamlessViewTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var FormFactoryInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $formFactory;

    /**
     * @var WirecardSeamlessView
     */
    protected $methodView;

    /**
     * @var WirecardSeamlessConfigInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $paymentConfig;

    /**
     * @param FormFactoryInterface $formFactory
     * @param WirecardSeamlessConfigInterface $config
     * @return WirecardSeamlessView
     */
    abstract protected function createView(FormFactoryInterface $formFactory, WirecardSeamlessConfigInterface $config);

    /**
     * @return string
     */
    abstract protected function getInitiateRoute();

    protected function setUp()
    {
        $this->formFactory = $this->getMockBuilder(FormFactoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->paymentConfig = $this->createMock(WirecardSeamlessConfigInterface::class);

        $this->methodView = $this->createView($this->formFactory, $this->paymentConfig);
    }

    public function testGetPaymentMethodIdentifier()
    {
        $this->paymentConfig->expects($this->once())
            ->method('getPaymentMethodIdentifier')
            ->willReturn('identifier');

        $this->assertEquals('identifier', $this->methodView->getPaymentMethodIdentifier());
    }

    public function testGetLabel()
    {
        $this->paymentConfig->expects($this->once())
            ->method('getLabel')
            ->willReturn('label');

        $this->assertEquals('label', $this->methodView->getLabel());
    }

    public function testGetShortLabel()
    {
        $this->paymentConfig->expects($this->once())
            ->method('getShortLabel')
            ->willReturn('short label');

        $this->assertEquals('short label', $this->methodView->getShortLabel());
    }

    public function testGetAdminLabel()
    {
        $this->paymentConfig->expects($this->once())
            ->method('getAdminLabel')
            ->willReturn('admin label');

        $this->assertEquals('admin label', $this->methodView->getAdminLabel());
    }

    public function testGetOptions()
    {
        $formView = null;
        if ($this->methodView->getFormTypeClass()) {
            $formView = $this->createMock(FormView::class);
            $form = $this->createMock(FormInterface::class);

            $form->expects($this->once())->method('createView')->willReturn($formView);

            $this->formFactory->expects($this->once())
                ->method('create')
                ->with($this->methodView->getFormTypeClass())
                ->willReturn($form);
        }

        $this->paymentConfig->expects($this->once())
            ->method('getPaymentMethodIdentifier')
            ->willReturn('identifier');

        /** @var PaymentContextInterface|\PHPUnit\Framework\MockObject\MockObject $context */
        $context = $this->createMock(PaymentContextInterface::class);
        $context
            ->expects($this->once())
            ->method('getSourceEntityIdentifier')
            ->willReturn('sourceEntityId');

        $this->assertEquals(
            [
                'formView' => $formView,
                'paymentMethod' => 'identifier',
                'sourceEntityId' => 'sourceEntityId',
                'initiatePaymentMethodRoute' => $this->getInitiateRoute(),
            ],
            $this->methodView->getOptions($context)
        );
    }
}
