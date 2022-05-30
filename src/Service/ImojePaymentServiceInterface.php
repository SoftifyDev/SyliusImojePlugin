<?php

declare(strict_types=1);

namespace Softify\SyliusImojePlugin\Service;

use Payum\Core\Request\Capture;
use Softify\SyliusImojePlugin\Api\ApiInterface;
use Softify\SyliusImojePlugin\Dto\ApiResponse;
use Symfony\Component\HttpFoundation\Request;

interface ImojePaymentServiceInterface
{
    public function setAuthorizationData(ApiInterface $api): void;
    public function createPayment(Capture $request): ApiResponse;
    public function retrievePayment(string $paymentId): ApiResponse;
    public function signatureFromHeaderIsValid(Request $request): bool;
    public function deserializeRequest(Request $request): ApiResponse;
    public function refund(string $transactionId, int $amount): ApiResponse;
}
