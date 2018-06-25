<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Wirecard\Seamless\Request;

use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option as Option;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Request\AbstractRequest;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractRequestTest extends \PHPUnit\Framework\TestCase
{
    const DEFAULT_REQUEST_OPTIONS = [
        Option\CustomerId::CUSTOMERID => 'test_customer_id',
        Option\ShopId::SHOPID => 'test_shop_id',
        Option\Secret::SECRET => 'test_secret',
        Option\Language::LANGUAGE => Option\Language::EN,
        Option\Hashing::HASHING => Option\Hashing::DEFAULT_HASHING_METHOD,
    ];

    /** @var OptionsResolver */
    protected $resolver;

    /** @var AbstractRequest */
    protected $request;

    /** @return AbstractRequest */
    abstract public function createRequest():AbstractRequest;

    /** @return array */
    abstract public function getOptions():array;

    public function setUp()
    {
        $this->resolver = new OptionsResolver();
        $this->request = $this->createRequest();
    }

    public function testConfigureOptions()
    {
        $this->request->configureOptions($this->resolver);
        $options = array_merge(static::DEFAULT_REQUEST_OPTIONS, $this->getOptions());

        self::assertEquals($options, $this->resolver->resolve($options));
    }
}
