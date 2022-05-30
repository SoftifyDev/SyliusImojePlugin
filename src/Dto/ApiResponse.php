<?php

declare(strict_types=1);

namespace Softify\SyliusImojePlugin\Dto;

class ApiResponse
{
    private int $code = 200;
    private Transaction $transaction;
    private Payment $payment;
    private ApiErrorResponse $apiErrorResponse;

    public function getCode(): int
    {
        return $this->code;
    }

    public function setCode(int $code): ApiResponse
    {
        $this->code = $code;
        return $this;
    }

    public function getTransaction(): Transaction
    {
        return $this->transaction;
    }

    public function setTransaction(Transaction $transaction): ApiResponse
    {
        $this->transaction = $transaction;
        return $this;
    }

    public function getPayment(): Payment
    {
        return $this->payment;
    }

    public function setPayment(Payment $payment): ApiResponse
    {
        $this->payment = $payment;
        return $this;
    }

    public function getApiErrorResponse(): ApiErrorResponse
    {
        return $this->apiErrorResponse;
    }

    public function setApiErrorResponse(ApiErrorResponse $apiErrorResponse): ApiResponse
    {
        $this->apiErrorResponse = $apiErrorResponse;
        return $this;
    }
}
