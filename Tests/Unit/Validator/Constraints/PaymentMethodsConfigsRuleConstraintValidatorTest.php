<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Validator\Constraints;

use Oro\Bundle\PaymentBundle\Entity\PaymentMethodConfig;
use Oro\Bundle\PaymentBundle\Entity\PaymentMethodsConfigsRule;
use Oro\Bundle\PaymentBundle\Form\Type\PaymentMethodsConfigsRuleType;
use Oro\Bundle\PaymentBundle\Method\Config\PaymentConfigInterface;
use Oro\Bundle\WirecardBundle\Method\Config\Provider\WirecardSeamlessSepaConfigProvider;
use Oro\Bundle\WirecardBundle\Validator\Constraints\PaymentMethodsConfigsRuleConstraint;
use Oro\Bundle\WirecardBundle\Validator\Constraints\PaymentMethodsConfigsRuleConstraintValidator;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

class PaymentMethodsConfigsRuleConstraintValidatorTest extends \PHPUnit\Framework\TestCase
{
    const SEPA_ID = 'sepa';

    /** @var WirecardSeamlessSepaConfigProvider|\PHPUnit\Framework\MockObject\MockObject */
    protected $provider;

    /** @var ExecutionContextInterface|\PHPUnit\Framework\MockObject\MockObject */
    protected $context;

    /** @var PaymentMethodsConfigsRuleConstraintValidator */
    protected $validator;

    /** @var PaymentMethodsConfigsRuleConstraint */
    protected $constraint;

    /** @var ConstraintViolationBuilderInterface|\PHPUnit\Framework\MockObject\MockObject */
    protected $builder;

    public function setUp()
    {
        $this->provider = $this->createMock(WirecardSeamlessSepaConfigProvider::class);
        $this->context = $this->createMock(ExecutionContextInterface::class);
        $this->constraint = new PaymentMethodsConfigsRuleConstraint();

        $this->builder = $this->createMock(ConstraintViolationBuilderInterface::class);
        $this->builder->expects($this->any())->method('addViolation');

        $this->validator = new PaymentMethodsConfigsRuleConstraintValidator($this->provider);
        $this->validator->initialize($this->context);
    }

    /**
     * @expectedException \Symfony\Component\Validator\Exception\UnexpectedTypeException
     */
    public function testValidateWrongRule()
    {
        $this->validator->validate(new \stdClass(), $this->constraint);
    }

    public function testValidate()
    {
        $this->context->expects($this->once())
            ->method('buildViolation')
            ->with($this->constraint->message)
            ->willReturn($this->builder);

        $config1 = $this->createMock(PaymentConfigInterface::class);
        $config1->expects($this->once())->method('getPaymentMethodIdentifier')->willReturn('some_other_method');
        $config2 = $this->createMock(PaymentConfigInterface::class);
        $config2->expects($this->once())->method('getPaymentMethodIdentifier')->willReturn(self::SEPA_ID);
        $this->provider->expects($this->once())
            ->method('getPaymentConfigs')
            ->willReturn([$config1, $config2]);

        $rule = new PaymentMethodsConfigsRule();
        $rule->addMethodConfig((new PaymentMethodConfig())->setType(self::SEPA_ID));
        $rule->setCurrency('USD');

        $this->validator->validate($rule, $this->constraint);
    }

    public function testValidateSuccessAnotherMethod()
    {
        $this->context->expects($this->never())->method('buildViolation');

        $config = $this->createMock(PaymentConfigInterface::class);
        $config->expects($this->once())->method('getPaymentMethodIdentifier')->willReturn(self::SEPA_ID);
        $this->provider->expects($this->once())
            ->method('getPaymentConfigs')
            ->willReturn([$config]);

        $rule = new PaymentMethodsConfigsRule();
        $rule->addMethodConfig((new PaymentMethodConfig())->setType('some_other_id'));
        $rule->setCurrency('USD');

        $this->validator->validate($rule, $this->constraint);
    }

    public function testValidateSuccessCorrectCurrency()
    {
        $this->context->expects($this->never())->method('buildViolation');

        $this->provider->expects($this->never())
            ->method('getPaymentConfigs');

        $rule = new PaymentMethodsConfigsRule();
        $rule->addMethodConfig((new PaymentMethodConfig())->setType(self::SEPA_ID));
        $rule->setCurrency('EUR');

        $this->validator->validate($rule, $this->constraint);
    }
}
