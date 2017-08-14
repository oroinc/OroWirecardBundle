<?php

namespace Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option;

use Symfony\Component\OptionsResolver\OptionsResolver;

class Hashing implements OptionInterface
{
    const HASHING = 'hashingMethod';

    const SHA = 'sha512';
    const HMAC = 'hmac-sha512';
    const DEFAULT_HASHING_METHOD = self::HMAC;

    /**
     * @var array
     */
    public static $hashingAlgs = [
        self::SHA,
        self::HMAC,
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
                ->setRequired(self::HASHING);
        }

        $resolver
            ->setDefault(self::HASHING, self::HMAC)
            ->addAllowedValues(self::HASHING, self::$hashingAlgs);
    }
}
