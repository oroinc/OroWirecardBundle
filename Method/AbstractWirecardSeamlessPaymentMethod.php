<?php

namespace Oro\Bundle\WirecardBundle\Method;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

use Oro\Bundle\EntityBundle\ORM\DoctrineHelper;
use Oro\Bundle\PaymentBundle\Context\PaymentContextInterface;
use Oro\Bundle\PaymentBundle\Entity\PaymentTransaction;
use Oro\Bundle\PaymentBundle\Method\PaymentMethodInterface;
use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessConfigInterface;
use Oro\Bundle\WirecardBundle\Provider\PaymentTransactionProvider;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\GatewayInterface;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Request\RequestInterface;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Response\Response;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Response\ResponseInterface;

abstract class AbstractWirecardSeamlessPaymentMethod implements PaymentMethodInterface
{
    const COMPLETE = 'complete';

    /**
     * @var WirecardSeamlessConfigInterface
     */
    protected $config;

    /**
     * @var PaymentTransactionProvider
     */
    protected $transactionProvider;

    /**
     * @var GatewayInterface
     */
    protected $gateway;

    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * @var DoctrineHelper
     */
    protected $doctrineHelper;

    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * @param WirecardSeamlessConfigInterface $config
     * @param PaymentTransactionProvider $transactionProvider
     * @param GatewayInterface $gateway
     * @param RouterInterface $router
     * @param DoctrineHelper $doctrineHelper
     * @param RequestStack $requestStack
     */
    public function __construct(
        WirecardSeamlessConfigInterface $config,
        PaymentTransactionProvider $transactionProvider,
        GatewayInterface $gateway,
        RouterInterface $router,
        DoctrineHelper $doctrineHelper,
        RequestStack $requestStack
    ) {
        $this->config = $config;
        $this->transactionProvider = $transactionProvider;
        $this->gateway = $gateway;
        $this->router = $router;
        $this->doctrineHelper = $doctrineHelper;
        $this->requestStack = $requestStack;
    }

    /**
     * @return string
     */
    abstract public function getWirecardPaymentType();

    /** {@inheritdoc} */
    public function execute($action, PaymentTransaction $paymentTransaction)
    {
        if (!$this->supports($action)) {
            throw new \InvalidArgumentException(sprintf('Unsupported action "%s"', $action));
        }

        return $this->{$action}($paymentTransaction) ?: [];
    }

    /**
     * @param PaymentTransaction $paymentTransaction
     *
     * @return array
     */
    abstract public function purchase(PaymentTransaction $paymentTransaction);

    /**
     * @param PaymentTransaction $paymentTransaction
     *
     * @throws \RuntimeException
     */
    public function complete(PaymentTransaction $paymentTransaction)
    {
        $response = new Response($paymentTransaction->getResponse());

        if ($response->isSuccessful()) {
            $paymentTransaction
                ->setReference($response->getOrderNumber());
        }

        $paymentTransaction
            ->setActive($response->isSuccessful())
            ->setSuccessful($response->isSuccessful());
    }

    /**
     * @param RequestInterface $request
     * @param array $options
     * @return ResponseInterface
     */
    protected function doRequest(RequestInterface $request, array $options)
    {
        return $this->gateway->request($request, array_merge($this->config->getCredentials(), $options));
    }

    /**
     * {@inheritdoc}
     */
    public function supports($actionName)
    {
        return in_array($actionName, [self::PURCHASE, self::COMPLETE], true);
    }

    /**
     * {@inheritdoc}
     */
    public function isApplicable(PaymentContextInterface $context)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentifier()
    {
        return $this->config->getPaymentMethodIdentifier();
    }

    /**
     * @param PaymentTransaction $paymentTransaction
     *
     * @return string
     */
    protected function createConfirmUrl(PaymentTransaction $paymentTransaction)
    {
        return
            $this->router->generate(
                'oro_payment_callback_notify',
                [
                    'accessIdentifier' => $paymentTransaction->getAccessIdentifier(),
                    'accessToken' => $paymentTransaction->getAccessToken(),
                ],
                UrlGeneratorInterface::ABSOLUTE_URL
            );
    }

    /**
     * @param PaymentTransaction $paymentTransaction
     *
     * @return string
     */
    protected function createSuccessUrl(PaymentTransaction $paymentTransaction)
    {
        return
            $this->router->generate(
                'oro_payment_callback_return',
                [
                    'accessIdentifier' => $paymentTransaction->getAccessIdentifier(),
                ],
                UrlGeneratorInterface::ABSOLUTE_URL
            );
    }

    /**
     * @param PaymentTransaction $paymentTransaction
     *
     * @return string
     */
    protected function createFailureUrl(PaymentTransaction $paymentTransaction)
    {
        return
            $this->router->generate(
                'oro_payment_callback_error',
                [
                    'accessIdentifier' => $paymentTransaction->getAccessIdentifier(),
                ],
                UrlGeneratorInterface::ABSOLUTE_URL
            );
    }

    /**
     * @param PaymentTransaction $paymentTransaction
     *
     * @return string
     */
    protected function createReturnUrl(PaymentTransaction $paymentTransaction)
    {
        // TODO: BB-9385 need to correctly handle this case
        $returnURL = $this->router->generate(
            'oro_checkout_frontend_checkout',
            [
                'id' => $paymentTransaction->getEntityIdentifier(),
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        return $returnURL;
    }

    /**
     * FIXME: how to go to about or contact pages??
     *
     * @return string
     */
    protected function createServiceUrl()
    {
        $serviceURL = $this->router->generate(
            'oro_frontend_root',
            [],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        return $serviceURL;
    }

    /**
     * @return array|string
     */
    protected function getUserAgent()
    {
        return $this->requestStack->getMasterRequest()->headers->get('User-Agent');
    }

    /**
     * @return string
     */
    protected function getClientIp()
    {
        return $this->requestStack->getMasterRequest()->getClientIp();
    }

    /**
     * @param PaymentTransaction $paymentTransaction
     * @return string
     */
    protected function getOrderDescription(PaymentTransaction $paymentTransaction)
    {
        $transactionOptions = $paymentTransaction->getTransactionOptions();

        $description = '';
        if (array_key_exists('checkoutId', $transactionOptions)) {
            $description .= sprintf('Checkout: %d', $transactionOptions['checkoutId']);
        }

        return $description;
    }

    /**
     * @param PaymentTransaction $paymentTransaction
     * @return array
     */
    protected function getBaseOptions(PaymentTransaction $paymentTransaction)
    {
        return [
            Option\Language::LANGUAGE => $this->config->getLanguageCode(),
            Option\PaymentType::PAYMENTTYPE => $this->getWirecardPaymentType(),
            Option\Amount::AMOUNT => $paymentTransaction->getAmount(),
            Option\Currency::CURRENCY => $paymentTransaction->getCurrency(),
            Option\OrderDescription::ORDERDESCRIPTION => $this->getOrderDescription($paymentTransaction),
            Option\SuccessUrl::SUCCESSURL => $this->createSuccessUrl($paymentTransaction),
            Option\CancelUrl::CANCELURL => $this->createFailureUrl($paymentTransaction),
            Option\FailureUrl::FAILUREURL => $this->createFailureUrl($paymentTransaction),
            Option\ConfirmUrl::CONFIRMURL => $this->createConfirmUrl($paymentTransaction),
            Option\ServiceUrl::SERVICEURL => $this->createServiceUrl(),
            Option\ConsumerUserAgent::CONSUMERUSERAGENT => $this->getUserAgent(),
            Option\ConsumerIpAddress::CONSUMERIPADDRESS => $this->getClientIp(),
        ];
    }
}
