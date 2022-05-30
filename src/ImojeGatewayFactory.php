<?php

declare(strict_types=1);

namespace Softify\SyliusImojePlugin;

use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\GatewayFactory;
use Softify\SyliusImojePlugin\Api\Api;
use Softify\SyliusImojePlugin\Api\ApiInterface;

final class ImojeGatewayFactory extends GatewayFactory
{
    public const GATEWAY_NAME = 'imoje';

    protected function populateConfig(ArrayObject $config): void
    {
        $config->defaults([
            'payum.factory_name' => self::GATEWAY_NAME,
            'payum.factory_title' => self::GATEWAY_NAME,
        ]);

        if (false === (bool) $config['payum.api']) {
            $config['payum.default_options'] = [
                'environment' => 'sandbox',
                'debug_mode' => false,
                'authorization_token' => '',
                'merchant_id' => '',
                'service_id' => '',
                'service_key' => '',
            ];
            $config->defaults($config['payum.default_options']);

            $config['payum.required_options'] = ['environment', 'authorization_token', 'merchant_id', 'service_id', 'service_key'];

            $config['payum.api'] = static function (ArrayObject $config): ApiInterface {
                $config->validateNotEmpty($config['payum.required_options']);

                return new Api(
                    $config['environment'],
                    $config['authorization_token'],
                    $config['merchant_id'],
                    $config['service_id'],
                    $config['service_key'],
                    $config['debug_mode'],
                );
            };
        }
    }
}
