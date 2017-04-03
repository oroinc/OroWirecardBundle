<?php

namespace Oro\Bundle\WirecardBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Oro\Bundle\ValidationBundle\Validator\Constraints\Integer;

class CreditCardType extends AbstractType
{
    const NAME = 'oro_wirecard_seamless_credit_card';

    /**
     * @param FormBuilderInterface $formBuilder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $formBuilder, array $options)
    {
        $formBuilder
            ->add(
                'cardholdername',
                TextType::class,
                [
                    'constraints' => [
                        new NotBlank(),
                        new Length(['min' => '3', 'max' => '256']),
                    ],
                    'attr' => [
                        'data-card-holder-name' => true,
                    ],
                ]
            )
            ->add(
                'pan',
                TextType::class,
                [
                    'required' => true,
                    'label' => 'oro.wirecard.credit_card.card_number.label',
                    'mapped' => false,
                    'attr' => [
                        'autocomplete' => 'off',
                        'data-pan' => true,
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
                'oro_wirecard_seamless_credit_card_expiration_date',
                [
                    'required' => true,
                    'label' => 'oro.wirecard.credit_card.expiration_date.label',
                    'mapped' => false,
                    'placeholder' => [
                        'year' => 'Year',
                        'month' => 'Month',
                    ],
                    'attr' => [
                        'data-expiration-date' => true,
                    ],
                ]
            )
            ->add(
                'cardverifycode',
                TextType::class,
                [
                    'constraints' => [
                        new NotBlank(),
                        new Integer(),
                        new Length(['min' => '3', 'max' => '3']),
                    ],
                    'attr' => [
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
     * @return string
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
