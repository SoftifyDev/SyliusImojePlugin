<?php

declare(strict_types=1);

namespace Softify\SyliusImojePlugin\Dto;

use DateTime;

class Transaction
{
    private string $id;
    private string $type;
    private string $status;
    private string $web;
    private DateTime $created;
    private DateTime $modified;
    private ?string $notificationUrl;
    private string $serviceId;
    private int $amount;
    private string $currency;
    private string $title;
    private string $orderId;
    private string $paymentMethod;
    private string $paymentMethodCode;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): Transaction
    {
        $this->id = $id;
        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): Transaction
    {
        $this->type = $type;
        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): Transaction
    {
        $this->status = $status;
        return $this;
    }

    public function getWeb(): string
    {
        return $this->web;
    }

    public function setWeb(string $web): Transaction
    {
        $this->web = $web;
        return $this;
    }

    public function getCreated(): DateTime
    {
        return $this->created;
    }

    public function setCreated(DateTime $created): Transaction
    {
        $this->created = $created;
        return $this;
    }

    public function getModified(): DateTime
    {
        return $this->modified;
    }

    public function setModified(DateTime $modified): Transaction
    {
        $this->modified = $modified;
        return $this;
    }

    public function getNotificationUrl(): ?string
    {
        return $this->notificationUrl;
    }

    public function setNotificationUrl(?string $notificationUrl): Transaction
    {
        $this->notificationUrl = $notificationUrl;
        return $this;
    }

    public function getServiceId(): string
    {
        return $this->serviceId;
    }

    public function setServiceId(string $serviceId): Transaction
    {
        $this->serviceId = $serviceId;
        return $this;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): Transaction
    {
        $this->amount = $amount;
        return $this;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): Transaction
    {
        $this->currency = $currency;
        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): Transaction
    {
        $this->title = $title;
        return $this;
    }

    public function getOrderId(): string
    {
        return $this->orderId;
    }

    public function setOrderId(string $orderId): Transaction
    {
        $this->orderId = $orderId;
        return $this;
    }

    public function getPaymentMethod(): string
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod(string $paymentMethod): Transaction
    {
        $this->paymentMethod = $paymentMethod;
        return $this;
    }

    public function getPaymentMethodCode(): string
    {
        return $this->paymentMethodCode;
    }

    public function setPaymentMethodCode(string $paymentMethodCode): Transaction
    {
        $this->paymentMethodCode = $paymentMethodCode;
        return $this;
    }
}
