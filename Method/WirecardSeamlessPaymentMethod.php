<?php

namespace Oro\Bundle\WirecardBundle\Method;

use Hochstrasser\Wirecard\Model\Seamless\Frontend\InitPaymentResult;
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
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

abstract class WirecardSeamlessPaymentMethod implements PaymentMethodInterface, WirecardSeamlessPaymentMethodInterface
{
    const ZERO = 0;
    const CURRENCY_EUR = 'EUR';

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

    public function __construct(
        WirecardSeamlessConfigInterface $config,
        PaymentTransactionProvider $transactionProvider,
        Gateway $gateway,
        RouterInterface $router
    ) {
        $this->config = $config;
        $this->transactionProvider = $transactionProvider;
        $this->gateway = $gateway;
        $this->router = $router;
    }

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
            ->setAmount(static::ZERO)
            ->setCurrency(static::CURRENCY_EUR);

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

    protected function getInitiateOptions(PaymentTransaction $paymentTransaction)
    {
        return array_merge(
            $this->config->getCredentials(),
            [
                Option\Language::LANGUAGE => $this->config->getLanguageCode(),
                Option\OrderIdent::ORDERIDENT => $paymentTransaction->getAccessIdentifier(),
                Option\ReturnUrl::RETURNURL => $this->createReturnUrl($paymentTransaction),
                Option\TestMode::TESTMODE => $this->config->isTestMode(),
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
        $initiateTransaction =
            $this->transactionProvider->getActiveInitiatePaymentTransaction($paymentTransaction->getPaymentMethod());
        if (!$initiateTransaction) {
            return [];
        }

        $initiateData = array_merge(
            $initiateTransaction->getRequest(),
            $initiateTransaction->getResponse()
        );

        $options = $this->getInitPaymentOptions($initiateData, $paymentTransaction);
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

    protected function getInitPaymentOptions(
        array $initiateData,
        PaymentTransaction $paymentTransaction
    ) {
        return array_merge(
            $this->config->getCredentials(),
            [
                Option\Language::LANGUAGE => $this->config->getLanguageCode(),

                Option\OrderIdent::ORDERIDENT => $initiateData[Option\OrderIdent::ORDERIDENT],
                Option\StorageId::STORAGEID => $initiateData[Option\StorageId::STORAGEID],

                Option\PaymentType::PAYMENTTYPE => $this->getWirecardPaymentType(),
                Option\Amount::AMOUNT => $paymentTransaction->getAmount(),
                Option\Currency::CURRENCY => $paymentTransaction->getCurrency(),

                Option\SuccessUrl::SUCCESSURL => $this->createSuccessUrl($paymentTransaction),
                Option\CancelUrl::CANCELURL => $this->createFailureUrl($paymentTransaction),
                Option\FailureUrl::FAILUREURL => $this->createFailureUrl($paymentTransaction),
                Option\ConfirmUrl::CONFIRMURL => $this->createConfirmUrl($paymentTransaction),
                Option\ServiceUrl::SERVICEURL => $this->createServiceUrl(),

                Option\ConsumerUserAgent::CONSUMERUSERAGENT => $this->gateway->getUserAgent(),
                Option\ConsumerIpAddress::CONSUMERIPADDRESS => $this->gateway->getClientIp(),

                Option\TestMode::TESTMODE => $this->config->isTestMode(),
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
        $returnURL = $this->router->generate(
            'oro_frontend_root',
            [],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        return $returnURL;
    }
}
