<?php

namespace Oro\Bundle\WirecardBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Validator\Constraints\NotBlank;

class SepaDirectDebitType extends AbstractType
{
    const NAME = 'oro_wirecard_seamless_sepa_direct_debit';

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $formBuilder, array $options)
    {
        $formBuilder
            ->add(
                'accountOwner',
                TextType::class,
                [
                    'label' => 'oro.wirecard.sepa.account_owner',
                    'required' => true,
                    'attr' => [
                        'placeholder' => false,
                        'data-account-owner' => true,
                        'autocomplete' => 'off',
                    ],
                    'constraints' => [
                        new NotBlank(),
                    ],
                ]
            )
            ->add(
                'bankIban',
                TextType::class,
                [
                    'required' => true,
                    'label' => 'oro.wirecard.sepa.iban.label',
                    'mapped' => false,
                    'attr' => [
                        'data-validation' => [
                            'sepa-iban' => [
                                'message' => 'oro.wirecard.validation.iban',
                                'payload' => null,
                            ],
                        ],
                        'placeholder' => false,
                        'autocomplete' => 'off',
                        'data-bank-iban' => true,
                    ],
                    'constraints' => [
                        new NotBlank(),
                    ],
                ]
            )
            ->add(
                'bankBic',
                TextType::class,
                [
                    'required' => true,
                    'label' => 'oro.wirecard.sepa.bic.label',
                    'mapped' => false,
                    'attr' => [
                        'data-validation' => [
                            'sepa-bic' => [
                                'message' => 'oro.wirecard.validation.bic',
                                'payload' => null,
                            ],
                        ],
                        'placeholder' => false,
                        'autocomplete' => 'off',
                        'data-bank-bic' => true,

                    ],
                    'constraints' => [
                        new NotBlank(),

                    ],
                ]
            );
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

    /**
     * {@inheritdoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        foreach ($view->children as $child) {
            $child->vars['full_name'] = $child->vars['name'];
        }
    }
}
