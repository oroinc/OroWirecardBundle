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
    /**
     * @var array Channels configuration
     */
    protected $channelData = [
        [
            'name' => 'Wirecard1',
            'type' => 'wirecard_seamless',
            'enabled' => true,
            'reference' => 'wirecard:channel_1',
        ],
        [
            'name' => 'Wirecard2',
            'type' => 'wirecard_seamless',
            'enabled' => false,
            'reference' => 'wirecard:channel_2',
        ],
        [
            'name' => 'Wirecard3',
            'type' => 'wirecard_seamless',
            'enabled' => true,
            'reference' => 'wirecard:channel_3',
        ],
        [
            'name' => 'Wirecard4',
            'type' => 'wirecard_seamless',
            'enabled' => true,
            'reference' => 'wirecard:channel_4',
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

        foreach ($this->channelData as $data) {
            $entity = new Channel();
            $entity->setName($data['name']);
            $entity->setType($data['type']);
            $entity->setEnabled($data['enabled']);
            $entity->setDefaultUserOwner($admin);
            $entity->setOrganization($organization);
            $entity->setTransport(new WirecardSeamlessSettings());
            $this->setReference($data['reference'], $entity);

            $manager->persist($entity);
        }
        $manager->flush();
    }
}
