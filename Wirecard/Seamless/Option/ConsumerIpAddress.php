<?php

namespace Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option;

use Symfony\Component\OptionsResolver\OptionsResolver;

class ConsumerIpAddress extends AbstractOption
{
    const CONSUMERIPADDRESS = 'consumerIpAddress';

    /** {@inheritdoc} */
    public function configureOption(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired(self::CONSUMERIPADDRESS)
            ->addAllowedTypes(self::CONSUMERIPADDRESS, 'string');
    }
}
