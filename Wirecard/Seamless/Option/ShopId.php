<?php

namespace Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option;

use Symfony\Component\OptionsResolver\OptionsResolver;

class ShopId extends AbstractOption
{
    const SHOPID = 'shopId';

    /** {@inheritdoc} */
    public function configureOption(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired(self::SHOPID)
            ->addAllowedTypes(self::SHOPID, 'string');
    }
}
