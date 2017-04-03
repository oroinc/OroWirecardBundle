<?php

namespace Oro\Bundle\WirecardBundle\Method\View;

use Oro\Bundle\WirecardBundle\Form\Type\SepaDirectDebitType;

class WirecardSeamlessSepaView extends WirecardSeamlessView
{
    /**
     * {@inheritdoc}
     */
    public function getBlock()
    {
        return '_payment_methods_wirecard_seamless_sepa_direct_debit_widget';
    }

    /**
     * {@inheritdoc}
     */
    public function getFormTypeClass()
    {
        return SepaDirectDebitType::class;
    }
}
