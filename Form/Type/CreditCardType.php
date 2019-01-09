<?php

namespace Oro\Bundle\WirecardBundle\Form\Type;

use Oro\Bundle\ValidationBundle\Validator\Constraints\Integer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Credit card form
 */
class CreditCardType extends AbstractType
{
    const NAME = 'oro_wirecard_seamless_credit_card';

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $formBuilder, array $options)
    {
        $formBuilder
            ->add(
                'cardholderName',
                TextType::class,
                [
                    'label' => 'oro.wirecard.credit_card.card_holder_name.label',
                    'constraints' => [
                        new NotBlank(),
                        new Length(['min' => '3', 'max' => '256']),
                    ],
                    'attr' => [
                        'data-card-holder-name' => true,
                        'autocomplete' => 'off',
                        'placeholder' => false,
                    ],
                ]
            )
            ->add(
                'creditCardNumber',
                TextType::class,
                [
                    'required' => true,
                    'label' => 'oro.wirecard.credit_card.card_number.label',
                    'mapped' => false,
                    'attr' => [
                        'autocomplete' => 'off',
                        'data-pan' => true,
                        'placeholder' => false,
                        'data-validation' => [
                            'credit-card-number' => [
                                'message' => 'oro.payment.validation.credit_card',
                                'payload' => null,
                            ],
                        ],
                    ],
                    'constraints' => [
                        new Integer(),
                        new NotBlank(),
                        new Length(['min' => '12', 'max' => '19']),
                    ],
                ]
            )
            ->add(
                'expirationDate',
                CreditCardExpirationDateType::class,
                [
                    'required' => true,
                    'label' => 'oro.wirecard.credit_card.expiration_date.label',
                    'mapped' => false,
                    'placeholder' => [
                        'year' => 'oro.wirecard.credit_card.expiration_date.year',
                        'month' => 'oro.wirecard.credit_card.expiration_date.month',
                    ],
                    'attr' => [
                        'data-expiration-date' => true,
                    ],
                ]
            )
            ->add(
                'cvv',
                PasswordType::class,
                [
                    'label' => 'oro.wirecard.credit_card.cvv.label',
                    'block_name' => 'payment_credit_card_cvv',
                    'constraints' => [
                        new NotBlank(),
                        new Integer(),
                        new Length(['min' => '3', 'max' => '4']),
                    ],
                    'attr' => [
                        'placeholder' => false,
                        'data-cvc' => true,
                    ],
                ]
            );
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        foreach ($view->children as $child) {
            $child->vars['full_name'] = $child->vars['name'];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return self::NAME;
    }
}
