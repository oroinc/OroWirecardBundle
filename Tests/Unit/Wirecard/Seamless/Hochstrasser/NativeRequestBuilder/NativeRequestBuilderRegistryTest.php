<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Wirecard\Seamless\Hochstrasser\NativeRequestBuilder;

use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Hochstrasser\NativeRequestBuilder\NativeRequestBuilderInterface;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Hochstrasser\NativeRequestBuilder\NativeRequestBuilderRegistry;

class NativeRequestBuilderRegistryTest extends \PHPUnit\Framework\TestCase
{
    /** @var NativeRequestBuilderRegistry */
    protected $nativeRequestBuilderRegistry;

    /** @var  NativeRequestBuilderInterface|\PHPUnit\Framework\MockObject\MockObject */
    protected $nativeRequestBuilder;

    public function setUp()
    {
        $this->nativeRequestBuilderRegistry = new NativeRequestBuilderRegistry();
        $this->nativeRequestBuilder = $this->createMock(NativeRequestBuilderInterface::class);
    }

    public function testAddAndGetNativeRequestBuilder()
    {
        $this->nativeRequestBuilder->expects($this->once())->method('getRequestIdentifier')
            ->willReturn('1');
        $this->nativeRequestBuilderRegistry->addNativeRequestBuilder($this->nativeRequestBuilder);

        self::assertSame(
            $this->nativeRequestBuilder,
            $this->nativeRequestBuilderRegistry->getNativeRequestBuilder('1')
        );
    }

    /** @expectedException \InvalidArgumentException */
    public function testGetNativeRequestBuilderWithWrongIdentifier()
    {
        $this->nativeRequestBuilder->expects($this->once())->method('getRequestIdentifier')
            ->willReturn('test1');
        $this->nativeRequestBuilderRegistry->addNativeRequestBuilder($this->nativeRequestBuilder);
        $this->nativeRequestBuilderRegistry->getNativeRequestBuilder('test2');
    }
}
