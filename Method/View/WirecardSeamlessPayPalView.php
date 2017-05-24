<?php

namespace Oro\Bundle\WirecardBundle\Method\View;

class WirecardSeamlessPayPalView extends WirecardSeamlessView
{
    /**
     * {@inheritdoc}
     */
    public function getFormTypeClass()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlock()
    {
        return '_payment_methods_wirecard_seamless_paypal_widget';
    }
}
