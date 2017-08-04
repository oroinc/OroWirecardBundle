<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Method\View;

use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessConfigInterface;
use Oro\Bundle\WirecardBundle\Method\View\WirecardSeamlessSepaView;
use Symfony\Component\Form\FormFactoryInterface;

class WirecardSeamlessSepaViewTest extends WirecardSeamlessViewTest
{
    /** {@inheritdoc} */
    protected function createView(FormFactoryInterface $formFactory, WirecardSeamlessConfigInterface $config)
    {
        return new WirecardSeamlessSepaView($formFactory, $config);
    }

    /** {@inheritdoc} */
    protected function getInitiateRoute()
    {
        return WirecardSeamlessSepaView::INITIATE_ROUTE;
    }

    public function testGetBlock()
    {
        $this->assertEquals(
            '_payment_methods_wirecard_seamless_sepa_direct_debit_widget',
            $this->methodView->getBlock()
        );
    }
}
