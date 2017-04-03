<?php

namespace Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option;

use Symfony\Component\OptionsResolver\OptionsResolver;

class FailureUrl implements OptionInterface
{
    const FAILUREURL = 'failureUrl';

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
                ->setRequired(self::FAILUREURL);
        }

        $resolver
            ->setDefined(self::FAILUREURL)
            ->addAllowedTypes(self::FAILUREURL, 'string');
    }
}
