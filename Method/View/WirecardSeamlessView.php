<?php

namespace Oro\Bundle\WirecardBundle\Method\View;

use Oro\Bundle\PaymentBundle\Context\PaymentContextInterface;
use Oro\Bundle\PaymentBundle\Method\View\PaymentMethodViewInterface;
use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessConfigInterface;
use Symfony\Component\Form\FormFactoryInterface;

abstract class WirecardSeamlessView implements PaymentMethodViewInterface
{
    const INITIATE_ROUTE = 'oro_wirecard_frontend_seamless_initiate';

    /**
     * @var FormFactoryInterface
     */
    protected $formFactory;

    /**
     * @var WirecardSeamlessConfigInterface
     */
    protected $config;

    /**
     * @param FormFactoryInterface $formFactory
     * @param WirecardSeamlessConfigInterface $config
     */
    public function __construct(FormFactoryInterface $formFactory, WirecardSeamlessConfigInterface $config)
    {
        $this->config = $config;
        $this->formFactory = $formFactory;
    }

    /**
     * @return string|null
     */
    abstract public function getFormTypeClass();

    /**
     * @param PaymentContextInterface $context
     *
     * @return array
     */
    public function getOptions(PaymentContextInterface $context)
    {
        $formTypeClass = $this->getFormTypeClass();
        $formView = null;
        if ($formTypeClass !== null) {
            $formView = $this->formFactory->create($formTypeClass)->createView();
        }

        $viewOptions = [
            'formView' => $formView,
            'paymentMethod' => $this->config->getPaymentMethodIdentifier(),
            'sourceEntityId' => $context->getSourceEntityIdentifier(),
            'initiatePaymentMethodRoute' => static::INITIATE_ROUTE,
        ];

        return $viewOptions;
    }

    /**
     * {@inheritdoc}
     */
    public function getLabel()
    {
        return $this->config->getLabel();
    }

    /**
     * {@inheritdoc}
     */
    public function getAdminLabel()
    {
        return $this->config->getAdminLabel();
    }

    /**
     * {@inheritdoc}
     */
    public function getShortLabel()
    {
        return $this->config->getShortLabel();
    }

    /**
     * {@inheritdoc}
     */
    public function getPaymentMethodIdentifier()
    {
        return $this->config->getPaymentMethodIdentifier();
    }
}
