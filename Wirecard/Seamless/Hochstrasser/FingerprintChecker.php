<?php

namespace Oro\Bundle\WirecardBundle\Wirecard\Seamless\Hochstrasser;

use Hochstrasser\Wirecard\Context;
use Hochstrasser\Wirecard\Fingerprint;
use Oro\Bundle\PaymentBundle\Entity\PaymentTransaction;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\FingerprintCheckerInterface;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Response\Response;

class FingerprintChecker implements FingerprintCheckerInterface
{
    /**
     * {@inheritdoc}
     */
    public function check(PaymentTransaction $paymentTransaction, array $data)
    {
        $paymentTransaction->getRequest();
        $response = new Response($data);

        try {
            $fingerprint = Fingerprint::fromResponseParameters($data);
        } catch (\UnexpectedValueException $e) {
            // TODO: BB-9537 need to improve this
            // Fingerprint order information not found in wirecard callback
            return true;
        }

        $keys = ['hashingMethod'];

        $contextData = array_intersect_key($data, array_flip($keys));
        $context = new Context($contextData);
        $fingerprint->setContext($context);

        return hash_equals((string)$fingerprint, $response->getFingerprint());
    }
}
