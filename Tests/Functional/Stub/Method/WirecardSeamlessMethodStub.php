<?php

namespace Oro\Bundle\WirecardBundle\Tests\Functional\Stub\Method;

use Oro\Bundle\PaymentBundle\Context\PaymentContextInterface;
use Oro\Bundle\PaymentBundle\Entity\PaymentTransaction;
use Oro\Bundle\PaymentBundle\Method\PaymentMethodInterface;
use Oro\Bundle\WirecardBundle\Method\WirecardSeamlessInitiateAwarePaymentMethod;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Request\RequestInterface;

class WirecardSeamlessMethodStub extends WirecardSeamlessInitiateAwarePaymentMethod implements PaymentMethodInterface
{
    const TYPE = 'test_wirecard_seamless';

    const TEST_URL = '/';

    const TEST_STORAGE_ID = 'storageId';
    const TEST_JAVASCRIPT_URL = 'javascriptURL';

    /**
     * {@inheritDoc}
     */
    public function purchase(PaymentTransaction $paymentTransaction)
    {
        return [
            'redirectUrl' => self::TEST_URL,
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function initiate(PaymentTransaction $paymentTransaction)
    {
        $paymentTransaction
            ->setAmount(0)
            ->setCurrency('')
            ->setResponse(
                [
                    'storageId' => self::TEST_STORAGE_ID,
                    'javascriptURL' => self::TEST_JAVASCRIPT_URL,
                ]
            );
    }

    /**
     * {@inheritDoc}
     */
    protected function doRequest(RequestInterface $request, array $options)
    {
        throw new \RuntimeException('This method should never be calld in test env');
    }


    /**
     * {@inheritDoc}
     */
    public function getIdentifier()
    {
        return static::TYPE;
    }

    /**
     * {@inheritDoc}
     */
    public function isApplicable(PaymentContextInterface $context)
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function supports($actionName)
    {
        return in_array($actionName, [WirecardSeamlessInitiateAwarePaymentMethod::INITIATE, self::PURCHASE], true);
    }

    /**
     * @return string
     */
    public function getWirecardPaymentType()
    {
        return self::TYPE;
    }
}
