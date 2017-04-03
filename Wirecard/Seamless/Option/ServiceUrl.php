<?php

namespace Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option;

use Symfony\Component\OptionsResolver\OptionsResolver;

class ServiceUrl implements OptionInterface
{
    const SERVICEURL = 'serviceUrl';

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
                ->setRequired(self::SERVICEURL);
        }

        $resolver
            ->setDefined(self::SERVICEURL)
            ->addAllowedTypes(self::SERVICEURL, 'string');
    }
}
