<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Wirecard\Seamless\Option;

use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;

class ReturnUrlTest extends AbstractOptionTest
{
    /** {@inheritdoc} */
    protected function getOptions()
    {
        return [new Option\ReturnUrl()];
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
                    'The required option "returnUrl" is missing.',
                ],
            ],
            'invalid type given' => [
                ['returnUrl' => 10],
                [],
                [
                    InvalidOptionsException::class,
                    'The option "returnUrl" with value 10 is expected to be of type "string", but is of type "integer".'
                ],
            ],
            'valid string value' => [
                ['returnUrl' => 'test string'],
                ['returnUrl' => 'test string'],
            ],
        ];
    }
}
