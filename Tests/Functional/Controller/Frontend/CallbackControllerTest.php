<?php

namespace Oro\Bundle\WirecardBundle\Tests\Functional\Controller\Frontend;

use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;
use Oro\Bundle\WirecardBundle\Tests\Functional\DataFixtures\LoadPaymentTransactionData;
use Oro\Bundle\PaymentBundle\Entity\PaymentTransaction;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Response\Response;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option;

class CallbackControllerTest extends WebTestCase
{
    const ALLOWED_IP_ADDR = '195.93.244.97';

    protected function setUp()
    {
        $this->initClient();
        $this->client->useHashNavigation(true);

        $this->loadFixtures([LoadPaymentTransactionData::class]);
    }

    public function testNotifyChangeState()
    {
        $this->markTestIncomplete('Skipped. Will be fixed in BB-9506');
        $response = new Response(
            [
                Response::FINGERPRINT_ORDER_FIELD => 'a,b,c',
                'a' => 'a',
                'b' => 'b',
                'c' => 'c'
            ]
        );

        $parameters = [
            Response::ORDER_NUMBER_FIELD => 'ref',
            Response::PAYMENT_STATE_FIELD => 'SUCCESS',
            Response::FINGERPRINT_FIELD =>
                $response->calcFingerprint('', Option\Hashing::DEFAULT_HASHING_METHOD),//config secret is empty
            Response::FINGERPRINT_ORDER_FIELD => 'a,b,c',
            'a' => 'a',
            'b' => 'b',
            'c' => 'c'
        ];

        /** @var PaymentTransaction $paymentTransaction */
        $paymentTransaction = $this->getReference(LoadPaymentTransactionData::WIRECARD_PURCHASE_TRANSACTION);
        $this->assertFalse($paymentTransaction->isActive());
        $this->assertFalse($paymentTransaction->isSuccessful());

        $expectedData = $parameters + $paymentTransaction->getRequest();
        $this->client->request(
            'POST',
            $this->getUrl(
                'oro_payment_callback_notify',
                [
                    'accessIdentifier' => $paymentTransaction->getAccessIdentifier(),
                    'accessToken' => $paymentTransaction->getAccessToken(),
                ]
            ),
            $expectedData,
            [],
            ['REMOTE_ADDR' => static::ALLOWED_IP_ADDR]
        );

        $objectManager = $this->getContainer()->get('doctrine')
            ->getRepository('OroPaymentBundle:PaymentTransaction');

        /** @var PaymentTransaction $paymentTransaction */
        $paymentTransaction = $objectManager->find($paymentTransaction->getId());

        $this->assertTrue($paymentTransaction->isActive());
        $this->assertTrue($paymentTransaction->isSuccessful());
        $this->assertEquals($expectedData, $paymentTransaction->getResponse());
    }

    /**
     * @return array[]
     */
    public function returnAllowedIPs()
    {
        return [
            'Wirecard\'s IP address 1 should be allowed' => ['195.93.244.97'],
            'Wirecard\'s IP address 2 should be allowed' => ['185.60.56.35'],
            'Wirecard\'s IP address 3 should be allowed' => ['185.60.56.36'],
        ];
    }

    /**
     * @return array[]
     */
    public function returnNotAllowedIPs()
    {
        return [
            'Google\'s IP address 4 should not be allowed' => ['216.58.214.206'],
            'Facebook\'s IP address 5 should not be allowed' => ['173.252.120.68'],
        ];
    }

    /**
     * @dataProvider returnAllowedIPs
     * @param string $remoteAddress
     */
    public function testNotifyAllowedIPFiltering($remoteAddress)
    {
        /** @var PaymentTransaction $paymentTransaction */
        $paymentTransaction = $this->getReference(LoadPaymentTransactionData::WIRECARD_PURCHASE_TRANSACTION_IP_FILTER);

        $this->client->request(
            'POST',
            $this->getUrl(
                'oro_payment_callback_notify',
                [
                    'accessIdentifier' => $paymentTransaction->getAccessIdentifier(),
                    'accessToken' => $paymentTransaction->getAccessToken(),
                ]
            ),
            [],
            [],
            ['REMOTE_ADDR' => $remoteAddress]
        );

        $this->assertResponseStatusCodeEquals($this->client->getResponse(), 200);
    }

    /**
     * @dataProvider returnNotAllowedIPs
     * @param string $remoteAddress
     */
    public function testNotifyNotAllowedIPFiltering($remoteAddress)
    {
        /** @var PaymentTransaction $paymentTransaction */
        $paymentTransaction = $this->getReference(LoadPaymentTransactionData::WIRECARD_PURCHASE_TRANSACTION_IP_FILTER);

        $this->client->request(
            'POST',
            $this->getUrl(
                'oro_payment_callback_notify',
                [
                    'accessIdentifier' => $paymentTransaction->getAccessIdentifier(),
                    'accessToken' => $paymentTransaction->getAccessToken(),
                ]
            ),
            [],
            [],
            ['REMOTE_ADDR' => $remoteAddress]
        );

        $this->assertResponseStatusCodeEquals($this->client->getResponse(), 403);
    }
}
