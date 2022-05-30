<?php

declare(strict_types=1);

namespace Softify\SyliusImojePlugin\Api;

interface ApiInterface
{
    public const HASH_METHOD = 'sha256';

    public const ENVIRONMENT_SANDBOX = 'sandbox';
    public const ENVIRONMENT_PRODUCTION = 'production';

    public const URL_PRODUCTION = 'https://paywall.imoje.pl/pl/payment';
    public const URL_SANDBOX = 'https://sandbox.paywall.imoje.pl/pl/payment';

    public const API_URL_PRODUCTION = 'https://api.imoje.pl/v1/merchant';
    public const API_URL_SANDBOX = 'https://sandbox.api.imoje.pl/v1/merchant';

    public const STATUS_NEW = 'new';
    public const STATUS_AUTHORIZED = 'authorized';
    public const STATUS_PENDING = 'pending';
    public const STATUS_SUBMITTED = 'submitted';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_SETTLED = 'settled';
    public const STATUS_ERROR = 'error';
    public const STATUS_CANCELLED = 'cancelled';

    public function getApiEndpoint(): string;
    public function getAuthorizationToken(): string;
    public function getMerchantId(): string;
    public function getServiceId(): string;
    public function getServiceKey(): string;
    public function isDebugMode(): bool;
}
