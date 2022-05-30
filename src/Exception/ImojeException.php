<?php

declare(strict_types=1);

namespace Softify\SyliusImojePlugin\Exception;

use Payum\Core\Exception\Http\HttpException;
use Softify\SyliusImojePlugin\Dto\ApiErrorResponse;
use Softify\SyliusImojePlugin\Dto\Error;

final class ImojeException extends HttpException
{
    public const LABEL = 'ImojeException';

    public static function newInstance(ApiErrorResponse $errorResponse, int $code): self
    {
        $parts = [self::LABEL];

        if ($errorResponse->getCode()) {
            $parts[] = sprintf('[status code] %s (%s)' , $errorResponse->getCode(), $code);
        } else {
            $parts[] = sprintf('[status code] %s' , $code);
        }

        if ($errorResponse->getMessage()) {
            $parts[] = sprintf('[reason literal] %s' , $errorResponse->getMessage());
        }

        /** @var Error $error */
        foreach ($errorResponse->getErrors() as $error) {
            if ($error->getMessage()) {
                $parts[] = sprintf('[reason phrase] %s: %s', $error->getProperty(), $error->getMessage());
            }
        }

        $message = implode(\PHP_EOL, $parts);

        return new ImojeException($message, $code);
    }
}
