<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Wirecard\Seamless\Option;

use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;

class CancelUrlTest extends AbstractOptionTest
{
    /** {@inheritdoc} */
    protected function getOptions()
    {
        return [new Option\CancelUrl()];
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
                    'The required option "cancelUrl" is missing.',
                ],
            ],
            'invalid type given' => [
                ['cancelUrl' => 10],
                [],
                [
                    InvalidOptionsException::class,
                    'The option "cancelUrl" with value 10 is expected to be of type "string", but is of type "integer".'
                ],
            ],
            'valid string value' => [
                ['cancelUrl' => 'test string'],
                ['cancelUrl' => 'test string'],
            ],
        ];
    }
}
