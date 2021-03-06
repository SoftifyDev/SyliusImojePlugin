<?php

declare(strict_types=1);

namespace Softify\SyliusImojePlugin\DependencyInjection;

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
            ->end();

        return $treeBuilder;
    }
}
