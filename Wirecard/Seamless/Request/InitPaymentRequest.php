<?php

namespace Oro\Bundle\WirecardBundle\Wirecard\Seamless\Request;

use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option;
use Hochstrasser\Wirecard\Request\Seamless\Frontend\InitPaymentRequest as WirecardInitPaymentRequest;

class InitPaymentRequest extends AbstractRequest
{
    /**
     * {@inheritdoc}
     */
    protected function configureRequestOptions()
    {
        $this
            ->addOption(new Option\StorageId())
            ->addOption(new Option\OrderIdent())

            ->addOption(new Option\PaymentType())
            ->addOption(new Option\Amount())
            ->addOption(new Option\Currency())

            ->addOption(new Option\SuccessUrl())
            ->addOption(new Option\CancelUrl())
            ->addOption(new Option\FailureUrl())
            ->addOption(new Option\ConfirmUrl())
            ->addOption(new Option\ServiceUrl())

            ->addOption(new Option\ConsumerUserAgent())
            ->addOption(new Option\ConsumerIpAddress());

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function buildWirecardRequest(array $options = [])
    {
        $request = WirecardInitPaymentRequest::with();
        $request->setContext($this->buildContext($options));

        $request->setOrderIdent($options[Option\OrderIdent::ORDERIDENT]);
        $request->setStorageId($options[Option\StorageId::STORAGEID]);

        $request->setPaymentType($options[Option\PaymentType::PAYMENTTYPE]);
        $request->setAmount($options[Option\Amount::AMOUNT]);
        $request->setCurrency($options[Option\Currency::CURRENCY]);
        $request->setOrderDescription('orderDescription'); //FIXME: create order description

        $request->setSuccessUrl($options[Option\SuccessUrl::SUCCESSURL]);
        $request->setCancelUrl($options[Option\CancelUrl::CANCELURL]);
        $request->setFailureUrl($options[Option\FailureUrl::FAILUREURL]);
        $request->setConfirmUrl($options[Option\ConfirmUrl::CONFIRMURL]);
        $request->setServiceUrl($options[Option\ServiceUrl::SERVICEURL]);

        $request->setConsumerUserAgent($options[Option\ConsumerUserAgent::CONSUMERUSERAGENT]);
        $request->setConsumerIpAddress($options[Option\ConsumerIpAddress::CONSUMERIPADDRESS]);

        return $request;
    }
}
