<?php

/*
 * This file is part of the FOSJenkins package.
 *
 * (c) Hugo Hamon <hugo.hamon@sensio.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FOS\Bundle\JenkinsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * This class contains the configuration information for the bundle.
 *
 * This information is solely responsible for how the different configuration
 * sections are normalized, and merged.
 *
 * @author Hugo Hamon <hugo.hamon@sensio.com>
 */
class Configuration
{
    /**
     * Generates the configuration tree.
     *
     * @return \Symfony\Component\Config\Definition\ArrayNode The config tree
     */
    public function getConfigTree()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('fos_jenkins');

        $rootNode
            ->children()
                ->arrayNode('builds')
                    ->children()
                        ->arrayNode('summary')
                            ->children()
                                ->scalarNode('rss_uri')
                                
                                // This does not work for now...
                                
                                /*->validate()
                                    ->ifTrue(function($v) {
                                        return $v === filter_var($v, FILTER_VALIDATE_URL, FILTER_FLAG_SCHEME_REQUIRED);
                                    })
                                    ->thenInvalid('%s must be a valid uri.')
                                ->end()*/
                            ->end()    
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder->buildTree();
    }
}
