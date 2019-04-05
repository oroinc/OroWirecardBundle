<?php

namespace Oro\Bundle\WirecardBundle\Tests\Functional\DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Oro\Bundle\IntegrationBundle\Entity\Channel;
use Oro\Bundle\UserBundle\Migrations\Data\ORM\LoadAdminUserData;
use Oro\Bundle\WirecardBundle\Entity\WirecardSeamlessSettings;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadWirecardSeamlessChannelData extends AbstractFixture implements ContainerAwareInterface
{
    public const WIRECARD_SEAMLESS1 = 'wirecard:channel_1';
    public const WIRECARD_SEAMLESS2 = 'wirecard:channel_2';
    public const WIRECARD_SEAMLESS3 = 'wirecard:channel_3';
    public const WIRECARD_SEAMLESS4 = 'wirecard:channel_4';

    /**
     * @var array Channels configuration
     */
    protected $channelData = [
        self::WIRECARD_SEAMLESS1 => [
            'name' => 'Wirecard1',
            'type' => 'wirecard_seamless',
            'enabled' => true,
        ],
        self::WIRECARD_SEAMLESS2 => [
            'name' => 'Wirecard2',
            'type' => 'wirecard_seamless',
            'enabled' => false,
        ],
        self::WIRECARD_SEAMLESS3 => [
            'name' => 'Wirecard3',
            'type' => 'wirecard_seamless',
            'enabled' => true,
        ],
        self::WIRECARD_SEAMLESS4 => [
            'name' => 'Wirecard4',
            'type' => 'wirecard_seamless',
            'enabled' => true,
        ],
    ];

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $userManager = $this->container->get('oro_user.manager');
        $admin = $userManager->findUserByEmail(LoadAdminUserData::DEFAULT_ADMIN_EMAIL);
        $organization = $manager->getRepository('OroOrganizationBundle:Organization')->getFirst();

        foreach ($this->channelData as $reference => $data) {
            $entity = new Channel();
            $entity->setName($data['name']);
            $entity->setType($data['type']);
            $entity->setEnabled($data['enabled']);
            $entity->setDefaultUserOwner($admin);
            $entity->setOrganization($organization);
            $entity->setTransport(new WirecardSeamlessSettings());
            $this->setReference($reference, $entity);

            $manager->persist($entity);
        }
        $manager->flush();
    }
}
