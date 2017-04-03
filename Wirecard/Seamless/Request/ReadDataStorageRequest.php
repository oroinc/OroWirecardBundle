<?php

namespace Oro\Bundle\WirecardBundle\Wirecard\Seamless\Request\Factory;

use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Request\AbstractRequest;
use Hochstrasser\Wirecard\Request\Seamless\Frontend\ReadDataStorageRequest as WirecardReadDataStorageRequest;

class ReadDataStorageRequest extends AbstractRequest
{
    /**
     * {@inheritdoc}
     */
    protected function configureRequestOptions()
    {
        $this
            ->addOption(new Option\StorageId());

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function buildWirecardRequest(array $options = [])
    {
        $request = WirecardReadDataStorageRequest::withStorageId(
            $options[Option\StorageId::STORAGEID]
        );
        $request->setContext($this->buildContext($options));

        return $request;
    }
}
