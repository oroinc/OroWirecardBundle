<?php

namespace Oro\Bundle\WirecardBundle\Controller\Frontend;

use Oro\Bundle\CheckoutBundle\Entity\Checkout;
use Oro\Bundle\PaymentBundle\Method\PaymentMethodInterface;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;
use Oro\Bundle\SecurityBundle\Annotation\CsrfProtection;
use Oro\Bundle\WirecardBundle\Method\WirecardSeamlessInitiateAwarePaymentMethodInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Ajax Wirecard Controller
 */
class AjaxWirecardController extends Controller
{
    /**
     * @Route(
     *      "/seamless/initiate/{id}/{paymentMethod}",
     *      name="oro_wirecard_frontend_seamless_initiate",
     *      requirements={"id"="\d+"}
     * )
     * @AclAncestor("oro_checkout_frontend_checkout")
     * @Method("POST")
     * @CsrfProtection()
     *
     * @param Checkout $checkout
     * @param PaymentMethodInterface $paymentMethod
     * @return JsonResponse
     */
    public function initiateAction(Checkout $checkout, PaymentMethodInterface $paymentMethod)
    {
        if (!is_a($paymentMethod, WirecardSeamlessInitiateAwarePaymentMethodInterface::class) ||
            !$paymentMethod->supports(WirecardSeamlessInitiateAwarePaymentMethodInterface::INITIATE)
        ) {
            // Only wirecard payment methods are allowed
            throw new AccessDeniedHttpException();
        }

        $paymentTransactionProvider = $this->get('oro_wirecard.provider.payment_transaction');
        $initiatePaymentTransaction = $paymentTransactionProvider->getActiveInitiatePaymentTransaction(
            $checkout,
            $paymentMethod->getIdentifier()
        );

        if (!$initiatePaymentTransaction) {
            $initiatePaymentTransaction = $paymentTransactionProvider->createPaymentTransaction(
                $paymentMethod->getIdentifier(),
                WirecardSeamlessInitiateAwarePaymentMethodInterface::INITIATE,
                $checkout
            );
        }

        $paymentMethod->execute(
            WirecardSeamlessInitiateAwarePaymentMethodInterface::INITIATE,
            $initiatePaymentTransaction
        );

        $paymentTransactionProvider->savePaymentTransaction($initiatePaymentTransaction);

        return new JsonResponse($initiatePaymentTransaction->getResponse());
    }
}
