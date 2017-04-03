<?php

namespace Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option;

use Symfony\Component\OptionsResolver\OptionsResolver;

class StorageId extends AbstractOption
{
    const STORAGEID = 'storageId';

    /** @var bool */
    protected $required;

    /** @param bool $required */
    public function __construct($required = true)
    {
        $this->required = $required;
    }

    /** {@inheritdoc} */
    public function configureOption(OptionsResolver $resolver)
    {
        if ($this->required) {
            $resolver
                ->setRequired(self::STORAGEID);
        }

        $resolver
            ->setDefined(self::STORAGEID)
            ->addAllowedTypes(self::STORAGEID, 'string');
    }
}
