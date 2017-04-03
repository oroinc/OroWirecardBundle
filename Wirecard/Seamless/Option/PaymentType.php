<?php

namespace Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option;

use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentType extends AbstractOption
{
    const PAYMENTTYPE = 'paymentType';

    const CCARD = 'CCARD';
    const SEPA_DD = 'SEPA-DD';
    const PAYPAL = 'PAYPAL';

    /**
     * @var array
     */
    public static $paymentTypes = [
        self::CCARD,
        self::SEPA_DD,
        self::PAYPAL,
    ];

    /** {@inheritdoc} */
    public function configureOption(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired(self::PAYMENTTYPE)
            ->addAllowedValues(self::PAYMENTTYPE, self::$paymentTypes);
    }
}
