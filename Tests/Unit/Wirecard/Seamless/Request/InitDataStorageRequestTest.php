<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Wirecard\Seamless\Request;

use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Request\AbstractRequest;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Request\InitDataStorageRequest;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option as Option;

class InitDataStorageRequestTest extends AbstractRequestTest
{

    /** {@inheritdoc} */
    public function createRequest():AbstractRequest
    {
        return new InitDataStorageRequest();
    }

    /** {@inheritdoc} */
    public function getOptions():array
    {
        return [
            Option\OrderIdent::ORDERIDENT => 'test_order_ident',
            Option\ReturnUrl::RETURNURL => 'http://test.local',
        ];
    }

    public function testGetRequestIndentifier()
    {
        $this->assertEquals('init_data_storage', $this->request->getRequestIdentifier());
    }
}
