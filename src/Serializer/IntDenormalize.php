<?php

declare(strict_types=1);

namespace Softify\SyliusImojePlugin\Serializer;

use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class IntDenormalize implements ContextAwareDenormalizerInterface
{
    public const FIELDS = 'fields';

    public function __construct(private DenormalizerInterface $denormalizer)
    {
    }

    public function supportsDenormalization($data, string $type, string $format = null, array $context = []): bool
    {
        if (!empty($context[self::FIELDS])) {
            if (is_array($context[self::FIELDS])) {
                foreach ($context[self::FIELDS] as $field) {
                    if (isset($data[$field])) {
                        return true;
                    }
                }
            } else {
                return isset($data[$context[self::FIELDS]]);
            }
        }
        return false;
    }

    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        if (is_array($context[self::FIELDS])) {
            foreach ($context[self::FIELDS] as $field) {
                if (isset($data[$field])) {
                    $data[$field] = (int)$data[$field];
                }
            }
        } elseif (isset($data[$context[self::FIELDS]])) {
            $data[$context[self::FIELDS]] = (int)$data[$context[self::FIELDS]];
        }

        return $this->denormalizer->denormalize($data, $type, $format, $context);
    }
}
