<?php

namespace Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option;

use Symfony\Component\OptionsResolver\OptionsResolver;

class Currency implements OptionInterface
{
    const CURRENCY = 'currency';

    const EURO = 'EUR';
    const USD = 'USD';

    /**
     * @var array
     */
    public static $currencies = [
        self::EURO,
        self::USD,
    ];

    /** @var bool */
    protected $required;

    /**
     * @param bool $required
     */
    public function __construct($required = true)
    {
        $this->required = $required;
    }

    /** {@inheritdoc} */
    public function configureOption(OptionsResolver $resolver)
    {
        if ($this->required) {
            $resolver
                ->setRequired(self::CURRENCY);
        }

        $resolver
            ->setDefined(self::CURRENCY)
            ->addAllowedValues(self::CURRENCY, self::$currencies);
    }
}
