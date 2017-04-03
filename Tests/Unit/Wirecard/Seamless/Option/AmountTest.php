<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Wirecard\Payflow\Option;

use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;

class AmountTest extends AbstractOptionTest
{
    /** {@inheritdoc} */
    protected function getOptions()
    {
        return [new Option\Amount()];
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
                    'The required option "amount" is missing.',
                ],
            ],
            'use integer value from options' => [
                ['amount' => 10],
                ['amount' => '10.00'],
            ],
            'use float value from options' => [
                ['amount' => 10.0],
                ['amount' => '10.00'],
            ],
            'use string value from options' => [
                ['amount' => '10'],
                ['amount' => '10.00'],
            ],
        ];
    }
}
