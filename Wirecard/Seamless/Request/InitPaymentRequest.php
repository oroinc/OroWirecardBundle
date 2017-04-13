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
            ->addOption(new Option\OrderDescription())

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

        foreach ($options as $key => $value) {
            $request->addParam($key, $value);
        }

        return $request;
    }
}
