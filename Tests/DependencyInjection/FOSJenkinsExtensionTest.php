<?php

/*
 * This file is part of the FOSJenkins package.
 *
 * (c) Hugo Hamon <hugo.hamon@sensio.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FOS\Bundle\JenkinsBundle\Tests\DependencyInjection;

use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;

use FOS\Bundle\JenkinsBundle\DependencyInjection\FOSJenkinsExtension;

class FOSJenkinsExtensionTest extends \PHPUnit_Framework_TestCase
{
    const JENKINS_ENDPOINT = 'http://localhost:8080/job/ProjectName/';
    public function testLoad()
    {
        $container = new ContainerBuilder();

        $extension = new FOSJenkinsExtension();
        $extension->load($this->getConfigs(), $container);

        $this->assertFalse($container->getParameter('jenkins.enabled'));
        $this->assertEquals(self::JENKINS_ENDPOINT, $container->getParameter('jenkins.endpoint'));
        $this->assertEquals(self::JENKINS_ENDPOINT.'/rssAll', $container->getParameter('jenkins.builds.builds.rss_uri'));
        $this->assertEquals(self::JENKINS_ENDPOINT.'/rssChangelog', $container->getParameter('jenkins.builds.commits.rss_uri'));
    }

    public function testGetAlias()
    {
        $extension = new FOSJenkinsExtension();
        $this->assertEquals('fos_jenkins', $extension->getAlias());
    }

    public function testGetNamespace()
    {
        $extension = new FOSJenkinsExtension();
        $this->assertEquals('http://symfony.com/schema/dic/symfony', $extension->getNamespace());
    }

    private function getConfigs()
    {
        return array(
            array(
                'enabled'  => false,
                'endpoint' => 'http://localhost:8080/job/ProjectName/' 
            )
        );
    }
}