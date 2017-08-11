<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Wirecard\Seamless\Option;

use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option;

use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;

class LanguageTest extends AbstractOptionTest
{
    /** {@inheritdoc} */
    protected function getOptions()
    {
        return [new Option\Language()];
    }

    /** {@inheritdoc} */
    public function configureOptionDataProvider()
    {
        $testCaseData = [
            'empty' => [
                [],
                [],
                [
                    MissingOptionsException::class,
                    'The required option "language" is missing.',
                ],
            ],
            'not allowed language' => [
                ['language' => 'elvish'],
                [],
                [
                    InvalidOptionsException::class,
                    sprintf(
                        'The option "language" with value "elvish" is invalid. Accepted values are: "%s".',
                        implode('", "', Option\Language::$languages)
                    ),
                ],

            ],
        ];

        $allowedValuesData = [];
        foreach (Option\Language::$languages as $value) {
            $key = sprintf('allowed language %s', $value);
            $allowedValuesData[$key] = [['language' => $value], ['language' => $value]];
        }

        return array_merge($testCaseData, $allowedValuesData);
    }
}
