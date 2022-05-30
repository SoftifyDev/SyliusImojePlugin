<?php

declare(strict_types=1);

namespace Softify\SyliusImojePlugin\DependencyInjection;

use Payum\Core\Exception\LogicException;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('softify_sylius_imoje');
        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->children()
                ->arrayNode('ips')
                    ->defaultValue([
                        "5.196.116.32/28",
                        "51.195.95.0/28",
                        "54.37.185.64/28",
                        "54.37.185.80/28",
                        "147.135.151.16/28",
                        "127.0.0.1"
                    ])
                    ->scalarPrototype()->end()
                ->end()
                ->scalarNode('order_model_class')
                    ->cannotBeEmpty()
                    ->validate()
                        ->ifTrue(function($value) {
                            if (false === class_exists($value)) {
                                throw new LogicException(sprintf(
                                    'The storage entry must be a valid model class. It is set %s',
                                    $value
                                ));
                            }
                            return false;
                        })
                        ->thenInvalid('A message')
                    ->end()
                ->end()
                ->scalarNode('payment_security_token_model_class')
                    ->cannotBeEmpty()
                    ->validate()
                        ->ifTrue(function($value) {
                            if (false === class_exists($value)) {
                                throw new LogicException(sprintf(
                                    'The storage entry must be a valid model class. It is set %s',
                                    $value
                                ));
                            }
                            return false;
                        })
                        ->thenInvalid('A message')
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
