<?php

namespace Oro\Bundle\WirecardBundle\Method;

use Oro\Bundle\PaymentBundle\Entity\PaymentTransaction;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Request\InitPaypalPaymentRequest;

class WirecardSeamlessPaypalPaymentMethod extends AbstractWirecardSeamlessPaymentMethod
{
    const TYPE = 'PAYPAL';

    /**
     * {@inheritdoc}
     */
    public function getWirecardPaymentType()
    {
        return static::TYPE;
    }

    /**
     * @param PaymentTransaction $paymentTransaction
     *
     * @return array
     */
    public function purchase(PaymentTransaction $paymentTransaction)
    {
        $options = array_merge(
            $this->getBaseOptions($paymentTransaction),
            $this->getShippingInfo($paymentTransaction)
        );

        $request = new InitPaypalPaymentRequest();

        $response = $this->doRequest($request, $options);

        $paymentTransaction
            ->setRequest($options)
            ->setResponse($response->getData());

        $redirectUrl = $response->getRedirectUrl();

        return $redirectUrl ? ['redirectTo' => $redirectUrl] : [];
    }
}
