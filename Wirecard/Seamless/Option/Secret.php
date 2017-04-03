<?php

namespace Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option;

use Symfony\Component\OptionsResolver\OptionsResolver;

class Secret extends AbstractOption
{
    const SECRET = 'secret';

    /** {@inheritdoc} */
    public function configureOption(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired(self::SECRET)
            ->addAllowedTypes(self::SECRET, 'string');
    }
}
