<?php

namespace Oro\Bundle\WirecardBundle\Tests\Functional\DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Oro\Bundle\ChannelBundle\Entity\Channel;
use Oro\Bundle\CustomerBundle\Entity\CustomerUser;
use Oro\Bundle\CustomerBundle\Tests\Functional\DataFixtures\LoadCustomerUserData;
use Oro\Bundle\OrderBundle\Entity\Order;
use Oro\Bundle\OrderBundle\Tests\Functional\DataFixtures\LoadOrders;
use Oro\Bundle\PaymentBundle\Entity\PaymentTransaction;
use Oro\Bundle\PaymentBundle\Method\PaymentMethodInterface;
use Oro\Bundle\WirecardBundle\Method\WirecardSeamlessInitiateAwarePaymentMethodInterface;
use Oro\Component\Testing\Unit\EntityTrait;

class LoadPaymentTransactionData extends AbstractFixture implements DependentFixtureInterface
{
    use EntityTrait;

    const WIRECARD_INITIATE_TRANSACTION = 'wirecard_initiate_transaction';
    const WIRECARD_PURCHASE_TRANSACTION = 'wirecard_purchase_transaction';
    const WIRECARD_PURCHASE_TRANSACTION_IP_FILTER = 'wirecard_purchase_transaction_ip_filter';

    /** {@inheritdoc} */
    public function getDependencies(): array
    {
        return [
            LoadCustomerUserData::class,
            LoadWirecardSeamlessChannelData::class,
            LoadOrders::class
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager): void
    {
        foreach ($this->getData() as $reference => $data) {
            $paymentTransaction = new PaymentTransaction();

            foreach ($data as $property => $value) {
                $this->setValue($paymentTransaction, $property, $value);
            }

            $this->setReference($reference, $paymentTransaction);

            $manager->persist($paymentTransaction);
        }

        $manager->flush();
    }

    /**
     * @return array
     */
    private function getData()
    {
        /** @var CustomerUser $owner */
        $owner = $this->getReference(LoadCustomerUserData::EMAIL);

        /** @var Order $order */
        $order = $this->getReference(LoadOrders::ORDER_1);

        /** @var Channel $channel */
        $channel = $this->getReference(LoadWirecardSeamlessChannelData::WIRECARD_SEAMLESS1);

        $paymentMethod = 'wirecard_seamless_credit_card_' . $channel->getId();

        return [
            self::WIRECARD_INITIATE_TRANSACTION => [
                'amount' => '0.00',
                'currency' => 'EUR',
                'action' => WirecardSeamlessInitiateAwarePaymentMethodInterface::INITIATE,
                'entityIdentifier' => $order->getId(),
                'entityClass' => Order::class,
                'frontendOwner' => $owner,
                'response' => [
                    'storageId' => 'storageId',
                    'javascriptUrl' => 'javascriptUrl',
                ],
                'paymentMethod' => $paymentMethod,
            ],
            self::WIRECARD_PURCHASE_TRANSACTION => [
                'amount' => '1000.00',
                'currency' => 'EUR',
                'action' => PaymentMethodInterface::PURCHASE,
                'entityIdentifier' => $order->getId(),
                'entityClass' => Order::class,
                'frontendOwner' => $owner,
                'response' => [],
                'paymentMethod' => $paymentMethod,
            ],
            self::WIRECARD_PURCHASE_TRANSACTION_IP_FILTER => [
                'amount' => '1000.00',
                'currency' => 'EUR',
                'action' => PaymentMethodInterface::PURCHASE,
                'entityIdentifier' => $order->getId(),
                'entityClass' => Order::class,
                'frontendOwner' => $owner,
                'response' => [],
                'paymentMethod' => $paymentMethod,
            ],
        ];
    }
}
