<?php

namespace Oro\Bundle\WirecardBundle\Tests\Functional\Stub\Method\View;

use Oro\Bundle\PaymentBundle\Method\View\AbstractPaymentMethodViewProvider;
use Oro\Bundle\WirecardBundle\Tests\Functional\Stub\Method\WirecardSeamlessMethodStub;

class WirecardSeamlessMethodViewProviderStub extends AbstractPaymentMethodViewProvider
{
    /**
     * {@inheritDoc}
     */
    protected function buildViews()
    {
        $this->addView(WirecardSeamlessMethodStub::TYPE, new WirecardSeamlessMethodViewStub());
    }
}
