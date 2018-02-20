<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Wirecard\Seamless\Option;

use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;

class CurrencyTest extends AbstractOptionTest
{
    /** {@inheritdoc} */
    protected function getOptions()
    {
        return [new Option\Currency()];
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
                    'The required option "currency" is missing.',
                ],
            ],
            'invalid value given' => [
                ['currency' => 'dollar'],
                [],
                [
                    InvalidOptionsException::class,
                    'The option "currency" with value "dollar" is invalid. Accepted values are: "EUR", "USD".'
                ],
            ],
            'valid EUR value' => [
                ['currency' => 'EUR'],
                ['currency' => 'EUR'],
            ],
            'valid USD value' => [
                ['currency' => 'USD'],
                ['currency' => 'USD'],
            ],
        ];
    }
}
