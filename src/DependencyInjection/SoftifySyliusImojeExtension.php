<?php

declare(strict_types=1);

namespace Softify\SyliusImojePlugin\DependencyInjection;

use Softify\SyliusImojePlugin\ProcessManager\RefundPaymentProcessManager;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

final class SoftifySyliusImojeExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter($this->getAlias() . '.ips', $config['ips']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');

        $bundles = $container->getParameter('kernel.bundles');

        if (!empty($bundles['SyliusRefundPlugin'])) {
            $definition = new Definition(RefundPaymentProcessManager::class);
            $definition->setArgument('$orderRepository', new Reference('sylius.repository.order'));
            $definition->setArgument('$imojePaymentService', new Reference('softify.imoje_plugin.service.imoje_payment'));
            $definition->setTags(
                [
                    'sylius_refund.units_refunded.process_step' => [
                        [
                            'priority' => -200
                        ]
                    ]
                ]
            );
            $container->setDefinition(RefundPaymentProcessManager::class, $definition);
        }
    }
}
