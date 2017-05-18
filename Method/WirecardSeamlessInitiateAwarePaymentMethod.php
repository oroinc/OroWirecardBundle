<?php

namespace Oro\Bundle\WirecardBundle\Method;

use Oro\Bundle\CheckoutBundle\Entity\Checkout;
use Oro\Bundle\PaymentBundle\Entity\PaymentTransaction;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Request\InitDataStorageRequest;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Request\InitPaymentRequest;

abstract class WirecardSeamlessInitiateAwarePaymentMethod extends AbstractWirecardSeamlessPaymentMethod
{
    const INITIATE = 'initiate';

    /**
     * @param PaymentTransaction $paymentTransaction
     */
    public function initiate(PaymentTransaction $paymentTransaction)
    {
        // it's just service transaction and will be used to store storage info.
        // It wouldn't be sent to wirecard
        $paymentTransaction
            ->setAmount(0)
            ->setCurrency('');

        $options = $this->getInitiateOptions($paymentTransaction);
        $request = new InitDataStorageRequest();
        $response = $this->doRequest($request, $options);

        $paymentTransaction
            ->setRequest($options)
            ->setResponse($response->getData())
            ->setActive(true);
    }

    /**
     * @param PaymentTransaction $paymentTransaction
     *
     * @return array
     */
    public function purchase(PaymentTransaction $paymentTransaction)
    {
        $checkout = $this->extractCheckout($paymentTransaction);
        if (!$checkout) {
            throw new \RuntimeException('Appropriate checkout is not found');
        }

        $initiateTransaction = $this->transactionProvider->getActiveInitiatePaymentTransaction(
            $checkout,
            $paymentTransaction->getPaymentMethod()
        );
        if (!$initiateTransaction) {
            throw new \RuntimeException('Initiate payment transaction not found');
        }

        $options = $this->getInitPaymentOptions($initiateTransaction, $paymentTransaction);
        $request = new InitPaymentRequest();
        $response = $this->doRequest($request, $options);

        $initiateTransaction->setActive(false);

        $paymentTransaction
            ->setRequest($options)
            ->setResponse($response->getData());

        $redirectUrl = $response->getRedirectUrl();

        return $redirectUrl ? ['redirectTo' => $redirectUrl] : [];
    }

    /**
     * @param PaymentTransaction $paymentTransaction
     * @return array
     */
    protected function getInitiateOptions(PaymentTransaction $paymentTransaction)
    {
        return [
            Option\Language::LANGUAGE => $this->config->getLanguageCode(),
            Option\OrderIdent::ORDERIDENT => $paymentTransaction->getAccessIdentifier(),
            Option\ReturnUrl::RETURNURL => $this->createReturnUrl($paymentTransaction),
        ];
    }

    /**
     * @param PaymentTransaction $initiateTransaction
     * @param PaymentTransaction $paymentTransaction
     * @return array
     */
    protected function getInitPaymentOptions(
        PaymentTransaction $initiateTransaction,
        PaymentTransaction $paymentTransaction
    ) {
        $initiateRequest = $initiateTransaction->getRequest();
        $initiateResponse = $initiateTransaction->getResponse();

        return array_merge(
            $this->getBaseOptions($paymentTransaction),
            $this->getShippingInfo($paymentTransaction),
            [
                Option\OrderIdent::ORDERIDENT => $initiateRequest[Option\OrderIdent::ORDERIDENT],
                Option\StorageId::STORAGEID => $initiateResponse[Option\StorageId::STORAGEID],
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function supports($actionName)
    {
        return parent::supports($actionName) || $actionName === self::INITIATE;
    }
}
