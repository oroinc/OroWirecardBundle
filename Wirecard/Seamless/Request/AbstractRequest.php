<?php

namespace Oro\Bundle\WirecardBundle\Wirecard\Seamless\Request;

use Hochstrasser\Wirecard\Context;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractRequest implements RequestInterface
{
    /**
     * @var OptionsResolver
     */
    protected $resolver;

    /**
     * @param OptionsResolver $resolver
     *
     * @return $this
     */
    protected function withResolver(OptionsResolver $resolver)
    {
        $this->resolver = $resolver;

        return $this;
    }

    /**
     * @return $this
     */
    private function configureRequiredOptions()
    {
        $this
            ->addOption(new Option\CustomerId())
            ->addOption(new Option\ShopId())
            ->addOption(new Option\Secret())
            ->addOption(new Option\Language())
            ->addOption(new Option\Hashing())
            ->addOption(new Option\TestMode());

        return $this;
    }

    /** {@inheritdoc} */
    public function configureOptions(OptionsResolver $resolver)
    {
        $this
            ->withResolver($resolver)
            ->configureRequiredOptions()
            ->configureRequestOptions()
            ->endResolver();
    }

    /**
     * @return $this
     */
    protected function configureRequestOptions()
    {
        return $this;
    }

    /**
     * @return $this
     */
    private function endResolver()
    {
        $this->resolver = null;

        return $this;
    }

    /**
     * @param Option\OptionInterface $option
     *
     * @return $this
     */
    protected function addOption(Option\OptionInterface $option)
    {
        if (!$this->resolver) {
            throw new \InvalidArgumentException('Call AbstractRequest->withResolver($resolver) first');
        }

        $option->configureOption($this->resolver);

        return $this;
    }

    /**
     * @param array $options
     *
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
