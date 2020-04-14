<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Form\Type;

use Oro\Bundle\FormBundle\Form\Type\OroEncodedPlaceholderPasswordType;
use Oro\Bundle\LocaleBundle\Form\Type\LocalizedFallbackValueCollectionType;
use Oro\Bundle\LocaleBundle\Tests\Unit\Form\Type\Stub\LocalizedFallbackValueCollectionTypeStub;
use Oro\Bundle\SecurityBundle\Encoder\SymmetricCrypterInterface;
use Oro\Bundle\WirecardBundle\Entity\WirecardSeamlessSettings;
use Oro\Bundle\WirecardBundle\Form\Type\WirecardSeamlessSettingsType;
use Oro\Component\Testing\Unit\FormIntegrationTestCase;
use Oro\Component\Testing\Unit\PreloadedExtension;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Validation;

class WirecardSeamlessSettingsTypeTest extends FormIntegrationTestCase
{
    /**
     * @var WirecardSeamlessSettingsType
     */
    private $formType;

    /**
     * @var SymmetricCrypterInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $encoder;

    protected function setUp(): void
    {
        $this->encoder = $this->createMock(SymmetricCrypterInterface::class);
        $this->formType = new WirecardSeamlessSettingsType();

        parent::setUp();
    }

    /**
     * @return array
     */
    protected function getExtensions()
    {
        $localizedType = new LocalizedFallbackValueCollectionTypeStub();
        $oroEncodedPasswordType = new OroEncodedPlaceholderPasswordType($this->encoder);

        return [
            new PreloadedExtension(
                [
                    WirecardSeamlessSettingsType::class => $this->formType,
                    LocalizedFallbackValueCollectionType::class => $localizedType,
                    OroEncodedPlaceholderPasswordType::class => $oroEncodedPasswordType,
                ],
                []
            ),
            new ValidatorExtension(Validation::createValidator()),
        ];
    }

    public function testGetBlockPrefixReturnsCorrectString()
    {
        static::assertSame(WirecardSeamlessSettingsType::BLOCK_PREFIX, $this->formType->getBlockPrefix());
    }

    public function testSubmit()
    {
        $submitData = [
            'creditCardLabels' => [['string' => 'creditCard']],
            'creditCardShortLabels' => [['string' => 'creditCardShort']],
            'paypalLabels' => [['string' => 'paypal']],
            'paypalShortLabels' => [['string' => 'paypalShort']],
            'sepaLabels' => [['string' => 'sepa']],
            'sepaShortLabels' => [['string' => 'sepaShort']],
            'customerId' => 'customerId',
            'shopId' => 'shopId',
            'secret' => 'secret',
            'wcTestMode' => false,
        ];

        $this->encoder
            ->expects(static::any())
            ->method('encryptData')
            ->willReturnMap([
                [$submitData['secret'], $submitData['secret']],
            ]);

        $wcsSettings = new WirecardSeamlessSettings();

        $form = $this->factory->create(WirecardSeamlessSettingsType::class, $wcsSettings);

        $form->submit($submitData);

        static::assertTrue($form->isValid());
        static::assertTrue($form->isSynchronized());
        static::assertEquals($wcsSettings, $form->getData());
    }

    public function testConfigureOptions()
    {
        /** @var OptionsResolver|\PHPUnit\Framework\MockObject\MockObject $resolver */
        $resolver = $this->createMock(OptionsResolver::class);
        $resolver->expects(static::once())
            ->method('setDefaults')
            ->with([
                'data_class' => WirecardSeamlessSettings::class,
            ]);

        $this->formType->configureOptions($resolver);
    }
}
