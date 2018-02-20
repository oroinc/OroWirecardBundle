<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Method\Config;

use Oro\Bundle\PaymentBundle\Tests\Unit\Method\Config\AbstractPaymentConfigTestCase;
use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessConfig;
use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessConfigInterface;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option;

abstract class AbstractWirecardConfigTestCase extends AbstractPaymentConfigTestCase
{
    /** @var WirecardSeamlessConfigInterface */
    protected $config;

    /** @var array */
    protected $credentials = [
        Option\CustomerId::CUSTOMERID => 1,
        Option\ShopId::SHOPID => 2,
        Option\Secret::SECRET => 'secret'
    ];

    /** @return array */
    protected function getPaymentConfigParams(): array
    {
        return [
            WirecardSeamlessConfig::FIELD_PAYMENT_METHOD_IDENTIFIER => 'test_payment_method_identifier',
            WirecardSeamlessConfig::FIELD_LABEL => 'test label',
            WirecardSeamlessConfig::FIELD_SHORT_LABEL => 'test short label',
            WirecardSeamlessConfig::CREDENTIALS_KEY => $this->credentials,
            WirecardSeamlessConfig::LANGUAGE_KEY => 'EN',
            WirecardSeamlessConfig::HASHING_METHOD_KEY => 'sha256',
            WirecardSeamlessConfig::TEST_MODE_KEY => true,
            WirecardSeamlessConfig::FIELD_ADMIN_LABEL => 'test admin label'
        ];
    }

    public function testIsTestMode()
    {
        $this->assertSame(true, $this->config->isTestMode());
    }

    public function testGetHashingMethod()
    {
        $this->assertSame('sha256', $this->config->getHashingMethod());
    }

    public function testGetlanguageCode()
    {
        $this->assertSame('EN', $this->config->getLanguageCode());
    }

    public function testGetCredentials()
    {
        self::assertSame($this->credentials, $this->config->getCredentials());
    }
}
