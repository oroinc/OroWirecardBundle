<?php

namespace Oro\Bundle\WirecardBundle\Method\Config\Factory;

use Oro\Bundle\WirecardBundle\Entity\WirecardSeamlessSettings;
use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessPaypalConfig;

class WirecardSeamlessPaypalConfigFactory extends WirecardSeamlessConfigFactory implements
    WirecardSeamlessPaypalConfigFactoryInterface
{
    /**
     * @param WirecardSeamlessSettings $settings
     *
     * @return WirecardSeamlessPaypalConfig
     *
     * @throws \InvalidArgumentException
     */
    public function createConfig(WirecardSeamlessSettings $settings)
    {
        $channel = $settings->getChannel();

        $params = [
            WirecardSeamlessPaypalConfig::FIELD_PAYMENT_METHOD_IDENTIFIER =>
                $this->getPaymentMethodIdentifier($channel),
            WirecardSeamlessPaypalConfig::FIELD_ADMIN_LABEL =>
                $this->getAdminLabel($channel, WirecardSeamlessPaypalConfig::ADMIN_LABEL_SUFFIX),
            WirecardSeamlessPaypalConfig::FIELD_LABEL => $this->getLocalizedValue($settings->getPayPalLabels()),
            WirecardSeamlessPaypalConfig::FIELD_SHORT_LABEL =>
                $this->getLocalizedValue($settings->getPayPalShortLabels()),
            WirecardSeamlessPaypalConfig::CREDENTIALS_KEY => $this->getCredentials($settings),
            WirecardSeamlessPaypalConfig::LANGUAGE_KEY => $this->getLanguageCode(),
            WirecardSeamlessPaypalConfig::HASHING_METHOD_KEY => $this->getHashingMethod(),
            WirecardSeamlessPaypalConfig::TEST_MODE_KEY => $settings->getWcTestMode(),
        ];

        return new WirecardSeamlessPaypalConfig($params);
    }
}
