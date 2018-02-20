<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Wirecard\Seamless\Request;

use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option as Option;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Request\AbstractRequest;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Request\ReadDataStorageRequest;

class ReadDataStorageRequestTest extends AbstractRequestTest
{

    /** {@inheritdoc} */
    public function createRequest():AbstractRequest
    {
        return new ReadDataStorageRequest();
    }

    /** {@inheritdoc} */
    public function getOptions():array
    {
        return [
            Option\StorageId::STORAGEID => 'test_storage_id',
        ];
    }

    public function testGetRequestIndentifier()
    {
        $this->assertEquals('read_data_storage', $this->request->getRequestIdentifier());
    }
}
