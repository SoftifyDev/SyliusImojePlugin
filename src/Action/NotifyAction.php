<?php

declare(strict_types=1);

namespace Softify\SyliusImojePlugin\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Exception\UnsupportedApiException;
use Payum\Core\Request\Notify;
use Softify\SyliusImojePlugin\Api\ApiInterface;
use Softify\SyliusImojePlugin\Service\ImojePaymentServiceInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\IpUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class NotifyAction  implements ActionInterface, ApiAwareInterface
{
    private Request $request;

    public function __construct(
        private ImojePaymentServiceInterface $imojePaymentService,
        private ParameterBagInterface $parameterBag,
        RequestStack $requestStack
    ){
        $this->request = $requestStack->getCurrentRequest();
    }

    public function execute($request): void
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $response = $this->imojePaymentService->deserializeRequest($this->request);

        $model = $request->getModel();
        $model['statusImoje'] = $response->getPayment()->getStatus();
        $model['transactionId'] = $response->getTransaction()->getId();

        $request->setModel($model);
    }

    public function supports($request): bool
    {
        return
            $request instanceof Notify
            && $request->getModel() instanceof ArrayObject
            && IpUtils::checkIp($this->request->getClientIp(), $this->parameterBag->get('softify_sylius_imoje.ips'))
            && $this->imojePaymentService->signatureFromHeaderIsValid($this->request);
    }

    public function setApi($api): void
    {
        if (!$api instanceof ApiInterface) {
            throw new UnsupportedApiException('Not supported. Expected an instance of ' . ApiInterface::class);
        }
        $this->imojePaymentService->setAuthorizationData($api);
    }
}
