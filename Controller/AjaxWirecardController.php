<?php

namespace Oro\Bundle\WirecardBundle\Controller;

use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Oro\Bundle\WirecardBundle\Method\WirecardSeamlessPaymentMethod;
use Oro\Bundle\CheckoutBundle\Entity\Checkout;
use Oro\Bundle\PaymentBundle\Method\PaymentMethodInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class AjaxWirecardController extends Controller
{
    /**
     * @Route(
     *      "/seamless/initiate/{id}/{paymentMethod}",
     *      name="oro_wirecard_seamless_initiate",
     *      requirements={"id"="\d+"}
     * )
     * @AclAncestor("oro_checkout_frontend_checkout")
     *
     * @param Checkout $checkout
     * @param PaymentMethodInterface $paymentMethod
     * @return JsonResponse
     *
     */
    public function initiateAction(Checkout $checkout, PaymentMethodInterface $paymentMethod)
    {
        $paymentTransactionProvider = $this->get('oro_wirecard.provider.payment_transaction');
        $initiatePaymentTransaction =
            $paymentTransactionProvider->getActiveInitiatePaymentTransaction($paymentMethod->getIdentifier());
        if (!$initiatePaymentTransaction) {
            $initiatePaymentTransaction = $paymentTransactionProvider->createPaymentTransaction(
                $paymentMethod->getIdentifier(),
                WirecardSeamlessPaymentMethod::INITIATE,
                $checkout
            );
        }
        $initiatePaymentTransaction->setEntityIdentifier($checkout->getId());

        $paymentMethod->execute(WirecardSeamlessPaymentMethod::INITIATE, $initiatePaymentTransaction);
        $paymentTransactionProvider->savePaymentTransaction($initiatePaymentTransaction);

        return new JsonResponse($initiatePaymentTransaction->getResponse());
    }
}
