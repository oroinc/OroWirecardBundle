<?php

namespace Oro\Bundle\WirecardBundle\OptionsProvider;

use Oro\Bundle\AddressBundle\Entity\AbstractAddress;
use Oro\Bundle\PaymentBundle\Model\AddressOptionModel;
use Oro\Bundle\PaymentBundle\Provider\PaymentOrderShippingAddressOptionsProvider;

/**
 * Provides options that are required by wirecard payment method
 */
class OptionsProvider implements OptionsProviderInterface
{
    /**
     * @var PaymentOrderShippingAddressOptionsProvider
     */
    private $orderShippingAddressOptionsProvider;

    /**
     * @param PaymentOrderShippingAddressOptionsProvider $orderShippingAddressOptionsProvider
     */
    public function __construct(PaymentOrderShippingAddressOptionsProvider $orderShippingAddressOptionsProvider)
    {
        $this->orderShippingAddressOptionsProvider = $orderShippingAddressOptionsProvider;
    }

    /**
     * @param AbstractAddress $address
     * @return AddressOptionModel
     */
    public function getShippingAddressOptions(AbstractAddress $address): AddressOptionModel
    {
        return $this->orderShippingAddressOptionsProvider->getShippingAddressOptions($address);
    }
}
