<?php

namespace Oro\Bundle\WirecardBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Oro\Bundle\WirecardBundle\Entity\WirecardSeamlessSettings;

class WirecardSeamlessSettingsRepository extends EntityRepository
{
    /**
     * @param string $type
     *
     * @return WirecardSeamlessSettings[]
     */
    public function getEnabledSettingsByType($type)
    {
        return $this->createQueryBuilder('settings')
            ->innerJoin('settings.channel', 'channel')
            ->andWhere('channel.enabled = true')
            ->andWhere('channel.type = :type')
            ->setParameter('type', $type)
            ->getQuery()
            ->getResult();
    }
}
