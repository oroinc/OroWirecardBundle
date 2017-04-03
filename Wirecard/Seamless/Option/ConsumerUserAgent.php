<?php

namespace Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option;

use Symfony\Component\OptionsResolver\OptionsResolver;

class ConsumerUserAgent extends AbstractOption
{
    const CONSUMERUSERAGENT = 'consumerUserAgent';

    /** {@inheritdoc} */
    public function configureOption(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired(self::CONSUMERUSERAGENT)
            ->addAllowedTypes(self::CONSUMERUSERAGENT, 'string');
    }
}
