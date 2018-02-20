<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Wirecard\Seamless\Option;

use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;

class PaymentTypeTest extends AbstractOptionTest
{
    /** {@inheritdoc} */
    protected function getOptions()
    {
        return [new Option\PaymentType()];
    }

    /** {@inheritdoc} */
    public function configureOptionDataProvider()
    {
        return [
            'empty' => [
                [],
                [],
                [
                    MissingOptionsException::class,
                    'The required option "paymentType" is missing.',
                ],
            ],
            'invalid value given' => [
                ['paymentType' => 'abcdef'],
                [],
                [
                    InvalidOptionsException::class,
                    'The option "paymentType" with value "abcdef" is invalid. Accepted values are: "CCARD",'.
                    ' "SEPA-DD", "PAYPAL".'
                ],
            ],
            'valid value' => [
                ['paymentType' => 'CCARD'],
                ['paymentType' => 'CCARD'],
            ],
            'valid USD value_1' => [
                ['paymentType' => 'SEPA-DD'],
                ['paymentType' => 'SEPA-DD'],
            ],
            'valid USD value_2' => [
                ['paymentType' => 'PAYPAL'],
                ['paymentType' => 'PAYPAL'],
            ],
        ];
    }
}
