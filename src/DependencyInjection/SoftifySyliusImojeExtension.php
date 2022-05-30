<?php

declare(strict_types=1);

namespace Softify\SyliusImojePlugin\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

final class SoftifySyliusImojeExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configs = $this->loadDefaultValues($configs, $container);
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter($this->getAlias() . '.order_model_class', $config['order_model_class']);
        $container->setParameter($this->getAlias() . '.payment_security_token_model_class', $config['payment_security_token_model_class']);
        $container->setParameter($this->getAlias() . '.ips', $config['ips']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');
    }

    protected function loadDefaultValues(array $configs, ContainerBuilder $container): array
    {
        if (empty($configs[0]['order_model_class'])) {
            $configs[0]['order_model_class'] = $container->getParameter('sylius.model.order.class');
        }
        if (empty($configs[0]['payment_security_token_model_class'])) {
            $configs[0]['payment_security_token_model_class'] = $container->getParameter('sylius.model.payment_security_token.class');
        }
        return $configs;
    }
}
