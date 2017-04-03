<?php

namespace Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option;

use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerId extends AbstractOption
{
    const CUSTOMERID = 'customerId';

    /** {@inheritdoc} */
    public function configureOption(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired(self::CUSTOMERID)
            ->addAllowedTypes(self::CUSTOMERID, 'string');
    }
}
