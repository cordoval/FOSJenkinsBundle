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

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;

/**
 * FOSJenkinsExtension.
 *
 * @author Hugo Hamon <hugo.hamon@sensio.com>
 * @author William DURAND <william.durand1@gmail.com>
 */
class FOSJenkinsExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $loader->load('jenkins.xml');

        $configuration = new Configuration();
        $processor = new Processor();
        $config = $processor->process($configuration->getConfigTree(), $configs);

        $container->setParameter('jenkins.endpoint', $config['endpoint']);
        $container->setParameter('jenkins.builds.summary.rss_uri', $config['endpoint'] . '/rssAll');
    }

    public function getNamespace()
    {
        return 'http://symfony.com/schema/dic/symfony';
    }

    public function getAlias()
    {
        return 'fos_jenkins';
    }
}
