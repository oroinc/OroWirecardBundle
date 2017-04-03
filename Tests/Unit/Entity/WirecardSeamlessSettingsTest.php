<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Entity;

use Oro\Bundle\LocaleBundle\Entity\LocalizedFallbackValue;
use Oro\Bundle\WirecardBundle\Entity\WirecardSeamlessSettings;
use Oro\Component\Testing\Unit\EntityTestCaseTrait;
use Oro\Component\Testing\Unit\EntityTrait;
use Symfony\Component\HttpFoundation\ParameterBag;

class WirecardSeamlessSettingsTest extends \PHPUnit_Framework_TestCase
{
    use EntityTestCaseTrait;
    use EntityTrait;

    public function testAccessors()
    {
        static::assertPropertyAccessors(new WirecardSeamlessSettings(), [
            ['customerId', 'some string'],
            ['shopId', 'some string'],
            ['secret', 'some string'],
            ['testMode', false],
        ]);
        static::assertPropertyCollections(new WirecardSeamlessSettings(), [
            ['creditCardLabels', new LocalizedFallbackValue()],
            ['creditCardShortLabels', new LocalizedFallbackValue()],
            ['paypalLabels', new LocalizedFallbackValue()],
            ['paypalShortLabels', new LocalizedFallbackValue()],
            ['sepaLabels', new LocalizedFallbackValue()],
            ['sepaShortLabels', new LocalizedFallbackValue()],
        ]);
    }

    public function testGetSettingsBag()
    {
        /** @var WirecardSeamlessSettings $entity */
        $entity = $this->getEntity(
            WirecardSeamlessSettings::class,
            [
                'customerId' => 'some customerId',
                'shopId' => 'some shopId',
                'secret' => 'some secret',
                'testMode' => false,
                'creditCardLabels' => [(new LocalizedFallbackValue())->setString('label')],
                'creditCardShortLabels' => [(new LocalizedFallbackValue())->setString('lbl')],
                'paypalLabels' => [(new LocalizedFallbackValue())->setString('label')],
                'paypalShortLabels' => [(new LocalizedFallbackValue())->setString('lbl')],
                'sepaLabels' => [(new LocalizedFallbackValue())->setString('label')],
                'sepaShortLabels' => [(new LocalizedFallbackValue())->setString('lbl')],
            ]
        );

        /** @var ParameterBag $result */
        $result = $entity->getSettingsBag();

        static::assertEquals('some customerId', $result->get('customer_id'));
        static::assertEquals('some shopId', $result->get('shop_id'));
        static::assertEquals('some secret', $result->get('secret'));
        static::assertEquals(false, $result->get('test_mode'));


        static::assertEquals(
            $result->get('credit_card_labels'),
            $entity->getCreditCardLabels()
        );
        static::assertEquals(
            $result->get('credit_card_short_labels'),
            $entity->getCreditCardShortLabels()
        );
        static::assertEquals(
            $result->get('paypal_labels'),
            $entity->getPayPalLabels()
        );
        static::assertEquals(
            $result->get('paypal_short_labels'),
            $entity->getPayPalShortLabels()
        );
        static::assertEquals(
            $result->get('sepa_labels'),
            $entity->getSepaLabels()
        );
        static::assertEquals(
            $result->get('sepa_short_labels'),
            $entity->getSepaShortLabels()
        );
    }
}
