<?php

declare(strict_types=1);

namespace Tests\Softify\SyliusImojePlugin\Behat\Mocker;

use Payum\Core\Security\TokenInterface;
use Softify\SyliusImojePlugin\Api\ApiInterface;
use Softify\SyliusImojePlugin\Dto\ApiResponse;
use Softify\SyliusImojePlugin\Dto\Payment;
use Softify\SyliusImojePlugin\Dto\Transaction;
use Softify\SyliusImojePlugin\Service\ImojePaymentServiceInterface;
use Softify\SyliusImojePlugin\Service\TokenFactoryFromRequestServiceInterface;
use Sylius\Behat\Service\Mocker\Mocker;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class ImojeApiMocker
{
    public function __construct(private Mocker $mocker, private RepositoryInterface $securityTokenRepository)
    {
    }

    public function mockApiSuccessfulPaymentResponse(callable $action): void
    {
        $service = $this->mocker
            ->mockService(
                'softify.imoje_plugin.service.imoje_payment',
                ImojePaymentServiceInterface::class
            );

        $service->shouldReceive('createPayment')->andReturn(
            $this->createResponseWithStatusApi(ApiInterface::STATUS_NEW)
        );
        $service->shouldReceive('setAuthorizationData');

        $action();

        $this->mocker->unmockAll();
    }

    public function completedPayment(callable $action): void
    {
        $service = $this->mocker
            ->mockService(
                'softify.imoje_plugin.service.imoje_payment',
                ImojePaymentServiceInterface::class
            );

        $service->shouldReceive('retrievePayment')->andReturn(
            $this->createResponseWithStatusApi(ApiInterface::STATUS_SETTLED)
        );
        $service->shouldReceive('setAuthorizationData');

        $action();

        $this->mocker->unmockAll();
    }

    public function canceledPayment(callable $action): void
    {
        $service = $this->mocker
            ->mockService(
                'softify.imoje_plugin.service.imoje_payment',
                ImojePaymentServiceInterface::class
            );

        $service->shouldReceive('retrievePayment')->andReturn(
            $this->createResponseWithStatusApi(ApiInterface::STATUS_CANCELLED)
        );
        $service->shouldReceive('setAuthorizationData');

        $action();

        $this->mocker->unmockAll();
    }

    public function notifyPayment(callable $action, bool $tokenFindable, bool $signatureValidation): void
    {
        $tokenFactoryService = $this->mocker
            ->mockService(
                'softify.imoje_plugin.service.token_factory',
                TokenFactoryFromRequestServiceInterface::class
            );
        $tokenFactoryService->shouldReceive('create')->andReturn($tokenFindable ? $this->findNotifyToken() : null);

        $paymentService = $this->mocker
            ->mockService(
                'softify.imoje_plugin.service.imoje_payment',
                ImojePaymentServiceInterface::class
            );
        $paymentService->shouldReceive('deserializeRequest')->andReturn(
            $this->createResponseWithStatusApi(ApiInterface::STATUS_SETTLED)
        );
        $paymentService->shouldReceive('setAuthorizationData');
        $paymentService->shouldReceive('signatureFromHeaderIsValid')->andReturn($signatureValidation);

        $action();

        $this->mocker->unmockAll();
    }

    private function createResponseWithStatusApi(string $status): ApiResponse
    {
        $response = new ApiResponse();
        $response
            ->setCode(200)
            ->setTransaction(
                (new Transaction())
                    ->setId(uniqid('', true))
            )
            ->setPayment(
                (new Payment())
                    ->setId(uniqid('', true))
                    ->setStatus($status)
                    ->setUrl('/')
            );

        return $response;
    }

    private function findNotifyToken(): ?TokenInterface
    {
        $tokens = $this->securityTokenRepository->findAll();

        /** @var TokenInterface $token */
        foreach ($tokens as $token) {
            if (strpos($token->getTargetUrl(), 'notify')) {
                return $token;
            }
        }

        return null;
    }
}
