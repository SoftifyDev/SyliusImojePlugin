<?php

declare(strict_types=1);

namespace Softify\SyliusImojePlugin\Dto;

class ApiErrorResponse
{
    private string $code = '';
    private string $message;
    private array|string $instance;
    private array $errors = [];

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): ApiErrorResponse
    {
        $this->code = $code;
        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): ApiErrorResponse
    {
        $this->message = $message;
        return $this;
    }

    public function getInstance(): array|string
    {
        return $this->instance;
    }

    public function setInstance(array|string $instance): ApiErrorResponse
    {
        $this->instance = $instance;
        return $this;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function setErrors(array $errors): ApiErrorResponse
    {
        $this->errors = $errors;
        return $this;
    }

    public function addError(Error $error): ApiErrorResponse
    {
        $this->errors[] = $error;
        return $this;
    }
}
