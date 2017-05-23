<?php

namespace Oro\Bundle\WirecardBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class PaymentMethodsConfigsRuleConstraint extends Constraint
{
    /**
     * @var string
     */
    public $message = 'oro.wirecard.validators.sepa_only_eur_allowed';

    /**
     * {@inheritdoc}
     */
    public function validatedBy()
    {
        return PaymentMethodsConfigsRuleConstraintValidator::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
