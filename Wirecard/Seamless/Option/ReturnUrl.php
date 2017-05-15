<?php

namespace Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @link https://guides.wirecard.at/wcs:datastorage_init
 */
class ReturnUrl implements OptionInterface
{
    const RETURNURL = 'returnUrl';

    /** {@inheritdoc} */
    public function configureOption(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired(self::RETURNURL)
            ->addAllowedTypes(self::RETURNURL, 'string');
    }
}
