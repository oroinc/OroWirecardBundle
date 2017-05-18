<?php

namespace Oro\Bundle\WirecardBundle\Tests\Functional\Stub\Method;

use Oro\Bundle\PaymentBundle\Context\PaymentContextInterface;
use Oro\Bundle\PaymentBundle\Entity\PaymentTransaction;
use Oro\Bundle\PaymentBundle\Method\PaymentMethodInterface;
use Oro\Bundle\WirecardBundle\Method\WirecardSeamlessInitiateAwarePaymentMethod;

class WirecardSeamlessMethodStub implements PaymentMethodInterface
{
    const TYPE = 'test_wirecard_seamless';

    /** @internal */
    const TEST_URL = '/';

    const TEST_STORAGE_ID = 'storageId';
    const TEST_JAVASCRIPT_URL = 'javascriptURL';

    /**
     * {@inheritDoc}
     */
    public function execute($action, PaymentTransaction $paymentTransaction)
    {
        if (!$this->supports($action)) {
            throw new \InvalidArgumentException(sprintf('Unsupported action "%s"', $action));
        }

        if ($action === WirecardSeamlessInitiateAwarePaymentMethod::INITIATE) {
            $paymentTransaction
                ->setAmount(0)
                ->setCurrency('')
                ->setResponse(
                    [
                        'storageId' => self::TEST_STORAGE_ID,
                        'javascriptURL' => self::TEST_JAVASCRIPT_URL
                    ]
                );
        } elseif ($action === self::PURCHASE) {
            return [
                'redirectUrl' => self::TEST_URL,
            ];
        }
        return [];
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
        return WirecardSeamlessInitiateAwarePaymentMethod::INITIATE === $actionName || self::PURCHASE === $actionName;
    }
}
