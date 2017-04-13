<?php

namespace Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option;

use Symfony\Component\OptionsResolver\OptionsResolver;

class Language extends AbstractOption
{
    const LANGUAGE = 'language';

    const AR = 'ar';
    const BS = 'bs';
    const BG = 'bg';
    const ZH = 'zh';
    const HR = 'hr';
    const CS = 'cs';
    const DA = 'da';
    const NL = 'nl';
    const EN = 'en';
    const ET = 'et';
    const FI = 'fi';
    const FR = 'fr';
    const DE = 'de';
    const EL = 'el';
    const HE = 'he';
    const HI = 'hi';
    const HU = 'hu';
    const IT = 'it';
    const JA = 'ja';
    const KO = 'ko';
    const LV = 'lv';
    const LT = 'lt';
    const MK = 'mk';
    const NO = 'no';
    const PL = 'pl';
    const PT = 'pt';
    const RO = 'ro';
    const RU = 'ru';
    const SR = 'sr';
    const SK = 'sk';
    const SL = 'sl';
    const ES = 'es';
    const SV = 'sv';
    const TR = 'tr';
    const UK = 'uk';

    /**
     * @var array
     */
    public static $languages = [
        self::AR,
        self::BS,
        self::BG,
        self::ZH,
        self::HR,
        self::CS,
        self::DA,
        self::NL,
        self::EN,
        self::ET,
        self::FI,
        self::FR,
        self::DE,
        self::EL,
        self::HE,
        self::HI,
        self::HU,
        self::IT,
        self::JA,
        self::KO,
        self::LV,
        self::LT,
        self::MK,
        self::NO,
        self::PL,
        self::PT,
        self::RO,
        self::RU,
        self::SR,
        self::SK,
        self::SL,
        self::ES,
        self::SV,
        self::TR,
        self::UK,
    ];

    /** {@inheritdoc} */
    public function configureOption(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired(self::LANGUAGE)
            ->addAllowedValues(self::LANGUAGE, self::$languages);
    }
}
