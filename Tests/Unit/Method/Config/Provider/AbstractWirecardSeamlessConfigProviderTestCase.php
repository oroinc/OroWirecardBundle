<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Method\Config\Provider;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;
use Oro\Bundle\WirecardBundle\Entity\Repository\WirecardSeamlessSettingsRepository;
use Oro\Bundle\WirecardBundle\Entity\WirecardSeamlessSettings;
use Oro\Bundle\WirecardBundle\Method\Config\Factory\WirecardSeamlessConfigFactoryInterface;
use Oro\Bundle\WirecardBundle\Method\Config\Provider\WirecardSeamlessConfigProviderInterface;
use Psr\Log\LoggerInterface;

abstract class AbstractWirecardSeamlessConfigProviderTestCase extends \PHPUnit_Framework_TestCase
{
    /** @var  WirecardSeamlessConfigProviderInterface */
    protected $configProvider;

    /**@var ManagerRegistry|\PHPUnit_Framework_MockObject_MockObject */
    protected $doctrine;

    /** @var WirecardSeamlessConfigFactoryInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $factory;

    /** @var LoggerInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $logger;

    /** @var  @var WirecardSeamlessSettings|\PHPUnit_Framework_MockObject_MockObject */
    protected $wireCardSettings;

    /** @var string */
    protected $type = 'wirecard_test';

    /** @return array */
    abstract public function expectedConfigDataProvider(): array;

    protected function setUp()
    {
        $this->doctrine = $this->createMock(ManagerRegistry::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->wireCardSettings = $this->createMock(WirecardSeamlessSettings::class);

        $wireCardSettingsRepository = $this->createMock(WirecardSeamlessSettingsRepository::class);
        $wireCardSettingsRepository->expects($this->once())->method('getEnabledSettingsByType')
            ->with($this->type)->willReturn([$this->wireCardSettings]);

        $objectManager = $this->createMock(ObjectManager::class);
        $objectManager->expects($this->once())->method('getRepository')
            ->with('OroWirecardBundle:WirecardSeamlessSettings')->willReturn($wireCardSettingsRepository);

        $this->doctrine->expects($this->once())->method('getManagerForClass')
            ->with('OroWirecardBundle:WirecardSeamlessSettings')->willReturn($objectManager);
    }

    public function testGetPaymentConfigs()
    {
        $this->assertCount(1, $this->configProvider->getPaymentConfigs());
    }

    /** @dataProvider expectedConfigDataProvider */
    public function testGetPaymentConfig($expectedClass)
    {
        $identifier = 'test_payment_method_identifier';

        $this->assertInstanceOf(
            $expectedClass,
            $this->configProvider->getPaymentConfig($identifier)
        );
    }

    public function testHasPaymentConfig()
    {
        $identifier = 'test_payment_method_identifier';

        $this->assertTrue($this->configProvider->hasPaymentConfig($identifier));
    }
}
