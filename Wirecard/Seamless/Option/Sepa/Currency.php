<?php

namespace Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option\Sepa;

use Symfony\Component\OptionsResolver\OptionsResolver;

use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option\OptionInterface;

class Currency implements OptionInterface
{
    const CURRENCY = 'currency';

    const EURO = 'EUR';

    /**
     * @var array
     */
    public static $currencies = [
        self::EURO,
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
