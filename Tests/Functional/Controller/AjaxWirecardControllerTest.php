<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Controller;

use Oro\Bundle\CheckoutBundle\Entity\Checkout;
use Oro\Bundle\CustomerBundle\Tests\Functional\DataFixtures\LoadCustomerUserData;
use Oro\Bundle\PaymentBundle\Method\Provider\PaymentMethodProviderInterface;
use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;
use Oro\Bundle\WirecardBundle\Tests\Functional\DataFixtures\LoadCheckoutData;
use Oro\Bundle\WirecardBundle\Tests\Functional\Stub\Method\WirecardSeamlessMethodStub;

class AjaxWirecardControllerTest extends WebTestCase
{
    /**
     * @var PaymentMethodProviderInterface
     */
    protected $stubMethodProvider;

    protected function setUp()
    {
        $this->initClient(
            [],
            $this->generateBasicAuthHeader(LoadCustomerUserData::EMAIL, LoadCustomerUserData::PASSWORD)
        );
        $this->loadFixtures(
            [
                LoadCustomerUserData::class,
                LoadCheckoutData::class
            ]
        );
    }


    public function testInitiateWirecardSeamless()
    {
        $paymentMethodIdentifier = WirecardSeamlessMethodStub::TYPE;

        /** @var Checkout $checkout */
        $checkout = $this->getReference('wirecard:checkout_1');

        $this->client->request(
            'GET',
            $this->getUrl(
                'oro_wirecard_seamless_initiate',
                [
                    'id' => $checkout->getId(),
                    'paymentMethod' => $paymentMethodIdentifier
                ]
            )
        );

        $expectedData = [
            'storageId' => WirecardSeamlessMethodStub::TEST_STORAGE_ID,
            'javascriptURL' => WirecardSeamlessMethodStub::TEST_JAVASCRIPT_URL
        ];

        $result = $this->client->getResponse();
        $this->assertJsonResponseStatusCodeEquals($result, 200);
        $this->assertJsonStringEqualsJsonString(json_encode($expectedData), $result->getContent());
    }
}
