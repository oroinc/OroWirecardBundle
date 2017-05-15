<?php

namespace Oro\Bundle\WirecardBundle\Method;

use Hochstrasser\Wirecard\Model\Seamless\Frontend\InitPaymentResult;
use Oro\Bundle\CheckoutBundle\Entity\Checkout;
use Oro\Bundle\EntityBundle\ORM\DoctrineHelper;
use Oro\Bundle\OrderBundle\Entity\Order;
use Oro\Bundle\PaymentBundle\Context\PaymentContextInterface;
use Oro\Bundle\PaymentBundle\Entity\PaymentTransaction;
use Oro\Bundle\PaymentBundle\Method\PaymentMethodInterface;
use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessConfigInterface;
use Oro\Bundle\WirecardBundle\Provider\PaymentTransactionProvider;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Gateway;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Request\InitDataStorageRequest;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Request\InitPaymentRequest;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Response\Response;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

abstract class WirecardSeamlessPaymentMethod implements PaymentMethodInterface
{
    const ZERO_AMOUNT = 0;
    const EMPTY_CURRENCY = '';

    const INITIATE = 'initiate';
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
     * @var Gateway
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
     * @param Gateway $gateway
     * @param RouterInterface $router
     * @param DoctrineHelper $doctrineHelper
     * @param RequestStack $requestStack
     */
    public function __construct(
        WirecardSeamlessConfigInterface $config,
        PaymentTransactionProvider $transactionProvider,
        Gateway $gateway,
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
     */
    public function initiate(PaymentTransaction $paymentTransaction)
    {
        $paymentTransaction
            ->setAmount(static::ZERO_AMOUNT)
            ->setCurrency(static::EMPTY_CURRENCY);

        $options = $this->getInitiateOptions($paymentTransaction);
        $request = new InitDataStorageRequest();
        $response = $this->gateway->request(
            $request,
            $options
        );

        unset($options[Option\Secret::SECRET]);

        $paymentTransaction
            ->setRequest($options)
            ->setResponse($response->toArray())
            ->setActive(true);
    }

    /**
     * @param PaymentTransaction $paymentTransaction
     * @return array
     */
    protected function getInitiateOptions(PaymentTransaction $paymentTransaction)
    {
        return array_merge(
            $this->config->getCredentials(),
            [
                Option\Language::LANGUAGE => $this->config->getLanguageCode(),
                Option\OrderIdent::ORDERIDENT => $paymentTransaction->getAccessIdentifier(),
                Option\ReturnUrl::RETURNURL => $this->createReturnUrl($paymentTransaction),
            ]
        );
    }

    /**
     * @param PaymentTransaction $paymentTransaction
     *
     * @return array
     */
    public function purchase(PaymentTransaction $paymentTransaction)
    {
        $transactionOptions = $paymentTransaction->getTransactionOptions();
        if (!isset($transactionOptions['checkoutId'])) {
            throw new \RuntimeException('Checkout Id not set');
        }
        $object = $this->doctrineHelper->getEntityReference(
            Checkout::class,
            $transactionOptions['checkoutId']
        );

        $initiateTransaction = $this->transactionProvider->getActiveInitiatePaymentTransaction(
            $object,
            $paymentTransaction->getPaymentMethod()
        );
        if (!$initiateTransaction) {
            throw new \RuntimeException('Initiate payment transaction not found');
        }

        $options = $this->getInitPaymentOptions($initiateTransaction, $paymentTransaction);
        $request = new InitPaymentRequest();
        $response = $this->gateway->request(
            $request,
            $options
        );

        unset($options[Option\Secret::SECRET]);

        $initiateTransaction->setActive(false);

        $paymentTransaction
            ->setRequest($options)
            ->setResponse($response->toArray());

        /** @var InitPaymentResult $initPaymentResponse */
        $initPaymentResponse = $response->toObject();

        return $initPaymentResponse ? ['redirectTo' => $initPaymentResponse->getRedirectUrl()] : [];
    }

    /**
     * @param PaymentTransaction $initiateTransaction
     * @param PaymentTransaction $paymentTransaction
     * @return array
     */
    protected function getInitPaymentOptions(
        PaymentTransaction $initiateTransaction,
        PaymentTransaction $paymentTransaction
    ) {
        return array_merge(
            $this->config->getCredentials(),
            [
                Option\Language::LANGUAGE => $this->config->getLanguageCode(),

                Option\OrderIdent::ORDERIDENT =>
                    $initiateTransaction->getRequest()[Option\OrderIdent::ORDERIDENT],
                Option\StorageId::STORAGEID =>
                    $initiateTransaction->getResponse()[Option\StorageId::STORAGEID],

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
            ]
        );
    }

    /**
     * @param PaymentTransaction $paymentTransaction
     *
     * @throws \InvalidArgumentException
     */
    public function complete(PaymentTransaction $paymentTransaction)
    {
        $response = new Response($paymentTransaction->getResponse());
        if ($response->isSuccessful()) {
            $fingerprintCheck = $response->checkFingerprint(
                array_merge(
                    $this->config->getCredentials(),
                    [
                        Option\Hashing::HASHING => $this->config->getHashingMethod(),
                    ]
                )
            );
            if (!$fingerprintCheck) {
                throw new \InvalidArgumentException('Response fingerprint mismatch');
            }
            $paymentTransaction
                ->setReference($response->getOrderNumber());
        }

        $paymentTransaction
            ->setActive($response->isSuccessful())
            ->setSuccessful($response->isSuccessful());
    }

    /**
     * @param string $actionName
     * @return bool
     */
    public function supports($actionName)
    {
        return in_array(
            $actionName,
            [self::INITIATE, self::PURCHASE, self::COMPLETE],
            true
        );
    }

    /**
     * @param PaymentContextInterface $context
     *
     * @return bool
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
        $object = $this->doctrineHelper->getEntityReference(
            $paymentTransaction->getEntityClass(),
            $paymentTransaction->getEntityIdentifier()
        );
        $orderDescription = $paymentTransaction->getEntityIdentifier();
        if ($object instanceof Order) {
            $orderDescription = $object->getIdentifier();
        }
        return $orderDescription;
    }
}
