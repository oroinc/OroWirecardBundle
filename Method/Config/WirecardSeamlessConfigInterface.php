<?php

namespace Oro\Bundle\WirecardBundle\Method\Config;

use Oro\Bundle\PaymentBundle\Method\Config\PaymentConfigInterface;

interface WirecardSeamlessConfigInterface extends PaymentConfigInterface
{
    /**
     * @return array
     */
    public function getCredentials();

    /**
     * @return string
     */
    public function getLanguageCode();

    /**
     * @return string
     */
    public function getHashingMethod();

    /**
     * @return bool
     */
    public function isTestMode();
}
