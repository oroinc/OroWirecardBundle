<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Method\Config\Mapping;

use Oro\Bundle\WirecardBundle\Method\Config\Mapping\WirecardLanguageCodeMapper;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option\Language;

class WirecardLanguageCodeMapperTest extends \PHPUnit_Framework_TestCase
{
    /** @var WirecardLanguageCodeMapper */
    protected $languageCodeMapper;

    protected function setUp()
    {
        $this->languageCodeMapper = new WirecardLanguageCodeMapper();
    }

    /** @dataProvider languageDataProvider */
    public function testMapLanguageCodeToWirecardLanguageCode($oroLanguageCode, $expectedValue)
    {
        $this->assertSame(
            $expectedValue,
            $this->languageCodeMapper->mapLanguageCodeToWirecardLanguageCode($oroLanguageCode)
        );
    }

    /** @return array */
    public function languageDataProvider(): array
    {
        return [
            ['en', Language::EN],
            ['de_DE' , Language::DE],
            ['fr_FR' , Language::FR],
            ['en_US' , Language::EN],
            ['en_CA' , Language::EN],
            ['en_GB' , Language::EN],
            ['en_AU' , Language::EN],
            ['es_AR' , Language::ES],
            ['fr_CA' , Language::FR]
        ];
    }
}
