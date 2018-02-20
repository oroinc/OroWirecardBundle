<?php

namespace Oro\Bundle\WirecardBundle\Tests\Functional\DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Oro\Bundle\CheckoutBundle\Entity\Checkout;
use Oro\Bundle\CheckoutBundle\Entity\CheckoutSource;
use Oro\Bundle\CustomerBundle\Tests\Functional\DataFixtures\LoadCustomerUserData;
use Oro\Component\Testing\Unit\EntityTrait;

class LoadCheckoutData extends AbstractFixture implements DependentFixtureInterface
{
    use EntityTrait;


    /** {@inheritdoc} */
    public function getDependencies()
    {
        return [LoadCustomerUserData::class];
    }

    /**
     * @var array
     */
    protected $checkoutData = [
        [
            'name' => 'checkout1',
            'customerUserRef' => LoadCustomerUserData::EMAIL,
            'reference' => 'wirecard:checkout_1',
        ],
    ];

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->checkoutData as $data) {
            $entity = new Checkout();
            $entity->setSource(new CheckoutSource());
            $entity->setCustomerUser($this->getReference($data['customerUserRef']));
            $this->setReference($data['reference'], $entity);

            $manager->persist($entity);
        }

        $manager->flush();
    }
}
