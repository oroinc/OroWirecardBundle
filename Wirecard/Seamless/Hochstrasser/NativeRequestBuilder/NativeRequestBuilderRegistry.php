<?php

namespace Oro\Bundle\WirecardBundle\Wirecard\Seamless\Hochstrasser\NativeRequestBuilder;

class NativeRequestBuilderRegistry
{
    /**
     * @var NativeRequestBuilderInterface[]
     */
    protected $nativeRequestBuilders = [];

    /**
     * @param NativeRequestBuilderInterface $nativeRequestBuilder
     */
    public function addNativeRequestBuilder(NativeRequestBuilderInterface $nativeRequestBuilder)
    {
        $this->nativeRequestBuilders[$nativeRequestBuilder->getRequestIdentifier()] = $nativeRequestBuilder;
    }

    /**
     * @param string $identifier
     * @return NativeRequestBuilderInterface
     */
    public function getNativeRequestBuilder($identifier)
    {
        if (!isset($this->nativeRequestBuilders[$identifier])) {
            throw new \InvalidArgumentException(
                sprintf('Can not find native request builder with identifier "%s"', $identifier)
            );
        }

        return $this->nativeRequestBuilders[$identifier];
    }
}
