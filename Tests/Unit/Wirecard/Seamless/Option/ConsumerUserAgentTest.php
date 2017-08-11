<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Wirecard\Seamless\Option;

use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option;

use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;

class ConsumerUserAgentTest extends AbstractOptionTest
{
    /** {@inheritdoc} */
    protected function getOptions()
    {
        return [new Option\ConsumerUserAgent()];
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
                    'The required option "consumerUserAgent" is missing.',
                ],
            ],
            'invalid type given' => [
                ['consumerUserAgent' => 10],
                [],
                [
                    InvalidOptionsException::class,
                    'The option "consumerUserAgent" with value 10 is expected to be of type "string",' .
                    ' but is of type "integer".'
                ],
            ],
            'valid string value' => [
                ['consumerUserAgent' => 'test string'],
                ['consumerUserAgent' => 'test string'],
            ],
        ];
    }
}
