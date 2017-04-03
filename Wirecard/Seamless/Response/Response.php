<?php

namespace Oro\Bundle\WirecardBundle\Wirecard\Seamless\Response;

use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option;

class Response
{
    const FINGERPRINT_FIELD = 'responseFingerprint';
    const FINGERPRINT_ORDER_FIELD = 'responseFingerprintOrder';
    const PAYMENT_STATE_FIELD = 'paymentState';
    const GATEWAY_REFERENCE_NUMBER_FIELD = 'gatewayReferenceNumber';
    const ORDER_NUMBER_FIELD = 'orderNumber';

    /**
     * @var \ArrayObject
     */
    private $data;

    public function __construct(array $data = [])
    {
        $this->data = new \ArrayObject($data);
    }

    /**
     * @return bool
     */
    public function isSuccessful()
    {
        return $this->getPaymentState() === 'SUCCESS';
    }

    /**
     * @return string
     */
    public function getPaymentState()
    {
        return $this->getOffset(self::PAYMENT_STATE_FIELD);
    }

    /**
     * @return string
     */
    public function getFingerprint()
    {
        return $this->getOffset(self::FINGERPRINT_FIELD);
    }

    /**
     * @return string
     */
    public function getFingerprintOrder()
    {
        return $this->getOffset(self::FINGERPRINT_ORDER_FIELD);
    }

    public function getGatewayReferenceNumber()
    {
        return $this->getOffset(self::GATEWAY_REFERENCE_NUMBER_FIELD);
    }

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

    public function checkFingerprint(array $options = [])
    {
        if (!$this->getFingerprint()) {
            return false;
        }

        $this->data->offsetSet(Option\Secret::SECRET, $options[Option\Secret::SECRET]);
        $fingerPrint = $this->calcFingerprint($options[Option\Secret::SECRET], $options[Option\Hashing::HASHING]);

        return $fingerPrint === $this->getFingerprint();
    }

    public function calcFingerprint($secret, $hashingMethod = Option\Hashing::DEFAULT_HASHING_METHOD)
    {
        if (!$this->getFingerprintOrder()) {
            return null;
        }

        $responseFingerprintOrder = explode(',', $this->getFingerprintOrder());
        $raw = '';
        foreach ($responseFingerprintOrder as $parameter) {
            if ($this->data->offsetExists($parameter)) {
                $raw .= $this->data->offsetGet($parameter);
            }
        }

        $fingerPrint = null;
        if ($hashingMethod === Option\Hashing::HMAC) {
            $fingerPrint = hash_hmac('sha512', $raw, $secret);
        } else {
            $fingerPrint = hash('sha512', $raw);
        }
        return $fingerPrint;
    }
}
