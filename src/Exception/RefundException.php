<?php
declare(strict_types=1);

namespace Softify\SyliusImojePlugin\Exception;

final class RefundException extends \InvalidArgumentException
{
    public static function withMessage(string $message): self
    {
        return new self($message);
    }
}
