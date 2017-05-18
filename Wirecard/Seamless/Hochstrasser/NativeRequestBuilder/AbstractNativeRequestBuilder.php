<?php

namespace Oro\Bundle\WirecardBundle\Wirecard\Seamless\Hochstrasser\NativeRequestBuilder;

use Hochstrasser\Wirecard\Context;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option;

abstract class AbstractNativeRequestBuilder implements NativeRequestBuilderInterface
{
    /**
     * @param array $options
     * @return Context
     */
    protected function buildContext(array $options)
    {
        $keys = [
            Option\CustomerId::CUSTOMERID,
            Option\ShopId::SHOPID,
            Option\Secret::SECRET,
            Option\Language::LANGUAGE,
            Option\Hashing::HASHING,
        ];

        return new Context(array_intersect_key($options, array_flip($keys)));
    }
}
