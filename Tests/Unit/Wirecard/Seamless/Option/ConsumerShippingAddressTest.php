<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Wirecard\Seamless\Option;

use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;

class ConsumerShippingAddressTest extends AbstractOptionTest
{
    /** {@inheritdoc} */
    protected function getOptions()
    {
        return [new Option\ConsumerShippingAddress()];
    }

    /** {@inheritdoc} */
    public function configureOptionDataProvider()
    {
        return [
            'empty' => [
                [
                    'consumerShippingFirstName' => '',
                    'consumerShippingLastName' => '',
                    'consumerShippingAddress1' => '',
                    'consumerShippingAddress2' => '',
                    'consumerShippingCountry' => '',
                    'consumerShippingCity' => '',
                    'consumerShippingState' => '',
                    'consumerShippingZipCode' => ''
                ],
                [
                    'consumerShippingFirstName' => '',
                    'consumerShippingLastName' => '',
                    'consumerShippingAddress1' => '',
                    'consumerShippingAddress2' => '',
                    'consumerShippingCountry' => '',
                    'consumerShippingCity' => '',
                    'consumerShippingState' => '',
                    'consumerShippingZipCode' => ''
                ],
            ],
            'invalid type given' => [
                ['consumerShippingFirstName' => null],
                [],
                [
                    InvalidOptionsException::class,
                    'The option "consumerShippingFirstName" with value null is expected to be of type "string",'.
                    ' but is of type "NULL".'
                ],
            ],
            'valid string value' => [
                [
                    'consumerShippingFirstName' => 'abc',
                    'consumerShippingLastName' => 'abc',
                    'consumerShippingAddress1' => 'abc',
                    'consumerShippingAddress2' => 'abc',
                    'consumerShippingCountry' => 'abc',
                    'consumerShippingCity' => 'abc',
                    'consumerShippingState' => 'abc',
                    'consumerShippingZipCode' => 'abc'
                ],
                [
                    'consumerShippingFirstName' => 'abc',
                    'consumerShippingLastName' => 'abc',
                    'consumerShippingAddress1' => 'abc',
                    'consumerShippingAddress2' => 'abc',
                    'consumerShippingCountry' => 'abc',
                    'consumerShippingCity' => 'abc',
                    'consumerShippingState' => 'abc',
                    'consumerShippingZipCode' => 'abc'
                ],
            ],
        ];
    }
}
