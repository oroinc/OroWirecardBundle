<?php

namespace Oro\Bundle\WirecardBundle\Wirecard\Seamless\Request;

use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option;

class ReadDataStorageRequest extends AbstractRequest
{
    const IDENTIFIER = 'read_data_storage';

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
    public function getRequestIdentifier()
    {
        return self::IDENTIFIER;
    }
}
