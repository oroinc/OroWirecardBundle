<?php

namespace Oro\Bundle\PayPalBundle\Tests\Unit\Form\Type;

use Oro\Bundle\LocaleBundle\Tests\Unit\Form\Type\Stub\LocalizedFallbackValueCollectionTypeStub;
use Oro\Bundle\SecurityBundle\Encoder\SymmetricCrypterInterface;
use Oro\Bundle\WirecardBundle\Entity\WirecardSeamlessSettings;
use Oro\Bundle\WirecardBundle\Form\Type\WirecardSeamlessSettingsType;
use Oro\Component\Testing\Unit\FormIntegrationTestCase;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Validation;

class WirecardSeamlessSettingsTypeTest extends FormIntegrationTestCase
{
    /**
     * @var WirecardSeamlessSettingsType
     */
    private $formType;

    /**
     * @var SymmetricCrypterInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $encoder;

    public function setUp()
    {
        parent::setUp();

        /** @var TranslatorInterface|\PHPUnit_Framework_MockObject_MockObject $translator */
        $translator = $this->createMock(TranslatorInterface::class);

        $this->encoder = $this->createMock(SymmetricCrypterInterface::class);
        $this->formType = new WirecardSeamlessSettingsType(
            $translator,
            $this->encoder
        );
    }

    /**
     * @return array
     */
    protected function getExtensions()
    {
        $localizedType = new LocalizedFallbackValueCollectionTypeStub();

        return [
            new PreloadedExtension(
                [
                    $localizedType->getName() => $localizedType,
                ],
                []
            ),
            new ValidatorExtension(Validation::createValidator())
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
            'testMode' => false,
        ];

        $this->encoder
            ->expects(static::any())
            ->method('encryptData')
            ->willReturnMap([
                [$submitData['secret'], $submitData['secret']],
            ]);

        $wcsSettings = new WirecardSeamlessSettings();

        $form = $this->factory->create($this->formType, $wcsSettings);

        $form->submit($submitData);

        static::assertTrue($form->isValid());
        static::assertEquals($wcsSettings, $form->getData());
    }

    public function testConfigureOptions()
    {
        /** @var OptionsResolver|\PHPUnit_Framework_MockObject_MockObject $resolver */
        $resolver = $this->createMock(OptionsResolver::class);
        $resolver->expects(static::once())
            ->method('setDefaults')
            ->with([
                'data_class' => WirecardSeamlessSettings::class
            ]);

        $this->formType->configureOptions($resolver);
    }
}
