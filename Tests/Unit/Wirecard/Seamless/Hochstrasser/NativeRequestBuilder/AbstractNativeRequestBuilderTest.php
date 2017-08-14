<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Wirecard\Seamless\Hochstrasser\NativeRequestBuilder;

use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Hochstrasser\NativeRequestBuilder\NativeRequestBuilderInterface;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option;

use Hochstrasser\Wirecard\Context;

abstract class AbstractNativeRequestBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var  NativeRequestBuilderInterface
     */
    protected $nativeRequestBuilder;

    /**
     * @var array
     */
    protected $options = [
        Option\CustomerId::CUSTOMERID => 1,
        Option\ShopId::SHOPID => 2,
        Option\Secret::SECRET => 'secret',
        Option\Language::LANGUAGE => 'EN',
        Option\Hashing::HASHING => 'hmac-sha512',
    ];

    /**
     * @return array
     */
    abstract public function expectedClassDataProvider(): array;

    /**
     * @return array
     */
    abstract public function expectedRequestIdentifierDataProvider(): array;

    /**
     * @dataProvider getContextDataProvider
     */
    public function testCreateNativeRequest(Context $expectedContext)
    {
        $wirecardRequest = $this->nativeRequestBuilder->createNativeRequest($this->options);

        self::assertEquals($expectedContext, $wirecardRequest->getContext());
    }

    /**
     * @dataProvider expectedClassDataProvider
     * @param string $expectedClass
     */
    public function testCreateNativeRequestAssertValidReturningType(string $expectedClass)
    {
        self::assertInstanceOf(
            $expectedClass,
            $this->nativeRequestBuilder->createNativeRequest($this->options)
        );
    }

    /**
     * @dataProvider expectedRequestIdentifierDataProvider
     * @param string $expectedValue
     */
    public function testGetRequestIdentifier(string $expectedValue)
    {
        self::assertSame($expectedValue, $this->nativeRequestBuilder->getRequestIdentifier());
    }

    /**
     * @return array
     */
    public function getContextDataProvider(): array
    {
        $keys = [
            Option\CustomerId::CUSTOMERID,
            Option\ShopId::SHOPID,
            Option\Secret::SECRET,
            Option\Language::LANGUAGE,
            Option\Hashing::HASHING,
        ];

        return [[new Context(array_intersect_key($this->options, array_flip($keys)))]];
    }
}
