<?php

namespace Oro\Bundle\WirecardBundle\Method\Config;

use Oro\Bundle\PaymentBundle\Method\Config\ParameterBag\AbstractParameterBagPaymentConfig;

abstract class WirecardSeamlessConfig extends AbstractParameterBagPaymentConfig implements
    WirecardSeamlessConfigInterface
{
    const CREDENTIALS_KEY = 'credentials';
    const HASHING_METHOD_KEY = 'hashing';
    const LANGUAGE_KEY = 'language';
    const TEST_MODE_KEY = 'test_mode';

    /**
     * {@inheritdoc}
     */
    public function getCredentials()
    {
        return $this->get(self::CREDENTIALS_KEY);
    }

    /**
     * {@inheritdoc}
     */
    public function getHashingMethod()
    {
        return $this->get(self::HASHING_METHOD_KEY);
    }

    /**
     * {@inheritdoc}
     */
    public function getLanguageCode()
    {
        return $this->get(self::LANGUAGE_KEY);
    }

    /**
     * {@inheritdoc}
     */
    public function isTestMode()
    {
        return (bool)$this->get(self::TEST_MODE_KEY);
    }
}
