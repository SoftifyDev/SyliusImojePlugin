<?php

declare(strict_types=1);

namespace Softify\SyliusImojePlugin\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\GetStatusInterface;
use Softify\SyliusImojePlugin\Api\ApiInterface;
use ArrayAccess;

final class StatusAction implements ActionInterface
{
    public function execute($request): void
    {
        /** @var $request GetStatusInterface */
        RequestNotSupportedException::assertSupports($this, $request);
        $model = $request->getModel();
        $status = $model['statusImoje'] ?? null;
        $paymentId = $model['paymentId'] ?? null;

        if (($status === null || ApiInterface::STATUS_NEW === $status) && null !== $paymentId) {
            $request->markNew();
            return;
        }

        if (ApiInterface::STATUS_PENDING === $status) {
            $request->markPending();
            return;
        }

        if (ApiInterface::STATUS_CANCELLED === $status || ApiInterface::STATUS_REJECTED === $status) {
            $request->markCanceled();
            return;
        }

        if (ApiInterface::STATUS_ERROR === $status) {
            $request->markFailed();
            return;
        }

        if (ApiInterface::STATUS_SETTLED === $status) {
            $request->markCaptured();
            return;
        }

        $request->markUnknown();
    }

    public function supports($request): bool
    {
        return
            $request instanceof GetStatusInterface
            && $request->getModel() instanceof ArrayAccess;
    }
}
