<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Method\View;

use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessConfigInterface;
use Oro\Bundle\WirecardBundle\Method\View\WirecardSeamlessPaypalView;
use Symfony\Component\Form\FormFactoryInterface;

class WirecardSeamlessPaypalViewTest extends WirecardSeamlessViewTest
{
    protected function createView(
        FormFactoryInterface $formFactory,
        WirecardSeamlessConfigInterface $config
    ) {
        return new WirecardSeamlessPaypalView($formFactory, $config);
    }

    protected function getInitiateRoute()
    {
        return WirecardSeamlessPaypalView::INITIATE_ROUTE;
    }
}
