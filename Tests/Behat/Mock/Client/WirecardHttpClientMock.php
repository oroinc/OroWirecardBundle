<?php

namespace Oro\Bundle\WirecardBundle\Tests\Behat\Mock\Client;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;

class WirecardHttpClientMock implements ClientInterface
{
    const WIRECARD_OUTER_REDIRECT_MOCK_LINK = '/wirecard-outer-redirect-mock';

    const RESPONSE_BODY = [
        'redirectUrl' => self::WIRECARD_OUTER_REDIRECT_MOCK_LINK,
        'paymentState' => 'SUCCESS',
        'storageId' => 'e48fc6fa8e33c97cbc32d7ec91060132',
        'javascriptUrl' => '/bundles/orowirecard/js/stubs/wirecard-data-storage-mock.js'
    ];

    /** {@inheritdoc} */
    public function send(RequestInterface $request, array $options = [])
    {
        return new Response(200, [], http_build_query(self::RESPONSE_BODY));
    }

    /** {@inheritdoc} */
    public function sendAsync(RequestInterface $request, array $options = [])
    {
        $this->throwNotApplicableException();
    }

    /** {@inheritdoc} */
    public function request($method, $uri, array $options = [])
    {
        $this->throwNotApplicableException();
    }

    /** {@inheritdoc} */
    public function requestAsync($method, $uri, array $options = [])
    {
        $this->throwNotApplicableException();
    }

    /** {@inheritdoc} */
    public function getConfig($option = null)
    {
        $this->throwNotApplicableException();
    }

    /**
     * @throws \LogicException
     */
    protected function throwNotApplicableException()
    {
        throw new \LogicException('Method ' . __METHOD__ . 'is not applicable in' . __CLASS__);
    }
}
