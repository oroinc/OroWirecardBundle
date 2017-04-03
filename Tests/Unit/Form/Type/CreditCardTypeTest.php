<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Form;

use Oro\Bundle\WirecardBundle\Form\Type\CreditCardExpirationDateType;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\Test\FormIntegrationTestCase;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Validator\Validation;
use Oro\Bundle\WirecardBundle\Form\Type\CreditCardType;

class CreditCardTypeTest extends FormIntegrationTestCase
{
    /**
     * @var CreditCardType
     */
    protected $formType;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->formType = new CreditCardType();
    }

    /**
     * @return array
     */
    protected function getExtensions()
    {
        return [
            new PreloadedExtension(
                [
                    CreditCardExpirationDateType::NAME => new CreditCardExpirationDateType(),
                ],
                []
            ),
            new ValidatorExtension(Validation::createValidator()),
        ];
    }

    public function testGetName()
    {
        $this->assertEquals('oro_wirecard_seamless_credit_card', $this->formType->getName());
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

        $this->formType->finishView($formView, $form, []);

        foreach ($formView->children as $formItemData) {
            $this->assertEquals('name', $formItemData->vars['full_name']);
        }
    }
}
