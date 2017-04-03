<?php

namespace Oro\Bundle\WirecardBundle\Tests\Functional\Stub\Method\View;

use Oro\Bundle\PaymentBundle\Context\PaymentContextInterface;
use Oro\Bundle\PaymentBundle\Method\View\PaymentMethodViewInterface;
use Oro\Bundle\WirecardBundle\Tests\Functional\Stub\Method\WirecardSeamlessMethodStub;

class WirecardSeamlessMethodViewStub implements PaymentMethodViewInterface
{
    /**
     * {@inheritDoc}
     */
    public function getOptions(PaymentContextInterface $context)
    {
        return [];
    }

    /**
     * {@inheritDoc}
     */
    public function getBlock()
    {
        return '';
    }

    /**
     * {@inheritDoc}
     */
    public function getLabel()
    {
        return 'Test';
    }

    /**
     * {@inheritDoc}
     */
    public function getAdminLabel()
    {
        return 'Test';
    }

    /**
     * {@inheritDoc}
     */
    public function getShortLabel()
    {
        return 'Test';
    }

    /**
     * {@inheritDoc}
     */
    public function getPaymentMethodIdentifier()
    {
        return WirecardSeamlessMethodStub::TYPE;
    }
}
