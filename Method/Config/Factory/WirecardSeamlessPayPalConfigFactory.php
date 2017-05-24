<?php

namespace Oro\Bundle\WirecardBundle\Method\Config\Factory;

use Oro\Bundle\WirecardBundle\Entity\WirecardSeamlessSettings;
use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessPayPalConfig;

class WirecardSeamlessPayPalConfigFactory extends WirecardSeamlessConfigFactory implements
    WirecardSeamlessPayPalConfigFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function createConfig(WirecardSeamlessSettings $settings)
    {
        $channel = $settings->getChannel();

        $params = [
            WirecardSeamlessPayPalConfig::FIELD_PAYMENT_METHOD_IDENTIFIER =>
                $this->getPaymentMethodIdentifier($channel),
            WirecardSeamlessPayPalConfig::FIELD_ADMIN_LABEL =>
                $this->getAdminLabel($channel, WirecardSeamlessPayPalConfig::ADMIN_LABEL_SUFFIX),
            WirecardSeamlessPayPalConfig::FIELD_LABEL => $this->getLocalizedValue($settings->getPayPalLabels()),
            WirecardSeamlessPayPalConfig::FIELD_SHORT_LABEL =>
                $this->getLocalizedValue($settings->getPayPalShortLabels()),
            WirecardSeamlessPayPalConfig::CREDENTIALS_KEY => $this->getCredentials($settings),
            WirecardSeamlessPayPalConfig::LANGUAGE_KEY => $this->getLanguageCode(),
            WirecardSeamlessPayPalConfig::HASHING_METHOD_KEY => $this->getHashingMethod(),
            WirecardSeamlessPayPalConfig::TEST_MODE_KEY => $settings->isWcTestMode(),
        ];

        return new WirecardSeamlessPayPalConfig($params);
    }
}
