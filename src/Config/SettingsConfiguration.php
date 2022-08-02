<?php

declare(strict_types=1);

namespace PvListManager\Config;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class SettingsConfiguration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('pvListSettings');

        // ... add node definitions to the root of the tree
        // $treeBuilder->getRootNode()->...
        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('listStorageFolder')->defaultValue('lists/')->end()
            ->end()
            ->children()
                ->arrayNode('generatedLists')
                    ->children()
                        ->scalarNode('allowList')->defaultValue('pv-customer-allowlist.txt')->end()
                        ->scalarNode('blocklist')->defaultValue('pv-custom-blocklist.txt')->end()
                    ->end()
                ->end()
            ->end()
            ->children()
                ->arrayNode('sources')
                    ->children()
                        ->scalarNode('source')->end()
                    ->end()
                ->end()
            ->end()
;

        return $treeBuilder;
    }
}