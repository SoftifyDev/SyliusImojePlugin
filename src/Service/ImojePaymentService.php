<?php

declare(strict_types=1);

namespace Softify\SyliusImojePlugin\Service;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\RequestOptions;
use JetBrains\PhpStorm\ArrayShape;
use Payum\Core\Request\Capture;
use Payum\Core\Security\TokenInterface;
use Softify\SyliusImojePlugin\Api\ApiInterface;
use Softify\SyliusImojePlugin\Serializer\IntDenormalize;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Softify\SyliusImojePlugin\Dto\Payment as PaymentDto;
use Softify\SyliusImojePlugin\Dto\Customer as CustomerDto;
use Softify\SyliusImojePlugin\Dto\ApiResponse as ApiResponseDto;
use Softify\SyliusImojePlugin\Dto\ApiErrorResponse as ApiErrorResponseDto;
use Softify\SyliusImojePlugin\Dto\Refund as RefundDto;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

class ImojePaymentService implements ImojePaymentServiceInterface
{
    protected ApiInterface $api;
    protected ClientInterface $client;

    public function __construct(protected SerializerInterface $serializer)
    {
        $this->client = new Client();
    }

    public function setAuthorizationData(ApiInterface $api): void
    {
        $this->api = $api;
    }

    public function createPayment(Capture $request): ApiResponseDto
    {
        $paymentDto = $this->createPaymentDto($request);
        return $this->doRequest(function() use ($paymentDto) {
            return $this->client->request(
                'POST', sprintf('%s/%s/payment', $this->api->getApiEndpoint(), $this->api->getMerchantId()),
                [
                    RequestOptions::HEADERS => $this->getHeaders(),
                    RequestOptions::BODY => $this->serializer->serialize($paymentDto, 'json')
                ]
            );
        }, ApiResponseDto::class);
    }

    public function retrievePayment(string $paymentId): ApiResponseDto
    {
        return $this->doRequest(function() use ($paymentId) {
            return $this->client->request(
                'GET',
                sprintf('%s/%s/payment/%s', $this->api->getApiEndpoint(), $this->api->getMerchantId(), $paymentId),
                [
                    RequestOptions::HEADERS => $this->getHeaders(),
                ]
            );
        }, PaymentDto::class);
    }

    public function signatureFromHeaderIsValid(Request $request): bool
    {
        $header = $request->headers->get('X-IMoje-Signature');
        $parts = [];
        foreach (explode(';', $header) as $part) {
            [$key, $value] = explode('=', $part);
            $parts[$key] = $value;
        }

        if (
            $this->api->getServiceId() !== $parts['serviceid']
            || $this->api->getMerchantId() !== $parts['merchantid']
        ) {
            return false;
        }

        $signature = hash($parts['alg'], $request->getContent() . $this->api->getServiceKey());
        return $signature === $parts['signature'];
    }

    public function deserializeRequest(Request $request): ApiResponseDto
    {
        return $this->deserialize($request->getContent(), ApiResponseDto::class);
    }

    public function refund(string $transactionId, int $amount): ApiResponseDto
    {
        $refundDto = new RefundDto();
        $refundDto
            ->setAmount($amount)
            ->setServiceId($this->api->getServiceId())
            ->setType(RefundDto::TYPE_REFUND);

        return $this->doRequest(function () use ($transactionId, $refundDto) {
            return $this->client->request(
                'POST',
                sprintf(
                    '%s/%s/transaction/%s/refund',
                    $this->api->getApiEndpoint(),
                    $this->api->getMerchantId(),
                    $transactionId
                ),
                [
                    RequestOptions::HEADERS => $this->getHeaders(),
                    RequestOptions::BODY => $this->serializer->serialize($refundDto, 'json')
                ]
            );
        }, ApiResponseDto::class);
    }

    protected function doRequest(callable $callback, string $model): ApiResponseDto
    {
        try {
            $response = $callback();
        } catch (RequestException $exception) {
            $response = $exception->getResponse();
        } finally {
            $apiResponse = new ApiResponseDto();
            if ($body = $response->getBody()->getContents()) {
                $apiResponse = $this->deserialize($body, $model);
                if ($apiResponse instanceof PaymentDto) {
                    $apiResponse = (new ApiResponseDto())->setPayment($apiResponse);
                }
            } elseif ($response->getStatusCode() !== 200) {
                $apiResponse->setApiErrorResponse(
                    (new ApiErrorResponseDto())->setMessage($response->getReasonPhrase())
                );
            }
            $apiResponse->setCode($response->getStatusCode());
        }
        return $apiResponse;
    }

    protected function deserialize(string $content, string $model): mixed
    {
        return $this->serializer->deserialize(
            $content,
            $model,
            'json',
            [
                DateTimeNormalizer::FORMAT_KEY => 'U',
                IntDenormalize::FIELDS => ['amount']
            ]
        );
    }

    #[ArrayShape(['Authorization' => "string", 'Content-Type' => "string"])]
    protected function getHeaders(): array
    {
        return [
            'Authorization' => sprintf('Bearer %s', $this->api->getAuthorizationToken()),
            'Content-Type' => 'application/json'
        ];
    }

    protected function createPaymentDto(Capture $request): PaymentDto
    {
        /** @var PaymentInterface $payment */
        $payment = $request->getFirstModel();
        /** @var OrderInterface $order */
        $order = $payment->getOrder();
        /** @var CustomerInterface $customer */
        $customer = $order->getCustomer();
        /** @var TokenInterface $token */
        $token = $request->getToken();

        $paymentDto = new PaymentDto();
        $paymentDto
            ->setServiceId($this->api->getServiceId())
            ->setAmount($payment->getAmount())
            ->setCurrency($payment->getCurrencyCode())
            ->setOrderId($order->getNumber())
            ->setReturnUrl($token->getTargetUrl())
            ->setSuccessReturnUrl($token->getTargetUrl())
            ->setFailureReturnUrl($token->getTargetUrl())
            ->setCustomer(
                (new CustomerDto())
                    ->setFirstName($customer->getFirstName())
                    ->setLastName($customer->getLastName())
                    ->setEmail($customer->getEmail())
                    ->setPhone($customer->getPhoneNumber() ?: '')
            );
        return $paymentDto;
    }
}
