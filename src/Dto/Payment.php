<?php

declare(strict_types=1);

namespace Softify\SyliusImojePlugin\Dto;

use DateTime;

class Payment
{
    private string $id;
    private ?string $title;
    private int $amount;
    private string $status;
    private DateTime $created;
    private string $orderId;
    private string $currency;
    private DateTime $modified;
    private string $serviceId;
    private string $notificationUrl;
    private string $returnUrl;
    private string $successReturnUrl;
    private string $failureReturnUrl;
    private string $url;
    private bool $isActive;
    private ?DateTime $validTo;
    private Customer $customer;
    private bool $isGenerated;
    private bool $isUsed;
    private ?DateTime $usedAt;
    private bool $isConfirmVisited;
    private ?DateTime $confirmVisitedAt;
    private array $transactions;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): Payment
    {
        $this->id = $id;
        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): Payment
    {
        $this->title = $title;
        return $this;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): Payment
    {
        $this->amount = $amount;
        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): Payment
    {
        $this->status = $status;
        return $this;
    }

    public function getCreated(): DateTime
    {
        return $this->created;
    }

    public function setCreated(DateTime $created): Payment
    {
        $this->created = $created;
        return $this;
    }

    public function getOrderId(): string
    {
        return $this->orderId;
    }

    public function setOrderId(string $orderId): Payment
    {
        $this->orderId = $orderId;
        return $this;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): Payment
    {
        $this->currency = $currency;
        return $this;
    }

    public function getModified(): DateTime
    {
        return $this->modified;
    }

    public function setModified(DateTime $modified): Payment
    {
        $this->modified = $modified;
        return $this;
    }

    public function getServiceId(): string
    {
        return $this->serviceId;
    }

    public function setServiceId(string $serviceId): Payment
    {
        $this->serviceId = $serviceId;
        return $this;
    }

    public function getNotificationUrl(): string
    {
        return $this->notificationUrl;
    }

    public function setNotificationUrl(string $notificationUrl): Payment
    {
        $this->notificationUrl = $notificationUrl;
        return $this;
    }

    public function getReturnUrl(): string
    {
        return $this->returnUrl;
    }

    public function setReturnUrl(string $returnUrl): Payment
    {
        $this->returnUrl = $returnUrl;
        return $this;
    }

    public function getSuccessReturnUrl(): string
    {
        return $this->successReturnUrl;
    }

    public function setSuccessReturnUrl(string $successReturnUrl): Payment
    {
        $this->successReturnUrl = $successReturnUrl;
        return $this;
    }

    public function getFailureReturnUrl(): string
    {
        return $this->failureReturnUrl;
    }

    public function setFailureReturnUrl(string $failureReturnUrl): Payment
    {
        $this->failureReturnUrl = $failureReturnUrl;
        return $this;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): Payment
    {
        $this->url = $url;
        return $this;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): Payment
    {
        $this->isActive = $isActive;
        return $this;
    }

    public function getValidTo(): ?DateTime
    {
        return $this->validTo;
    }

    public function setValidTo(?DateTime $validTo): Payment
    {
        $this->validTo = $validTo;
        return $this;
    }

    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    public function setCustomer(Customer $customer): Payment
    {
        $this->customer = $customer;
        return $this;
    }

    public function isGenerated(): bool
    {
        return $this->isGenerated;
    }

    public function setIsGenerated(bool $isGenerated): Payment
    {
        $this->isGenerated = $isGenerated;
        return $this;
    }

    public function isUsed(): bool
    {
        return $this->isUsed;
    }

    public function setIsUsed(bool $isUsed): Payment
    {
        $this->isUsed = $isUsed;
        return $this;
    }

    public function getUsedAt(): ?DateTime
    {
        return $this->usedAt;
    }

    public function setUsedAt(?DateTime $usedAt): Payment
    {
        $this->usedAt = $usedAt;
        return $this;
    }

    public function isConfirmVisited(): bool
    {
        return $this->isConfirmVisited;
    }

    public function setIsConfirmVisited(bool $isConfirmVisited): Payment
    {
        $this->isConfirmVisited = $isConfirmVisited;
        return $this;
    }

    public function getConfirmVisitedAt(): ?DateTime
    {
        return $this->confirmVisitedAt;
    }

    public function setConfirmVisitedAt(?DateTime $confirmVisitedAt): Payment
    {
        $this->confirmVisitedAt = $confirmVisitedAt;
        return $this;
    }

    public function getTransactions(): array
    {
        return $this->transactions;
    }

    public function setTransactions(array $transactions): Payment
    {
        $this->transactions = $transactions;
        return $this;
    }

    public function addTransaction(Transaction $transaction): Payment
    {
        $this->transactions[] = $transaction;
        return $this;
    }
}
