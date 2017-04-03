<?php

namespace Oro\Bundle\WirecardBundle\Method\View;

use Oro\Bundle\WirecardBundle\Form\Type\CreditCardType;

class WirecardSeamlessCreditCardView extends WirecardSeamlessView
{
    /**
     * {@inheritdoc}
     */
    public function getBlock()
    {
        return '_payment_methods_wirecard_seamless_credit_card_widget';
    }

    /**
     * {@inheritdoc}
     */
    public function getFormTypeClass()
    {
        return CreditCardType::class;
    }
}
