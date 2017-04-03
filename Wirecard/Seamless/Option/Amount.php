<?php

namespace Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option;

use Symfony\Component\OptionsResolver\OptionsResolver;

class Amount implements OptionInterface
{
    const AMOUNT = 'amount';

    /** {@inheritdoc} */
    public function configureOption(OptionsResolver $resolver)
    {
        $allowedTypes = ['string', 'integer', 'float'];

        $resolver
            ->setRequired(self::AMOUNT)
            ->addAllowedTypes(self::AMOUNT, $allowedTypes)
            ->setNormalizer(
                self::AMOUNT,
                function (OptionsResolver $resolver, $amount) {
                    $floatValueNormalizer = self::getFloatValueNormalizer();

                    return $floatValueNormalizer($resolver, $amount);
                }
            );
    }

    /**
     * Round.
     *
     * @return \Closure
     */
    public static function getFloatValueNormalizer()
    {
        return function (OptionsResolver $resolver, $value) {
            return sprintf('%.2f', $value);
        };
    }
}
