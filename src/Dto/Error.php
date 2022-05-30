<?php

declare(strict_types=1);

namespace Softify\SyliusImojePlugin\Dto;

class Error
{
    private string $property = '';
    private string $message = '';

    public function getProperty(): string
    {
        return $this->property;
    }

    public function setProperty(string $property): Error
    {
        $this->property = $property;
        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): Error
    {
        $this->message = $message;
        return $this;
    }
}
