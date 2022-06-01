<?php

declare(strict_types=1);

namespace Softify\SyliusImojePlugin\Controller;

use Payum\Bundle\PayumBundle\Controller\NotifyController;
use Payum\Core\GatewayInterface;
use Payum\Core\Request\Notify;
use Payum\Core\Security\TokenInterface;
use Softify\SyliusImojePlugin\ImojeGatewayFactory;
use Softify\SyliusImojePlugin\Service\TokenFactoryFromRequestServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class UnsafeNotifyController extends NotifyController
{
    public function __construct(private TokenFactoryFromRequestServiceInterface $tokenFactoryFromRequestService){
    }

    public function doUnsafeAction(Request $request): Response
    {
        $gateway = $this->getPayum()->getGateway($request->get('gateway'));
        if ($request->get('gateway') === ImojeGatewayFactory::GATEWAY_NAME) {
            if ($token = $this->tokenFactoryFromRequestService->create($request)) {
                $this->doAction($this->createNewRequestFromToken($request, $token));
                return $this->response200();
            }
            return $this->response204();
        }
        return $this->doDefault($gateway);
    }

    protected function doDefault(GatewayInterface $gateway): Response
    {
        $gateway->execute(new Notify(null));
        return $this->response204();
    }

    protected function response204(): Response
    {
        return new Response('', 204);
    }

    protected function response200(): Response
    {
        return new JsonResponse(['status' => 'ok'], 200);
    }

    protected function createNewRequestFromToken(Request $request, TokenInterface $token): Request
    {
        $request = Request::create(
            $token->getTargetUrl(),
            $request->getMethod(),
            $request->query->all(),
            $request->cookies->all(),
            $request->files->all(),
            $request->server->all(),
            $request->getContent()
        );
        $request->attributes->add([
            'payum_token' => $token->getHash(),
        ]);
        return $request;
    }
}
