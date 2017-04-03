<?php

namespace Oro\Bundle\WirecardBundle\Method\View\Factory;

use Symfony\Component\Form\FormFactoryInterface;

abstract class WirecardSeamlessViewFactory
{
    /** @var FormFactoryInterface */
    protected $formFactory;

    public function __construct(
        FormFactoryInterface $formFactory
    ) {
        $this->formFactory = $formFactory;
    }
}
