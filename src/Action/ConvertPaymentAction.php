<?php

declare(strict_types=1);

namespace Softify\SyliusImojePlugin\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Model\PaymentInterface;
use Payum\Core\Request\Convert;

final class ConvertPaymentAction implements ActionInterface
{
    public function execute($request): void
    {
        RequestNotSupportedException::assertSupports($this, $request);

        /** @var PaymentInterface $payment */
        $payment = $request->getSource();
        $details = ArrayObject::ensureArrayObject($payment->getDetails());

        $details['totalAmount'] = $payment->getTotalAmount();
        $details['currencyCode'] = $payment->getCurrencyCode();
        $details['description'] = $payment->getDescription();
        $details['clientEmail'] = $payment->getClientEmail();
        $details['clientId'] = $payment->getClientId();
        $details['customerIp'] = $this->getClientIp();

        $request->setResult((array) $details);
    }

    public function supports($request): bool
    {
        return $request instanceof Convert
               && $request->getSource() instanceof PaymentInterface
               && 'array' === $request->getTo();
    }

    private function getClientIp(): ?string
    {
        return $_SERVER['REMOTE_ADDR'] ?? null;
    }
}
