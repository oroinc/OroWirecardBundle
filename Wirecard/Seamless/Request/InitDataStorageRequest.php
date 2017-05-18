<?php

namespace Oro\Bundle\WirecardBundle\Wirecard\Seamless\Request;

use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option;

class InitDataStorageRequest extends AbstractRequest
{
    const IDENTIFIER = 'init_data_storage';

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
    public function getRequestIdentifier()
    {
        return self::IDENTIFIER;
    }
}
