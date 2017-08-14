<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Wirecard\Seamless\Option;

use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option;

use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;

class OrderIdentTest extends AbstractOptionTest
{
    /** {@inheritdoc} */
    protected function getOptions()
    {
        return [new Option\OrderIdent()];
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
                    'The required option "orderIdent" is missing.',
                ],
            ],
            'invalid type given' => [
                ['orderIdent' => 10],
                [],
                [
                    InvalidOptionsException::class,
                    'The option "orderIdent" with value 10 is expected to be of type "string",' .
                    ' but is of type "integer".'
                ],
            ],
            'valid string value' => [
                ['orderIdent' => 'test string'],
                ['orderIdent' => 'test string'],
            ],
        ];
    }
}
