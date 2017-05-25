<?php

namespace Oro\Bundle\WirecardBundle\Tests\Functional\DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Oro\Bundle\WirecardBundle\Method\WirecardSeamlessInitiateAwarePaymentMethodInterface;
use Oro\Component\Testing\Unit\EntityTrait;
use Oro\Bundle\PaymentBundle\Entity\PaymentTransaction;
use Oro\Bundle\PaymentBundle\Method\PaymentMethodInterface;
use Oro\Bundle\CustomerBundle\Tests\Functional\DataFixtures\LoadCustomerUserData;

class LoadPaymentTransactionData extends AbstractFixture implements DependentFixtureInterface
{
    use EntityTrait;

    const WIRECARD_INITIATE_TRANSACTION = 'wirecard_initiate_transaction';
    const WIRECARD_PURCHASE_TRANSACTION = 'wirecard_purchase_transaction';
    const WIRECARD_PURCHASE_TRANSACTION_IP_FILTER = 'wirecard_purchase_transaction_ip_filter';

    /** {@inheritdoc} */
    public function getDependencies()
    {
        return [LoadCustomerUserData::class, LoadWirecardSeamlessChannelData::class];
    }

    /**
     * @var array
     */
    protected $data = [
        self::WIRECARD_INITIATE_TRANSACTION => [
            'amount' => '0.00',
            'currency' => 'EUR',
            'action' => WirecardSeamlessInitiateAwarePaymentMethodInterface::INITIATE,
            'entityIdentifier' => 1,
            'entityClass' => \stdClass::class,
            'frontendOwner' => LoadCustomerUserData::EMAIL,
            'response' => [
                'storageId' => 'storageId',
                'javascriptUrl' => 'javascriptUrl',
            ],
            'channel_reference' => 'wirecard:channel_1',
            'method_prefix' => 'wirecard_seamless_credit_card',
        ],
        self::WIRECARD_PURCHASE_TRANSACTION => [
            'amount' => '1000.00',
            'currency' => 'EUR',
            'action' => PaymentMethodInterface::PURCHASE,
            'entityIdentifier' => 1,
            'entityClass' => \stdClass::class,
            'frontendOwner' => LoadCustomerUserData::EMAIL,
            'response' => [],
            'channel_reference' => 'wirecard:channel_1',
            'method_prefix' => 'wirecard_seamless_credit_card',
        ],
        self::WIRECARD_PURCHASE_TRANSACTION_IP_FILTER => [
            'amount' => '1000.00',
            'currency' => 'EUR',
            'action' => PaymentMethodInterface::PURCHASE,
            'entityIdentifier' => 1,
            'entityClass' => \stdClass::class,
            'frontendOwner' => LoadCustomerUserData::EMAIL,
            'response' => [],
            'channel_reference' => 'wirecard:channel_1',
            'method_prefix' => 'wirecard_seamless_credit_card',
        ],
    ];

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->data as $reference => $data) {
            $paymentTransaction = new PaymentTransaction();

            $data['frontendOwner'] = $this->getReference($data['frontendOwner']);

            foreach ($data as $property => $value) {
                if ($this->getPropertyAccessor()->isWritable($paymentTransaction, $property)) {
                    $this->setValue($paymentTransaction, $property, $value);
                }
            }

            $channel = $this->getReference($data['channel_reference']);
            $paymentMethod = sprintf('%s_%s', $data['method_prefix'], $channel->getId());

            $paymentTransaction->setPaymentMethod($paymentMethod);

            $this->setReference($reference, $paymentTransaction);

            $manager->persist($paymentTransaction);
        }

        $manager->flush();
    }
}
