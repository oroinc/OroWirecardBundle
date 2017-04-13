<?php

namespace Oro\Bundle\WirecardBundle\Form\Type;

use Oro\Bundle\LocaleBundle\Form\Type\LocalizedFallbackValueCollectionType;
use Oro\Bundle\SecurityBundle\Encoder\SymmetricCrypterInterface;
use Oro\Bundle\WirecardBundle\Entity\WirecardSeamlessSettings;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Exception\AccessException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Exception\InvalidOptionsException;
use Symfony\Component\Validator\Exception\MissingOptionsException;

/**
 * Class WirecardSeamlessSettingsType.
 */
class WirecardSeamlessSettingsType extends AbstractType
{
    const BLOCK_PREFIX = 'oro_wirecard_seamless_settings';

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var SymmetricCrypterInterface
     */
    protected $encoder;

    /**
     * @param TranslatorInterface       $translator
     * @param SymmetricCrypterInterface $encoder
     */
    public function __construct(
        TranslatorInterface $translator,
        SymmetricCrypterInterface $encoder
    ) {
        $this->translator = $translator;
        $this->encoder = $encoder;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     *
     * @throws ConstraintDefinitionException
     * @throws InvalidOptionsException
     * @throws MissingOptionsException
     * @throws \InvalidArgumentException
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('creditCardLabels', LocalizedFallbackValueCollectionType::NAME, [
                'label' => 'oro.wirecard.settings.wirecard_seamless.credit_card_labels.label',
                'required' => true,
                'options' => ['constraints' => [new NotBlank()]],
            ])
            ->add('creditCardShortLabels', LocalizedFallbackValueCollectionType::NAME, [
                'label' => 'oro.wirecard.settings.wirecard_seamless.credit_card_short_labels.label',
                'required' => true,
                'options' => ['constraints' => [new NotBlank()]],
            ])
            ->add('paypalLabels', LocalizedFallbackValueCollectionType::NAME, [
                'label' => 'oro.wirecard.settings.wirecard_seamless.paypal_labels.label',
                'required' => true,
                'options' => ['constraints' => [new NotBlank()]],
            ])
            ->add('paypalShortLabels', LocalizedFallbackValueCollectionType::NAME, [
                'label' => 'oro.wirecard.settings.wirecard_seamless.paypal_short_labels.label',
                'required' => true,
                'options' => ['constraints' => [new NotBlank()]],
            ])
            ->add('sepaLabels', LocalizedFallbackValueCollectionType::NAME, [
                'label' => 'oro.wirecard.settings.wirecard_seamless.sepa_labels.label',
                'required' => true,
                'options' => ['constraints' => [new NotBlank()]],
            ])
            ->add('sepaShortLabels', LocalizedFallbackValueCollectionType::NAME, [
                'label' => 'oro.wirecard.settings.wirecard_seamless.sepa_short_labels.label',
                'required' => true,
                'options' => ['constraints' => [new NotBlank()]],
            ])
            ->add('customerId', TextType::class, [
                'label' => 'oro.wirecard.settings.wirecard_seamless.customer_id.label',
                'required' => true,
            ])
            ->add('shopId', TextType::class, [
                'label' => 'oro.wirecard.settings.wirecard_seamless.shop_id.label',
                'required' => false,
            ])
            ->add('secret', PasswordType::class, [
                'label' => 'oro.wirecard.settings.wirecard_seamless.secret.label',
                'required' => true,
            ])
            ->add('testMode', CheckboxType::class, [
                'label' => 'oro.wirecard.settings.wirecard_seamless.test_mode.label',
                'required' => false,
            ]);

        $builder
            ->get('secret')
            ->addModelTransformer(new CallbackTransformer(
                function ($value) {
                    return $value;
                },
                function ($value) {
                    return $this->encoder->encryptData($value);
                }
            ));
    }

    /**
     * @param OptionsResolver $resolver
     *
     * @throws AccessException
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => WirecardSeamlessSettings::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return self::BLOCK_PREFIX;
    }
}
