<?php

namespace Oro\Bundle\WirecardBundle\OptionsProvider;

use Oro\Bundle\AddressBundle\Entity\AbstractAddress;
use Oro\Bundle\PaymentBundle\Model\AddressOptionModel;

/**
 * Provides interface for payment options provider.
 */
interface OptionsProviderInterface
{
    /**
     * Gets shipping address options.
     *
     * @param AbstractAddress $address
     * @return AddressOptionModel
     */
    public function getShippingAddressOptions(AbstractAddress $address): AddressOptionModel;
}
