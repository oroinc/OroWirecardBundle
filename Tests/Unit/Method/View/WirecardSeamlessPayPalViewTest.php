<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Method\View;

use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessConfigInterface;
use Oro\Bundle\WirecardBundle\Method\View\WirecardSeamlessPayPalView;
use Symfony\Component\Form\FormFactoryInterface;

class WirecardSeamlessPayPalViewTest extends WirecardSeamlessViewTest
{
    /** {@inheritdoc} */
    protected function createView(FormFactoryInterface $formFactory, WirecardSeamlessConfigInterface $config)
    {
        return new WirecardSeamlessPayPalView($formFactory, $config);
    }

    /** {@inheritdoc} */
    protected function getInitiateRoute()
    {
        return WirecardSeamlessPayPalView::INITIATE_ROUTE;
    }

    public function testGetBlock()
    {
        $this->assertEquals('_payment_methods_wirecard_seamless_paypal_widget', $this->methodView->getBlock());
    }
}
