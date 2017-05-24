<?php

namespace Oro\Bundle\WirecardBundle\Method;

use Oro\Bundle\PaymentBundle\Entity\PaymentTransaction;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Request\InitSepaPaymentRequest;

class WirecardSeamlessSepaPaymentMethod extends WirecardSeamlessInitiateAwarePaymentMethod
{
    const TYPE = 'SEPA-DD';

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
        $initiateTransaction = $this->getInitiateTransaction($paymentTransaction);

        if (!$initiateTransaction) {
            throw new \RuntimeException('Initiate payment transaction not found');
        }

        $options = $this->getInitPaymentOptions($initiateTransaction, $paymentTransaction);
        $request = new InitSepaPaymentRequest();
        $response = $this->doRequest($request, $options);

        $initiateTransaction->setActive(false);

        $paymentTransaction
            ->setRequest($options)
            ->setActive(true)
            ->setResponse($response->getData());

        $redirectUrl = $response->getRedirectUrl();

        return $redirectUrl ? ['redirectTo' => $redirectUrl] : [];
    }
}
