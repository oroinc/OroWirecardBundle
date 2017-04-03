<?php

namespace Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option;

use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractOption implements OptionInterface
{
    /** {@inheritdoc} */
    public function configureOption(OptionsResolver $resolver)
    {
    }
}
