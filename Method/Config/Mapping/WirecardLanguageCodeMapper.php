<?php

namespace Oro\Bundle\WirecardBundle\Method\Config\Mapping;

use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option\Language;

class WirecardLanguageCodeMapper
{
    public static $mappings = [
        'en' => Language::EN,
        'de_DE' => Language::DE,
        'fr_FR' =>  Language::FR,
        'en_US' => Language::EN,
        'en_CA' => Language::EN,
        'en_GB' => Language::EN,
        'en_AU' => Language::EN,
        'es_AR' => Language::ES,
        'fr_CA' => Language::FR,
    ];

    public static function mapLanguageCodeToWirecardLanguageCode(
        $languageCode,
        $defaultLanguageCode = Language::EN
    ) {
        return $languageCode && isset(self::$mappings[$languageCode])?
            self::$mappings[$languageCode]:
            $defaultLanguageCode;
    }
}
