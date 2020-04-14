<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Wirecard\Seamless\Option;

use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractOptionTest extends \PHPUnit\Framework\TestCase
{
    /** @var Option\AbstractOption[] */
    protected $options;

    /** @return Option\AbstractOption[] */
    abstract protected function getOptions();

    protected function setUp(): void
    {
        $this->options = $this->getOptions();
    }

    protected function tearDown(): void
    {
        unset($this->options);
    }

    /**
     * @param array $options
     * @param array $expectedResult
     * @param array $exceptionAndMessage
     * @dataProvider configureOptionDataProvider
     */
    public function testConfigureOption(
        array $options = [],
        array $expectedResult = [],
        array $exceptionAndMessage = []
    ) {
        if ($exceptionAndMessage) {
            list($exception, $message) = $exceptionAndMessage;
            $this->expectException($exception);
            $this->expectExceptionMessage($message);
        }

        $resolver = new OptionsResolver();
        foreach ($this->options as $option) {
            $option->configureOption($resolver);
        }
        $resolvedOptions = $resolver->resolve($options);

        if ($expectedResult) {
            // Sort array to avoid different order in strict comparison
            sort($expectedResult);
            sort($resolvedOptions);
            $this->assertSame($expectedResult, $resolvedOptions);
        }
    }

    /**
     * @return array
     */
    abstract public function configureOptionDataProvider();
}
