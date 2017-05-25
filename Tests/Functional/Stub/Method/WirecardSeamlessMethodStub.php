<?php

namespace Oro\Bundle\WirecardBundle\Tests\Functional\Stub\Method;

use Oro\Bundle\PaymentBundle\Context\PaymentContextInterface;
use Oro\Bundle\PaymentBundle\Entity\PaymentTransaction;
use Oro\Bundle\WirecardBundle\Method\WirecardSeamlessInitiateAwarePaymentMethod;
use Oro\Bundle\WirecardBundle\Method\WirecardSeamlessInitiateAwarePaymentMethodInterface;

class WirecardSeamlessMethodStub implements WirecardSeamlessInitiateAwarePaymentMethodInterface
{
    const TYPE = 'test_wirecard_seamless';

    const TEST_URL = '/';

    const TEST_STORAGE_ID = 'storageId';
    const TEST_JAVASCRIPT_URL = 'javascriptURL';

    /**
     * {@inheritdoc}
     */
    public function execute($action, PaymentTransaction $paymentTransaction)
    {
        if (!$this->supports($action)) {
            throw new \InvalidArgumentException(sprintf('Unsupported action "%s"', $action));
        }

        return $this->{$action}($paymentTransaction) ?: [];
    }

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
        return in_array(
            $actionName,
            [WirecardSeamlessInitiateAwarePaymentMethodInterface::INITIATE, self::PURCHASE],
            true
        );
    }
}
