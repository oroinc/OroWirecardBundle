<?php

namespace Oro\Bundle\WirecardBundle\Tests\Functional\DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Oro\Bundle\CheckoutBundle\Entity\Checkout;
use Oro\Bundle\CheckoutBundle\Entity\CheckoutSource;
use Oro\Bundle\CustomerBundle\Tests\Functional\Api\Frontend\DataFixtures\LoadWebsiteData;
use Oro\Bundle\CustomerBundle\Tests\Functional\DataFixtures\LoadCustomerUserData;
use Oro\Bundle\TestFrameworkBundle\Tests\Functional\DataFixtures\LoadOrganization;
use Oro\Component\Testing\Unit\EntityTrait;

class LoadCheckoutData extends AbstractFixture implements DependentFixtureInterface
{
    use EntityTrait;

    private const CHECKOUT_DATA = [
        [
            'name' => 'checkout1',
            'customerUserReference' => LoadCustomerUserData::EMAIL,
            'reference' => 'wirecard:checkout_1',
            'organizationReference' => 'organization',
            'websiteReference' => 'website',
        ],
    ];

    /**
     * {@inheritdoc}
     */
    public function getDependencies(): array
    {
        return [
            LoadCustomerUserData::class,
            LoadOrganization::class,
            LoadWebsiteData::class
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager): void
    {
        foreach (self::CHECKOUT_DATA as $data) {
            $entity = new Checkout();
            $entity->setSource(new CheckoutSource());
            $entity->setCustomerUser($this->getReference($data['customerUserReference']));
            $entity->setOrganization($this->getReference($data['organizationReference']));
            $entity->setWebsite($this->getReference($data['websiteReference']));
            $this->setReference($data['reference'], $entity);

            $manager->persist($entity);
        }

        $manager->flush();
    }
}
