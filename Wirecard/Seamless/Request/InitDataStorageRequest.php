<?php

namespace Oro\Bundle\WirecardBundle\Wirecard\Seamless\Request;

use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option;
use Hochstrasser\Wirecard\Request\Seamless\Frontend\InitDataStorageRequest as WirecardInitDataStorageRequest;

class InitDataStorageRequest extends AbstractRequest
{
    /**
     * {@inheritdoc}
     */
    protected function configureRequestOptions()
    {
        $this
            ->addOption(new Option\OrderIdent())
            ->addOption(new Option\ReturnUrl());

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function buildWirecardRequest(array $options = [])
    {
        $request = WirecardInitDataStorageRequest::withOrderIdentAndReturnUrl(
            $options[Option\OrderIdent::ORDERIDENT],
            $options[Option\ReturnUrl::RETURNURL]
        );
        $request->setContext($this->buildContext($options));

        return $request;
    }
}
