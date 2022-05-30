<?php

declare(strict_types=1);

namespace Softify\SyliusImojePlugin\Dto;

class Refund
{
    public const TYPE_REFUND = 'refund';

    private string $type;
    private string $serviceId;
    private int $amount;

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): Refund
    {
        $this->type = $type;
        return $this;
    }

    public function getServiceId(): string
    {
        return $this->serviceId;
    }

    public function setServiceId(string $serviceId): Refund
    {
        $this->serviceId = $serviceId;
        return $this;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): Refund
    {
        $this->amount = $amount;
        return $this;
    }
}
