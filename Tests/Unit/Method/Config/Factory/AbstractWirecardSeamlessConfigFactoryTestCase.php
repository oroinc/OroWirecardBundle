<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Method\Config\Factory;

use Doctrine\Common\Collections\Collection;
use Oro\Bundle\IntegrationBundle\Entity\Channel;
use Oro\Bundle\IntegrationBundle\Generator\IntegrationIdentifierGeneratorInterface;
use Oro\Bundle\LocaleBundle\Entity\Localization;
use Oro\Bundle\LocaleBundle\Entity\LocalizedFallbackValue;
use Oro\Bundle\LocaleBundle\Helper\LocalizationHelper;
use Oro\Bundle\SecurityBundle\Encoder\SymmetricCrypterInterface;
use Oro\Bundle\WirecardBundle\Entity\WirecardSeamlessSettings;
use Oro\Bundle\WirecardBundle\Method\Config\Factory\WirecardSeamlessConfigFactoryInterface;
use Oro\Bundle\WirecardBundle\Method\Config\Mapping\WirecardLanguageCodeMapper;
use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessConfig;
use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessConfigInterface;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option;
use Oro\Component\Testing\Unit\EntityTrait;

abstract class AbstractWirecardSeamlessConfigFactoryTestCase extends \PHPUnit\Framework\TestCase
{
    use EntityTrait;

    /** @var SymmetricCrypterInterface|\PHPUnit\Framework\MockObject\MockObject */
    protected $encoder;

    /** @var LocalizationHelper|\PHPUnit\Framework\MockObject\MockObject */
    protected $localizationHelper;

    /** @var IntegrationIdentifierGeneratorInterface|\PHPUnit\Framework\MockObject\MockObject */
    protected $identifierGenerator;

    /** @var WirecardLanguageCodeMapper|\PHPUnit\Framework\MockObject\MockObject */
    protected $languageCodeMapper;

    /** @var WirecardSeamlessConfigFactoryInterface */
    protected $wirecardSeamlessConfigFactory;

    protected function setUp()
    {
        $this->encoder = $this->createMock(SymmetricCrypterInterface::class);
        $this->localizationHelper = $this->createMock(LocalizationHelper::class);
        $this->languageCodeMapper = $this->createMock(WirecardLanguageCodeMapper::class);
        $this->identifierGenerator = $this->createMock(IntegrationIdentifierGeneratorInterface::class);
        $this->wirecardSeamlessConfigFactory = $this->getConfigFactory();
    }

    /**
     * @return WirecardSeamlessConfigFactoryInterface
     */
    abstract protected function getConfigFactory(): WirecardSeamlessConfigFactoryInterface;

    /**
     * @return array
     */
    abstract public function wirecardSeamlessConfigProvider(): array;

    /**
     * @dataProvider wirecardSeamlessConfigProvider
     * @param WirecardSeamlessConfigInterface $expectedConfig
     */
    public function testCreateConfig(WirecardSeamlessConfigInterface $expectedConfig)
    {
        $this->encoder->expects($this->once())
            ->method('decryptData')
            ->willReturn('secret');

        /** @var Channel $channel */
        $channel = $this->getEntity(
            Channel::class,
            ['id' => 1, 'name' => 'wirecard']
        );

        $creditCardLabels = [(new LocalizedFallbackValue())->setString('credit card label')];
        $creditCardShortLabels = [(new LocalizedFallbackValue())->setString('credit card short label')];

        $paypalLabels = [(new LocalizedFallbackValue())->setString('paypal label')];
        $paypalShortLabels = [(new LocalizedFallbackValue())->setString('paypal short label')];

        $sepaLabels = [(new LocalizedFallbackValue())->setString('sepa label')];
        $sepaShortLabels = [(new LocalizedFallbackValue())->setString('sepa short label')];

        /** @var WirecardSeamlessSettings $wireCardSettings */
        $wireCardSettings = $this->getEntity(
            WirecardSeamlessSettings::class,
            [
                'customerId' => 1,
                'shopId' => 2,
                'secret' => 'secret',
                'wcTestMode' => false,
                'creditCardLabels' => $creditCardLabels,
                'creditCardShortLabels' => $creditCardShortLabels,
                'paypalLabels' => $paypalLabels,
                'paypalShortLabels' => $paypalShortLabels,
                'sepaLabels' => $sepaLabels,
                'sepaShortLabels' => $sepaShortLabels,
                'channel' => $channel,
            ]
        );

        $localization = $this->createMock(Localization::class);
        $localization->expects($this->once())->method('getLanguageCode')->willReturn('en');
        $this->localizationHelper->expects($this->exactly(1))->method('getCurrentLocalization')
            ->willReturn($localization);

        $this->languageCodeMapper->expects($this->once())
            ->method('mapLanguageCodeToWirecardLanguageCode')
            ->with('en')
            ->willReturn('EN');

        $this->localizationHelper->expects($this->exactly(2))
            ->method('getLocalizedValue')
            ->willReturnCallback(function (Collection $collection) {
                /** @var LocalizedFallbackValue $localizedFallbackValue */
                $localizedFallbackValue = $collection->first();

                return $localizedFallbackValue->getString();
            });

        $this->identifierGenerator->expects($this->once())
            ->method('generateIdentifier')
            ->with($channel)
            ->willReturn('wirecard_1');

        $this->assertEquals($expectedConfig, $this->wirecardSeamlessConfigFactory->createConfig($wireCardSettings));
    }

    /**
     * @param string $adminLabelSuffix
     * @return array
     */
    protected function getBaseConfigParameters($adminLabelSuffix)
    {
        return [
            WirecardSeamlessConfig::FIELD_PAYMENT_METHOD_IDENTIFIER => 'wirecard_1',
            WirecardSeamlessConfig::LANGUAGE_KEY => 'EN',
            WirecardSeamlessConfig::HASHING_METHOD_KEY => 'hmac-sha512',
            WirecardSeamlessConfig::TEST_MODE_KEY => false,
            WirecardSeamlessConfig::FIELD_ADMIN_LABEL => 'wirecard - ' . $adminLabelSuffix,
            WirecardSeamlessConfig::CREDENTIALS_KEY => [
                Option\CustomerId::CUSTOMERID => '1',
                Option\ShopId::SHOPID => '2',
                Option\Secret::SECRET => 'secret',
            ],
        ];
    }
}
