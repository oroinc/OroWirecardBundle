<?php

namespace Oro\Bundle\WirecardBundle\Method\Config\Factory;

use Oro\Bundle\WirecardBundle\Entity\WirecardSeamlessSettings;
use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessCreditCardConfig;

class WirecardSeamlessCreditCardConfigFactory extends WirecardSeamlessConfigFactory implements
    WirecardSeamlessCreditCardConfigFactoryInterface
{
    /**
     * * {@inheritdoc}
     */
    public function createConfig(WirecardSeamlessSettings $settings)
    {
        $channel = $settings->getChannel();

        $params = [
            WirecardSeamlessCreditCardConfig::FIELD_PAYMENT_METHOD_IDENTIFIER =>
                $this->getPaymentMethodIdentifier($channel),
            WirecardSeamlessCreditCardConfig::FIELD_ADMIN_LABEL =>
                $this->getAdminLabel($channel, WirecardSeamlessCreditCardConfig::ADMIN_LABEL_SUFFIX),
            WirecardSeamlessCreditCardConfig::FIELD_LABEL =>
                $this->getLocalizedValue($settings->getCreditCardLabels()),
            WirecardSeamlessCreditCardConfig::FIELD_SHORT_LABEL =>
                $this->getLocalizedValue($settings->getCreditCardShortLabels()),
            WirecardSeamlessCreditCardConfig::CREDENTIALS_KEY => $this->getCredentials($settings),
            WirecardSeamlessCreditCardConfig::LANGUAGE_KEY => $this->getLanguageCode(),
            WirecardSeamlessCreditCardConfig::HASHING_METHOD_KEY => $this->getHashingMethod(),
            WirecardSeamlessCreditCardConfig::TEST_MODE_KEY => $settings->getTestMode(),
        ];

        return new WirecardSeamlessCreditCardConfig($params);
    }
}
