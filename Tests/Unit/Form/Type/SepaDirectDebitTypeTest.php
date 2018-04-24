<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Form\Type;

use Oro\Bundle\WirecardBundle\Form\Type\SepaDirectDebitType;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\Test\FormIntegrationTestCase;
use Symfony\Component\Validator\Validation;

class SepaDirectDebitTypeTest extends FormIntegrationTestCase
{
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

        $formType = new SepaDirectDebitType();
        $formType->finishView($formView, $form, []);

        foreach ($formView->children as $formItemData) {
            $this->assertEquals('name', $formItemData->vars['full_name']);
        }
    }

    public function testFormConfiguration()
    {
        $form = $this->factory->create(SepaDirectDebitType::class);

        $this->assertTrue($form->has('accountOwner'));
        $this->assertTrue($form->has('bankIban'));
        $this->assertTrue($form->has('bankBic'));
    }

    /**
     * @return array
     */
    protected function getExtensions()
    {
        return [
            new ValidatorExtension(Validation::createValidator()),
        ];
    }
}
