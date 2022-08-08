<?php

declare(strict_types=1);

namespace PvListManager\Config;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class SettingsConfiguration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('list_manager');

        // ... add node definitions to the root of the tree
        // $treeBuilder->getRootNode()->...
        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('list_storage_folder')->defaultValue('lists/')->end()
            ->end()
            ->children()
                ->arrayNode('generated_lists')
                    ->children()
                        ->scalarNode('allowlist')->defaultValue('custom-allowlist.txt')->end()
                        ->scalarNode('blocklist')->defaultValue('custom-blocklist.txt')->end()
                    ->end()
                ->end() //end generatedLists
            ->end()
            ->children()
                ->arrayNode('block_sources')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('name')->end()
                            ->scalarNode('source')->end()
                            ->booleanNode('enabled')->defaultTrue()->end()
                        ->end()
                    ->end()
                ->end() // end block_sources
                ->arrayNode('allow_sources')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('name')->end()
                            ->scalarNode('source')->end()
                            ->booleanNode('enabled')->defaultTrue()->end()
                        ->end()
                    ->end()
                ->end()//end allow_sources
            ->end()
;

        return $treeBuilder;
    }
}