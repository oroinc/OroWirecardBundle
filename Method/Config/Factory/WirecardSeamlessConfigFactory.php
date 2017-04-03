<?php

namespace Oro\Bundle\WirecardBundle\Method\Config\Factory;

use Doctrine\Common\Collections\Collection;
use Oro\Bundle\IntegrationBundle\Entity\Channel;
use Oro\Bundle\IntegrationBundle\Generator\IntegrationIdentifierGeneratorInterface;
use Oro\Bundle\LocaleBundle\Helper\LocalizationHelper;
use Oro\Bundle\SecurityBundle\Encoder\SymmetricCrypterInterface;
use Oro\Bundle\WirecardBundle\Entity\WirecardSeamlessSettings;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option;

abstract class WirecardSeamlessConfigFactory
{
    /**
     * @var SymmetricCrypterInterface
     */
    private $encoder;

    /**
     * @var LocalizationHelper
     */
    private $localizationHelper;

    /**
     * @var IntegrationIdentifierGeneratorInterface
     */
    private $identifierGenerator;

    /**
     * @param SymmetricCrypterInterface               $encoder
     * @param LocalizationHelper                      $localizationHelper
     * @param IntegrationIdentifierGeneratorInterface $identifierGenerator
     */
    public function __construct(
        SymmetricCrypterInterface $encoder,
        LocalizationHelper $localizationHelper,
        IntegrationIdentifierGeneratorInterface $identifierGenerator
    ) {
        $this->encoder = $encoder;
        $this->localizationHelper = $localizationHelper;
        $this->identifierGenerator = $identifierGenerator;
    }

    /**
     * @param WirecardSeamlessSettings $settings
     *
     * @return array
     */
    protected function getCredentials(WirecardSeamlessSettings $settings)
    {
        return [
            Option\CustomerId::CUSTOMERID => $settings->getCustomerId(),
            Option\ShopId::SHOPID => $settings->getShopId(),
            Option\Secret::SECRET => $this->getDecryptedValue($settings->getSecret()),
        ];
    }

    protected function getLanguageCode()
    {
        $localization = $this->localizationHelper->getCurrentLocalization();

        return $localization ? $localization->getLanguageCode() : Option\Language::EN;
    }

    /**
     * @param Collection $values
     *
     * @return string
     */
    protected function getLocalizedValue(Collection $values)
    {
        return (string) $this->localizationHelper->getLocalizedValue($values);
    }

    protected function getHashingMethod()
    {
        return Option\Hashing::DEFAULT_HASHING_METHOD;
    }

    /**
     * @param string $value
     *
     * @return string
     */
    protected function getDecryptedValue($value)
    {
        return (string) $this->encoder->decryptData($value);
    }

    /**
     * @param Channel $channel
     *
     * @return string
     */
    protected function getPaymentMethodIdentifier(Channel $channel)
    {
        return (string) $this->identifierGenerator->generateIdentifier($channel);
    }

    /**
     * @param Channel $channel
     * @param string  $suffix
     *
     * @return string
     */
    protected function getAdminLabel(Channel $channel, $suffix = '')
    {
        return sprintf('%s - %s', $channel->getName(), $suffix);
    }
}
