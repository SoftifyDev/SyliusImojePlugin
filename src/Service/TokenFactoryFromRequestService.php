<?php

declare(strict_types=1);

namespace Softify\SyliusImojePlugin\Service;

use Payum\Core\Security\TokenInterface;
use Softify\SyliusImojePlugin\Dto\ApiResponse;
use Softify\SyliusImojePlugin\Dto\Payment as PaymentDto;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\Payment;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class TokenFactoryFromRequestService implements TokenFactoryFromRequestServiceInterface
{
    public function __construct(
        private SerializerInterface $serializer,
        private RepositoryInterface $securityTokenRepository,
        private RepositoryInterface $orderRepository,
    ){
    }

    public function create(Request $request): ?TokenInterface
    {
        $body = $request->getContent();
        if (empty($body)) {
            return null;
        }

        $object = $this->deserializeBody($body);
        $order = $this->getOrder($object);
        if ($order === null) {
            return null;
        }

        $token = null;
        /** @var Payment $payment */
        foreach ($order->getPayments() as $payment) {
            if ($hash = $this->getTokenHash($payment->getDetails(), $object->getPayment())) {
                $token = $this->getToken($hash);
            }
            if ($token) {
                break;
            }
        }
        return $token;
    }

    protected function getTokenHash(array $details, PaymentDto $payment): ?string
    {
        if (empty($details['paymentId'])) {
            return null;
        }
        if (empty($details['notifyTokenHash'])) {
            return null;
        }
        if ($details['paymentId'] !== $payment->getId()) {
            return null;
        }
        return $details['notifyTokenHash'];
    }

    protected function getToken(string $hash): ?TokenInterface
    {
        return $this->securityTokenRepository->findOneBy(['hash' => $hash]);
    }

    protected function getOrder(ApiResponse $object): ?OrderInterface
    {
        $orderNumber = $object->getPayment()?->getOrderId();
        return $this->orderRepository->findOneBy(['number' => $orderNumber]);
    }

    protected function deserializeBody(string $payload): ApiResponse
    {
        return $this->serializer->deserialize(
            $payload,
            ApiResponse::class,
            'json',
            [
                DateTimeNormalizer::FORMAT_KEY => 'U'
            ]
        );
    }
}
