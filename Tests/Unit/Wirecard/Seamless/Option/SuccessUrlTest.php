<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Wirecard\Seamless\Option;

use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;

class SuccessUrlTest extends AbstractOptionTest
{
    /** {@inheritdoc} */
    protected function getOptions()
    {
        return [new Option\SuccessUrl()];
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
                    'The required option "successUrl" is missing.',
                ],
            ],
            'invalid type given' => [
                ['successUrl' => 10],
                [],
                [
                    InvalidOptionsException::class,
                    'The option "successUrl" with value 10 is expected to be of type "string",' .
                    ' but is of type "integer".'
                ],
            ],
            'valid string value' => [
                ['successUrl' => 'test string'],
                ['successUrl' => 'test string'],
            ],
        ];
    }
}
