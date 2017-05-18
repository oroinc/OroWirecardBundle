<?php

namespace Oro\Bundle\WirecardBundle\Wirecard\Seamless\Response;

interface ResponseInterface
{
    /**
     * @return bool
     */
    public function isSuccessful();

    /**
     * @return string|null
     */
    public function getPaymentState();

    /**
     * @return string|null
     */
    public function getFingerprint();

    /**
     * @return string|null
     */
    public function getFingerprintOrder();

    /**
     * @return string|null
     */
    public function getGatewayReferenceNumber();

    /**
     * @return string|null
     */
    public function getOrderNumber();

    /**
     * @return string|null
     */
    public function getRedirectUrl();

    /**
     * @return array
     */
    public function getData();
}
