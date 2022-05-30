<?php

declare(strict_types=1);

namespace Softify\SyliusImojePlugin\Api;

final class Api implements ApiInterface
{
    public function __construct(
        private string $environment,
        private string $authorizationToken,
        private string $merchantId,
        private string $serviceId,
        private string $serviceKey,
        private bool $debugMode = false
    ) {
    }

    public function getApiEndpoint(): string
    {
        return $this->environment === 'sandbox' ? self::API_URL_SANDBOX : self::API_URL_PRODUCTION;
    }

    public function getAuthorizationToken(): string
    {
        return $this->authorizationToken;
    }

    public function getMerchantId(): string
    {
        return $this->merchantId;
    }

    public function getServiceId(): string
    {
        return $this->serviceId;
    }

    public function getServiceKey(): string
    {
        return $this->serviceKey;
    }

    public function isDebugMode(): bool
    {
        return $this->debugMode;
    }
}
