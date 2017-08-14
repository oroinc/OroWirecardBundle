<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Wirecard\Seamless\Request;

use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Request\AbstractRequest;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Request\InitSepaPaymentRequest;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option as Option;

class InitSepaPaymentRequestTest extends AbstractRequestTest
{

    /** {@inheritdoc} */
    public function createRequest():AbstractRequest
    {
        return new InitSepaPaymentRequest();
    }

    /** {@inheritdoc} */
    public function getOptions():array
    {
        return [
            Option\StorageId::STORAGEID => 'test_storage_id',
            Option\OrderIdent::ORDERIDENT => 'test_order_ident',
            Option\PaymentType::PAYMENTTYPE => Option\PaymentType::SEPA_DD,
            Option\Amount::AMOUNT => 123.45,
            Option\Sepa\Currency::CURRENCY => Option\Sepa\Currency::EURO,
            Option\OrderDescription::ORDERDESCRIPTION => 'test_order_description',
            Option\SuccessUrl::SUCCESSURL => 'http://test.local/success',
            Option\CancelUrl::CANCELURL => 'http://test.local/cancel',
            Option\FailureUrl::FAILUREURL => 'http://test.local/fail',
            Option\ConfirmUrl::CONFIRMURL => 'http://test.local/confirm',
            Option\ServiceUrl::SERVICEURL => 'http://test.local/service',
            Option\ConsumerUserAgent::CONSUMERUSERAGENT => 'test user agent',
            Option\ConsumerIpAddress::CONSUMERIPADDRESS => '127.0.0.1',
            Option\ConsumerShippingAddress::CONSUMERSHIPPINGFIRSTNAME => 'test_shipping_first_name',
            Option\ConsumerShippingAddress::CONSUMERSHIPPINGLASTNAME => 'test_shipping_last_name',
            Option\ConsumerShippingAddress::CONSUMERSHIPPINGADDRESS1 => 'test_shipping_address1',
            Option\ConsumerShippingAddress::CONSUMERSHIPPINGADDRESS2 => 'test_shipping_address2',
            Option\ConsumerShippingAddress::CONSUMERSHIPPINGCOUNTRY => 'test_shipping_country',
            Option\ConsumerShippingAddress::CONSUMERSHIPPINGCITY => 'test_shipping_city',
            Option\ConsumerShippingAddress::CONSUMERSHIPPINGSTATE => 'test_shipping_state',
            Option\ConsumerShippingAddress::CONSUMERSHIPPINGZIPCODE => 'test_shipping_zip_code',
        ];
    }

    public function testGetRequestIndentifier()
    {
        $this->assertEquals('init_sepa_payment', $this->request->getRequestIdentifier());
    }
}
