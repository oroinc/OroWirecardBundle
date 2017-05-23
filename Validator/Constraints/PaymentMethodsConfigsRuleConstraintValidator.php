<?php

namespace Oro\Bundle\WirecardBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

use Oro\Bundle\PaymentBundle\Entity\PaymentMethodsConfigsRule;
use Oro\Bundle\PaymentBundle\Form\Type\PaymentMethodsConfigsRuleType;
use Oro\Bundle\WirecardBundle\Method\Config\Provider\WirecardSeamlessSepaConfigProvider;

class PaymentMethodsConfigsRuleConstraintValidator extends ConstraintValidator
{
    const CURRENCY_EUR = 'EUR';

    /**
     * @var WirecardSeamlessSepaConfigProvider
     */
    protected $sepaConfigProvider;

    /**
     * @param WirecardSeamlessSepaConfigProvider $sepaConfigProvider
     */
    public function __construct(WirecardSeamlessSepaConfigProvider $sepaConfigProvider)
    {
        $this->sepaConfigProvider = $sepaConfigProvider;
    }

    /**
     * {@inheritdoc}
     * @param PaymentMethodsConfigsRule $rule
     * @param PaymentMethodsConfigsRuleConstraint $constraint
     */
    public function validate($rule, Constraint $constraint)
    {
        if (!$rule instanceof PaymentMethodsConfigsRule) {
            throw new UnexpectedTypeException($rule, PaymentMethodsConfigsRule::class);
        }

        // Don't validate if currency EUR
        if ($rule->getCurrency() === self::CURRENCY_EUR) {
            return;
        }

        if (!$this->hasSepaMethod($rule)) {
            return;
        }

        /** @var ExecutionContextInterface $context */
        $context = $this->context;
        $context->buildViolation($constraint->message)
            ->addViolation();
    }

    /**
     * @param PaymentMethodsConfigsRule $rule
     * @return bool
     */
    protected function hasSepaMethod(PaymentMethodsConfigsRule $rule)
    {
        $sepaPaymentConfigs = $this->sepaConfigProvider->getPaymentConfigs();

        foreach ($rule->getMethodConfigs() as $method) {
            foreach ($sepaPaymentConfigs as $config) {
                if ($method->getType() === $config->getPaymentMethodIdentifier()) {
                    return true;
                }
            }
        }

        return false;
    }
}
