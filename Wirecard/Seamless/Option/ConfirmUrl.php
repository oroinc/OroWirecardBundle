<?php

namespace Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option;

use Symfony\Component\OptionsResolver\OptionsResolver;

class ConfirmUrl implements OptionInterface
{
    const CONFIRMURL = 'confirmUrl';

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
                ->setRequired(self::CONFIRMURL);
        }

        $resolver
            ->setDefined(self::CONFIRMURL)
            ->addAllowedTypes(self::CONFIRMURL, 'string');
    }
}
