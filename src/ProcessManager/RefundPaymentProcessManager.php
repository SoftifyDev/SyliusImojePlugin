<?php

declare(strict_types=1);

namespace Softify\SyliusImojePlugin\ProcessManager;

use Doctrine\ORM\EntityManagerInterface;
use Payum\Core\Model\GatewayConfigInterface;
use Softify\SyliusImojePlugin\Api\Api;
use Softify\SyliusImojePlugin\Exception\MissingTransactionIdException;
use Softify\SyliusImojePlugin\Exception\RefundException;
use Softify\SyliusImojePlugin\ImojeGatewayFactory;
use Softify\SyliusImojePlugin\Service\ImojePaymentServiceInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Sylius\Component\Payment\Model\PaymentInterface;
use Sylius\RefundPlugin\Event\UnitsRefunded;
use Sylius\RefundPlugin\ProcessManager\UnitsRefundedProcessStepInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

final class RefundPaymentProcessManager implements UnitsRefundedProcessStepInterface
{
    public function __construct(
        private ParameterBagInterface $parameterBag,
        private EntityManagerInterface $entityManager,
        private ImojePaymentServiceInterface $imojePaymentService,
    ) {
    }

    public function next(UnitsRefunded $unitsRefunded): void
    {
        $order = $this->getOrder($unitsRefunded->orderNumber());
        if ($order === null) {
            return;
        }

        $payment = $order->getLastPayment(PaymentInterface::STATE_COMPLETED);
        if ($payment === null) {
            return;
        }

        /** @var PaymentMethodInterface $method */
        $method = $payment->getMethod();
        if ($method === null) {
            return;
        }

        $gatewayConfig = $method->getGatewayConfig();
        if ($gatewayConfig === null) {
            return;
        }

        if ($gatewayConfig->getGatewayName() !== ImojeGatewayFactory::GATEWAY_NAME) {
            return;
        }

        $transactionId = $payment->getDetails()['transactionId'] ?? null;
        if ($transactionId === null) {
            throw MissingTransactionIdException::withMessage($payment->getId());
        }

        $this->setImojeServiceConfiguration($gatewayConfig);
        $response = $this->imojePaymentService->refund($transactionId, $unitsRefunded->amount());
        if ($response->getCode() !== 200) {
            throw RefundException::withMessage($response->getApiErrorResponse()->getMessage());
        }
    }

    protected function setImojeServiceConfiguration(GatewayConfigInterface $gatewayConfig): void
    {
        $config = $gatewayConfig->getConfig();
        $this->imojePaymentService->setAuthorizationData(
            new Api(
                $config['environment'],
                $config['authorization_token'],
                $config['merchant_id'],
                $config['service_id'],
                $config['service_key'],
                $config['debug_mode'],
            )
        );
    }

    protected function getOrder(string $orderNumber): ?OrderInterface
    {
        $repository = $this->entityManager->getRepository(
            $this->parameterBag->get('softify_sylius_imoje.order_model_class')
        );
        return $repository->findOneBy(['number' => $orderNumber]);
    }

}
