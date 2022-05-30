<?php
declare(strict_types=1);

namespace Softify\SyliusImojePlugin\Exception;

final class MissingTransactionIdException extends \InvalidArgumentException
{
    public static function withMessage(int $paymentId): self
    {
        return new self(sprintf('Missing transaction id in payment details "%s"', $paymentId));
    }
}
