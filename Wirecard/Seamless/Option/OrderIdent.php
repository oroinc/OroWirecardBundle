<?php

namespace Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option;

use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderIdent implements OptionInterface
{
    const ORDERIDENT = 'orderIdent';

    /** {@inheritdoc} */
    public function configureOption(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired(self::ORDERIDENT)
            ->addAllowedTypes(self::ORDERIDENT, 'string');
    }
}
