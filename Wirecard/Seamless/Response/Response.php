<?php

namespace Oro\Bundle\WirecardBundle\Wirecard\Seamless\Response;

class Response implements ResponseInterface
{
    const FINGERPRINT_FIELD = 'responseFingerprint';
    const FINGERPRINT_ORDER_FIELD = 'responseFingerprintOrder';
    const PAYMENT_STATE_FIELD = 'paymentState';
    const GATEWAY_REFERENCE_NUMBER_FIELD = 'gatewayReferenceNumber';
    const ORDER_NUMBER_FIELD = 'orderNumber';
    const REDIRECT_URL_FIELD = 'redirectUrl';

    /**
     * @var \ArrayObject
     */
    private $data;

    /**
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->data = new \ArrayObject($data);
    }

    /**
     * {@inheritdoc}
     */
    public function isSuccessful()
    {
        return $this->getPaymentState() === 'SUCCESS';
    }

    /**
     * {@inheritdoc}
     */
    public function getPaymentState()
    {
        return $this->getOffset(self::PAYMENT_STATE_FIELD);
    }

    /**
     * {@inheritdoc}
     */
    public function getFingerprint()
    {
        return $this->getOffset(self::FINGERPRINT_FIELD);
    }

    /**
     * {@inheritdoc}
     */
    public function getFingerprintOrder()
    {
        return $this->getOffset(self::FINGERPRINT_ORDER_FIELD);
    }

    /**
     * {@inheritdoc}
     */
    public function getGatewayReferenceNumber()
    {
        return $this->getOffset(self::GATEWAY_REFERENCE_NUMBER_FIELD);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrderNumber()
    {
        return $this->getOffset(self::ORDER_NUMBER_FIELD);
    }

    /**
     * @param mixed $index
     * @param mixed $default
     *
     * @return mixed|null
     */
    public function getOffset($index, $default = null)
    {
        return $this->data->offsetExists($index) ? $this->data->offsetGet($index) : $default;
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return $this->data->getArrayCopy();
    }

    /**
     * {@inheritdoc}
     */
    public function getRedirectUrl()
    {
        return $this->getOffset(self::REDIRECT_URL_FIELD);
    }
}
