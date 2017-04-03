<?php

namespace Oro\Bundle\WirecardBundle\Integration;

use Oro\Bundle\IntegrationBundle\Provider\ChannelInterface;
use Oro\Bundle\IntegrationBundle\Provider\IconAwareIntegrationInterface;

class WirecardSeamlessChannelType implements ChannelInterface, IconAwareIntegrationInterface
{
    const TYPE = 'wirecard_seamless';

    /**
     * {@inheritdoc}
     */
    public function getLabel()
    {
        return 'oro.wirecard.channel_type.wirecard_seamless.label';
    }

    /**
     * {@inheritdoc}
     */
    public function getIcon()
    {
        return 'bundles/orowirecard/img/wirecard-logo.png';
    }
}
