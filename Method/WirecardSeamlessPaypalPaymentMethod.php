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
        $options = $this->getBaseOptions($paymentTransaction);

        $request = new InitPaypalPaymentRequest();

        $response = $this->doRequest($request, $options);

        $paymentTransaction
            ->setRequest($options)
            ->setResponse($response->getData());

        $redirectUrl = $response->getRedirectUrl();

        return $redirectUrl ? ['redirectTo' => $redirectUrl] : [];
    }
}
