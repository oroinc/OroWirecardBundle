<?php

namespace Oro\Bundle\WirecardBundle\Method\View;

class WirecardSeamlessPaypalView extends WirecardSeamlessView
{
    /**
     * {@inheritdoc}
     */
    public function getBlock()
    {
        return '_payment_methods_wirecard_seamless_paypal_widget';
    }
}
