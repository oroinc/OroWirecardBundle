<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Form\Type;

use Oro\Bundle\WirecardBundle\Form\Type\CreditCardExpirationDateType;
use Oro\Bundle\WirecardBundle\Form\Type\CreditCardType;
use Oro\Component\Testing\Unit\PreloadedExtension;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\Test\FormIntegrationTestCase;
use Symfony\Component\Validator\Validation;

class CreditCardTypeTest extends FormIntegrationTestCase
{
    /**
     * @return array
     */
    protected function getExtensions()
    {
        return [
            new PreloadedExtension(
                [
                    CreditCardExpirationDateType::class => new CreditCardExpirationDateType(),
                ],
                []
            ),
            new ValidatorExtension(Validation::createValidator()),
        ];
    }

    public function testFinishView()
    {
        /** @var FormInterface|\PHPUnit_Framework_MockObject_MockObject $form */
        $form = $this->createMock(FormInterface::class);

        $formView = new FormView();
        $formChildrenView = new FormView();
        $formChildrenView->vars = [
            'full_name' => 'full_name',
            'name' => 'name',
        ];
        $formView->children = [$formChildrenView];

        $formType = new CreditCardType();
        $formType->finishView($formView, $form, []);

        foreach ($formView->children as $formItemData) {
            $this->assertEquals('name', $formItemData->vars['full_name']);
        }
    }

    public function testFormConfiguration()
    {
        $form = $this->factory->create(CreditCardType::class);

        $this->assertTrue($form->has('cardholderName'));
        $this->assertTrue($form->has('creditCardNumber'));
        $this->assertTrue($form->has('expirationDate'));
        $this->assertTrue($form->has('cvv'));

        $cvcInnerType = $form->get('cvv')->getConfig()->getType()->getInnerType();

        $this->assertInstanceOf(PasswordType::class, $cvcInnerType);
    }
}
