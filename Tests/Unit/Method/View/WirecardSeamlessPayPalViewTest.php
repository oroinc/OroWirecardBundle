<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Method\View;

use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessConfigInterface;
use Oro\Bundle\WirecardBundle\Method\View\WirecardSeamlessPayPalView;
use Symfony\Component\Form\FormFactoryInterface;

class WirecardSeamlessPayPalViewTest extends WirecardSeamlessViewTest
{
    protected function createView(
        FormFactoryInterface $formFactory,
        WirecardSeamlessConfigInterface $config
    ) {
        return new WirecardSeamlessPayPalView($formFactory, $config);
    }

    protected function getInitiateRoute()
    {
        return WirecardSeamlessPayPalView::INITIATE_ROUTE;
    }
}
