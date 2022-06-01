<?php

declare(strict_types=1);

namespace Tests\Softify\SyliusImojePlugin\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use Doctrine\Persistence\ObjectManager;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Sylius\Behat\Service\SharedStorageInterface;
use Sylius\Bundle\CoreBundle\Fixture\Factory\ExampleFactoryInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Sylius\Component\Core\Repository\PaymentMethodRepositoryInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

final class ImojeContext implements Context
{
    use MockeryPHPUnitIntegration;

    private SharedStorageInterface $sharedStorage;
    private PaymentMethodRepositoryInterface $paymentMethodRepository;
    private ExampleFactoryInterface $paymentMethodExampleFactory;
    private FactoryInterface $paymentMethodTranslationFactory;
    private ObjectManager $paymentMethodManager;

    public function __construct(
        SharedStorageInterface $sharedStorage,
        PaymentMethodRepositoryInterface $paymentMethodRepository,
        ExampleFactoryInterface $paymentMethodExampleFactory,
        ObjectManager $paymentMethodManager
    ) {
        $this->sharedStorage = $sharedStorage;
        $this->paymentMethodRepository = $paymentMethodRepository;
        $this->paymentMethodExampleFactory = $paymentMethodExampleFactory;
        $this->paymentMethodManager = $paymentMethodManager;
    }

    /**
     * @Given the store has a payment method :paymentMethodName with a code :paymentMethodCode and Imoje Checkout gateway
     */
    public function theStoreHasAPaymentMethodWithACodeAndImojeCheckoutGateway(
        string $paymentMethodName,
        string $paymentMethodCode
    ): void {
        $paymentMethod = $this->createPaymentMethod($paymentMethodName, $paymentMethodCode, 'Imoje Checkout');
        $paymentMethod->getGatewayConfig()->setConfig(
            [
                'environment' => 'sandbox',
                'debug_mode' => false,
                'authorization_token' => 'TOKEN',
                'merchant_id' => 'MERCHANT_ID',
                'service_id' => 'SERVICE_ID',
                'service_key' => 'SERVICE_KEY',
            ]
        );
        $this->paymentMethodManager->persist($paymentMethod);
        $this->paymentMethodManager->flush();
    }

    private function createPaymentMethod(
        string $name,
        string $code,
        string $description = '',
        bool $addForCurrentChannel = true,
        ?int $position = null
    ): PaymentMethodInterface {
        /** @var PaymentMethodInterface $paymentMethod */
        $paymentMethod = $this->paymentMethodExampleFactory->create(
            [
                'name' => ucfirst($name),
                'code' => $code,
                'description' => $description,
                'gatewayName' => 'imoje',
                'gatewayFactory' => 'imoje',
                'enabled' => true,
                'channels' => ($addForCurrentChannel && $this->sharedStorage->has('channel'))
                    ? [$this->sharedStorage->get('channel')] : [],
            ]
        );

        if (null !== $position) {
            $paymentMethod->setPosition($position);
        }

        $this->sharedStorage->set('payment_method', $paymentMethod);
        $this->paymentMethodRepository->add($paymentMethod);

        return $paymentMethod;
    }
}
