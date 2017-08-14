<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Method\View;

use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessConfigInterface;
use Oro\Bundle\WirecardBundle\Method\View\WirecardSeamlessCreditCardView;
use Symfony\Component\Form\FormFactoryInterface;

class WirecardSeamlessCreditCardViewTest extends WirecardSeamlessViewTest
{
    /** {@inheritdoc} */
    protected function createView(FormFactoryInterface $formFactory, WirecardSeamlessConfigInterface $config)
    {
        return new WirecardSeamlessCreditCardView($formFactory, $config);
    }

    /** {@inheritdoc} */
    protected function getInitiateRoute()
    {
        return WirecardSeamlessCreditCardView::INITIATE_ROUTE;
    }

    public function testGetBlock()
    {
        $this->assertEquals('_payment_methods_wirecard_seamless_credit_card_widget', $this->methodView->getBlock());
    }
}
