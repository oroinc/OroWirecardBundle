<?php

namespace Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option;

use Symfony\Component\OptionsResolver\OptionsResolver;

class SuccessUrl implements OptionInterface
{
    const SUCCESSURL = 'successUrl';

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
                ->setRequired(self::SUCCESSURL);
        }

        $resolver
            ->setDefined(self::SUCCESSURL)
            ->addAllowedTypes(self::SUCCESSURL, 'string');
    }
}
