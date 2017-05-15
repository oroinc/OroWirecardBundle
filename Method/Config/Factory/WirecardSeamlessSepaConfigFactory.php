<?php

namespace Oro\Bundle\WirecardBundle\Method\Config\Factory;

use Oro\Bundle\WirecardBundle\Entity\WirecardSeamlessSettings;
use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessSepaConfig;

class WirecardSeamlessSepaConfigFactory extends WirecardSeamlessConfigFactory implements
    WirecardSeamlessSepaConfigFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function createConfig(WirecardSeamlessSettings $settings)
    {
        $channel = $settings->getChannel();

        $params = [
            WirecardSeamlessSepaConfig::FIELD_PAYMENT_METHOD_IDENTIFIER => $this->getPaymentMethodIdentifier($channel),
            WirecardSeamlessSepaConfig::FIELD_ADMIN_LABEL =>
                $this->getAdminLabel($channel, WirecardSeamlessSepaConfig::ADMIN_LABEL_SUFFIX),
            WirecardSeamlessSepaConfig::FIELD_LABEL => $this->getLocalizedValue($settings->getSepaLabels()),
            WirecardSeamlessSepaConfig::FIELD_SHORT_LABEL => $this->getLocalizedValue($settings->getSepaShortLabels()),
            WirecardSeamlessSepaConfig::CREDENTIALS_KEY => $this->getCredentials($settings),
            WirecardSeamlessSepaConfig::LANGUAGE_KEY => $this->getLanguageCode(),
            WirecardSeamlessSepaConfig::HASHING_METHOD_KEY => $this->getHashingMethod(),
            WirecardSeamlessSepaConfig::TEST_MODE_KEY => $settings->getTestMode(),
        ];

        return new WirecardSeamlessSepaConfig($params);
    }
}
