<?php

namespace Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option;

use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderDescription implements OptionInterface
{
    const ORDERDESCRIPTION = 'orderDescription';

    /** {@inheritdoc} */
    public function configureOption(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired(self::ORDERDESCRIPTION)
            ->addAllowedTypes(self::ORDERDESCRIPTION, 'string');
    }
}
