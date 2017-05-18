<?php

namespace Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option;

use Symfony\Component\OptionsResolver\OptionsResolver;

class ConsumerShippingAddress extends AbstractOption
{
    const CONSUMERSHIPPINGFIRSTNAME = 'consumerShippingFirstName';
    const CONSUMERSHIPPINGLASTNAME = 'consumerShippingLastName';
    const CONSUMERSHIPPINGADDRESS1 = 'consumerShippingAddress1';
    const CONSUMERSHIPPINGADDRESS2 = 'consumerShippingAddress2';
    const CONSUMERSHIPPINGCOUNTRY = 'consumerShippingCountry';
    const CONSUMERSHIPPINGCITY = 'consumerShippingCity';
    const CONSUMERSHIPPINGSTATE = 'consumerShippingState';
    const CONSUMERSHIPPINGZIPCODE = 'consumerShippingZipCode';

    const ALL_KEYS = [
        self::CONSUMERSHIPPINGFIRSTNAME,
        self::CONSUMERSHIPPINGLASTNAME,
        self::CONSUMERSHIPPINGADDRESS1,
        self::CONSUMERSHIPPINGADDRESS2,
        self::CONSUMERSHIPPINGCOUNTRY,
        self::CONSUMERSHIPPINGCITY,
        self::CONSUMERSHIPPINGSTATE,
        self::CONSUMERSHIPPINGZIPCODE,
    ];

    /** {@inheritdoc} */
    public function configureOption(OptionsResolver $resolver)
    {
        $resolver->setDefined(self::ALL_KEYS);

        foreach (self::ALL_KEYS as $key) {
            $resolver->setAllowedTypes($key, ['string']);
        }
    }
}
