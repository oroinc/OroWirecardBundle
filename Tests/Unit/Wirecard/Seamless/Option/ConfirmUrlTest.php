<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Wirecard\Seamless\Option;

use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;

class ConfirmUrlTest extends AbstractOptionTest
{
    /** {@inheritdoc} */
    protected function getOptions()
    {
        return [new Option\ConfirmUrl()];
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
                    'The required option "confirmUrl" is missing.',
                ],
            ],
            'invalid type given' => [
                ['confirmUrl' => 10],
                [],
                [
                    InvalidOptionsException::class,
                    'The option "confirmUrl" with value 10 is expected to be of type "string",' .
                    ' but is of type "integer".'
                ],
            ],
            'valid string value' => [
                ['confirmUrl' => 'test string'],
                ['confirmUrl' => 'test string'],
            ],
        ];
    }
}
