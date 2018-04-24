<?php

namespace Oro\Bundle\WirecardBundle\Form\Type;

use Oro\Bundle\FormBundle\Form\Type\OroEncodedPlaceholderPasswordType;
use Oro\Bundle\LocaleBundle\Form\Type\LocalizedFallbackValueCollectionType;
use Oro\Bundle\WirecardBundle\Entity\WirecardSeamlessSettings;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class WirecardSeamlessSettingsType extends AbstractType
{
    const BLOCK_PREFIX = 'oro_wirecard_seamless_settings';

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('creditCardLabels', LocalizedFallbackValueCollectionType::class, [
                'label' => 'oro.wirecard.settings.wirecard_seamless.credit_card_labels.label',
                'required' => true,
                'entry_options' => ['constraints' => [new NotBlank()]],
            ])
            ->add('creditCardShortLabels', LocalizedFallbackValueCollectionType::class, [
                'label' => 'oro.wirecard.settings.wirecard_seamless.credit_card_short_labels.label',
                'required' => true,
                'entry_options' => ['constraints' => [new NotBlank()]],
            ])
            ->add('paypalLabels', LocalizedFallbackValueCollectionType::class, [
                'label' => 'oro.wirecard.settings.wirecard_seamless.paypal_labels.label',
                'required' => true,
                'entry_options' => ['constraints' => [new NotBlank()]],
            ])
            ->add('paypalShortLabels', LocalizedFallbackValueCollectionType::class, [
                'label' => 'oro.wirecard.settings.wirecard_seamless.paypal_short_labels.label',
                'required' => true,
                'entry_options' => ['constraints' => [new NotBlank()]],
            ])
            ->add('sepaLabels', LocalizedFallbackValueCollectionType::class, [
                'label' => 'oro.wirecard.settings.wirecard_seamless.sepa_labels.label',
                'required' => true,
                'entry_options' => ['constraints' => [new NotBlank()]],
            ])
            ->add('sepaShortLabels', LocalizedFallbackValueCollectionType::class, [
                'label' => 'oro.wirecard.settings.wirecard_seamless.sepa_short_labels.label',
                'required' => true,
                'entry_options' => ['constraints' => [new NotBlank()]],
            ])
            ->add('customerId', TextType::class, [
                'label' => 'oro.wirecard.settings.wirecard_seamless.customer_id.label',
                'required' => true,
                'attr' => ['autocomplete' => 'off'],
            ])
            ->add('shopId', TextType::class, [
                'label' => 'oro.wirecard.settings.wirecard_seamless.shop_id.label',
                'required' => false,
                'attr' => ['autocomplete' => 'off'],
            ])
            ->add('secret', OroEncodedPlaceholderPasswordType::class, [
                'label' => 'oro.wirecard.settings.wirecard_seamless.secret.label',
                'required' => true,
                'attr' => ['autocomplete' => 'new-password'],
            ])
            ->add('wcTestMode', CheckboxType::class, [
                'label' => 'oro.wirecard.settings.wirecard_seamless.test_mode.label',
                'required' => false,
            ]);
    }

    /**
     * {@inheritdoc}
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
