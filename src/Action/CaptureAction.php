<?php

declare(strict_types=1);

namespace Softify\SyliusImojePlugin\Action;

use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Reply\HttpRedirect;
use Payum\Core\Security\GenericTokenFactoryAwareInterface;
use Payum\Core\Security\GenericTokenFactoryAwareTrait;
use Payum\Core\Security\TokenInterface;
use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Exception\UnsupportedApiException;
use Softify\SyliusImojePlugin\Api\ApiInterface;
use Softify\SyliusImojePlugin\Exception\ImojeException;
use Softify\SyliusImojePlugin\Service\ImojePaymentServiceInterface;
use Payum\Core\Request\Capture;

final class CaptureAction implements ActionInterface, ApiAwareInterface, GenericTokenFactoryAwareInterface
{
    use GenericTokenFactoryAwareTrait;

    private ApiInterface $api;

    public function __construct(private ImojePaymentServiceInterface $imojePaymentService)
    {
    }

    public function execute($request): void
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = $request->getModel();
        if ($model['paymentId'] === null) {
            $response = $this->imojePaymentService->createPayment($request);
            if ($response->getCode() === 200) {
                /** @var TokenInterface $token */
                $token = $request->getToken();

                $notifyToken = $this->createNotifyToken($token);

                $model['paymentId'] = $response->getPayment()->getId();
                $model['notifyTokenHash'] = $notifyToken->getHash();
                $model['paymentUrl'] = $response->getPayment()->getUrl();
                $request->setModel($model);

                throw new HttpRedirect($model['paymentUrl']);
            }
        } else {
            $token = $request->getToken();
            $response = $this->imojePaymentService->retrievePayment((string)$model['paymentId']);
            if ($response->getCode() === 200) {
                $model['statusImoje'] = $response->getPayment()->getStatus();
                $request->setModel($model);
                if ($model['statusImoje'] === ApiInterface::STATUS_NEW) {
                    throw new HttpRedirect($model['paymentUrl']);
                } elseif (!$this->api->invalidateCaptureToken()) {
                    throw new HttpRedirect($token->getAfterUrl());
                }
                return;
            }
        }
        throw ImojeException::newInstance($response->getApiErrorResponse(), $response->getCode());
    }

    public function supports($request): bool
    {
        return
            $request instanceof Capture
            && $request->getModel() instanceof ArrayObject;
    }

    protected function createNotifyToken(TokenInterface $token): TokenInterface
    {
        return $this->tokenFactory->createNotifyToken($token->getGatewayName(), $token->getDetails());
    }

    public function setApi($api): void
    {
        if (!$api instanceof ApiInterface) {
            throw new UnsupportedApiException('Not supported. Expected an instance of ' . ApiInterface::class);
        }
        $this->imojePaymentService->setAuthorizationData($api);
        $this->api = $api;
    }
}
