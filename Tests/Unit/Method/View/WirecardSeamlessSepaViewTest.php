<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Method\View;

use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessConfigInterface;
use Oro\Bundle\WirecardBundle\Method\View\WirecardSeamlessSepaView;
use Symfony\Component\Form\FormFactoryInterface;

class WirecardSeamlessSepaViewTest extends WirecardSeamlessViewTest
{
    protected function createView(
        FormFactoryInterface $formFactory,
        WirecardSeamlessConfigInterface $config
    ) {
        return new WirecardSeamlessSepaView($formFactory, $config);
    }

    protected function getInitiateRoute()
    {
        return WirecardSeamlessSepaView::INITIATE_ROUTE;
    }
}
