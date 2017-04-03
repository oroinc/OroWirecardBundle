<?php

namespace Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option;

use Symfony\Component\OptionsResolver\OptionsResolver;

class Language extends AbstractOption
{
    const LANGUAGE = 'language';

    const EN = 'en';
    const DE = 'de';

    /**
     * @var array
     */
    public static $languages = [
        self::EN,
        self::DE,
    ];

    /** {@inheritdoc} */
    public function configureOption(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired(self::LANGUAGE)
            ->addAllowedValues(self::LANGUAGE, self::$languages);
    }
}
