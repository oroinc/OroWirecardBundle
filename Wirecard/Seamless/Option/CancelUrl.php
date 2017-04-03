<?php

namespace Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option;

use Symfony\Component\OptionsResolver\OptionsResolver;

class CancelUrl implements OptionInterface
{
    const CANCELURL = 'cancelUrl';

    /** @var bool */
    protected $required;

    /** @param bool $required */
    public function __construct($required = true)
    {
        $this->required = $required;
    }

    /** {@inheritdoc} */
    public function configureOption(OptionsResolver $resolver)
    {
        if ($this->required) {
            $resolver
                ->setRequired(self::CANCELURL);
        }

        $resolver
            ->setDefined(self::CANCELURL)
            ->addAllowedTypes(self::CANCELURL, 'string');
    }
}
