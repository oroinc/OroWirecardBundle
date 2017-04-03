<?php

namespace Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option;

use Symfony\Component\OptionsResolver\OptionsResolver;

class ReturnUrl implements OptionInterface
{
    const RETURNURL = 'returnUrl';

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
                ->setRequired(self::RETURNURL);
        }

        $resolver
            ->setDefined(self::RETURNURL)
            ->addAllowedTypes(self::RETURNURL, 'string');
    }
}
