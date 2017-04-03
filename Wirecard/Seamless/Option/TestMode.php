<?php

namespace Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option;

use Symfony\Component\OptionsResolver\OptionsResolver;

class TestMode extends AbstractOption
{
    const TESTMODE = 'testMode';

    /** {@inheritdoc} */
    public function configureOption(OptionsResolver $resolver)
    {
        $resolver
            ->setDefined(self::TESTMODE)
            ->setDefault(self::TESTMODE, false)
            ->addAllowedTypes(self::TESTMODE, 'boolean');
    }
}
