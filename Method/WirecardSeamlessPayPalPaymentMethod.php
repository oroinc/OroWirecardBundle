<?php

namespace Oro\Bundle\WirecardBundle\Method;

use Oro\Bundle\PaymentBundle\Entity\PaymentTransaction;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Request\InitPayPalPaymentRequest;

class WirecardSeamlessPayPalPaymentMethod extends AbstractWirecardSeamlessPaymentMethod
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
     * {@inheritdoc}
     */
    public function purchase(PaymentTransaction $paymentTransaction)
    {
        $options = array_merge(
            $this->getBaseOptions($paymentTransaction),
            $this->getShippingInfo($paymentTransaction)
        );

        $request = new InitPayPalPaymentRequest();

        $response = $this->doRequest($request, $options);

        $paymentTransaction
            ->setRequest($options)
            ->setActive(true)
            ->setResponse($response->getData());

        $redirectUrl = $response->getRedirectUrl();

        return $redirectUrl ? ['redirectTo' => $redirectUrl] : [];
    }
}
