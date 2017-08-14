<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Wirecard\Seamless\Option;

use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option;

use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;

class HashingTest extends AbstractOptionTest
{
    /** {@inheritdoc} */
    protected function getOptions()
    {
        return [new Option\Hashing()];
    }

    /** {@inheritdoc} */
    public function configureOptionDataProvider()
    {
        return [
            'empty' => [
                [],
                [],
            ],
            'invalid type given' => [
                ['hashingMethod' => 10],
                [],
                [
                    InvalidOptionsException::class,
                    'The option "hashingMethod" with value 10 is invalid. Accepted values are: "sha512", "hmac-sha512".'
                ],
            ],
            'valid string value' => [
                ['hashingMethod' => 'hmac-sha512'],
                ['hashingMethod' => 'hmac-sha512'],
            ],
            'valid string value_1' => [
                ['hashingMethod' => 'sha512'],
                ['hashingMethod' => 'sha512'],
            ],
        ];
    }
}
