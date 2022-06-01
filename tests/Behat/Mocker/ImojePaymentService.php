<?php

declare(strict_types=1);

namespace Tests\Softify\SyliusImojePlugin\Behat\Mocker;

use Payum\Core\Request\Capture;
use Psr\Container\ContainerInterface;
use Softify\SyliusImojePlugin\Api\ApiInterface;
use Softify\SyliusImojePlugin\Dto\ApiResponse;
use Softify\SyliusImojePlugin\Service\ImojePaymentServiceInterface;
use Symfony\Component\HttpFoundation\Request;

class ImojePaymentService implements ImojePaymentServiceInterface
{
    public function __construct(protected ContainerInterface $container)
    {
    }

    public function setAuthorizationData(ApiInterface $api): void
    {
        $this->container->get('softify.imoje_plugin.service.imoje_payment')->setAuthorizationData($api);
    }

    public function createPayment(Capture $request): ApiResponse
    {
        return $this->container->get('softify.imoje_plugin.service.imoje_payment')->createPayment($request);
    }

    public function retrievePayment(string $paymentId): ApiResponse
    {
        return $this->container->get('softify.imoje_plugin.service.imoje_payment')->retrievePayment($paymentId);
    }

    public function signatureFromHeaderIsValid(Request $request): bool
    {
        return $this->container->get('softify.imoje_plugin.service.imoje_payment')->signatureFromHeaderIsValid($request);
    }

    public function deserializeRequest(Request $request): ApiResponse
    {
        return $this->container->get('softify.imoje_plugin.service.imoje_payment')->deserializeRequest($request);
    }

    public function refund(string $transactionId, int $amount): ApiResponse
    {
        return $this->container->get('softify.imoje_plugin.service.imoje_payment')->refund($transactionId, $amount);
    }
}
