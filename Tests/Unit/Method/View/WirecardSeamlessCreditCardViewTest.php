<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Method\View;

use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessConfigInterface;
use Oro\Bundle\WirecardBundle\Method\View\WirecardSeamlessCreditCardView;
use Symfony\Component\Form\FormFactoryInterface;

class WirecardSeamlessCreditCardViewTest extends WirecardSeamlessViewTest
{
    protected function createView(
        FormFactoryInterface $formFactory,
        WirecardSeamlessConfigInterface $config
    ) {
        return new WirecardSeamlessCreditCardView($formFactory, $config);
    }

    protected function getInitiateRoute()
    {
        return WirecardSeamlessCreditCardView::INITIATE_ROUTE;
    }
}
