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

use FOS\Bundle\JenkinsBundle\DependencyInjection\Configuration;

class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    public function testGetConfigTree()
    {
        $configuration = new Configuration();
        $this->assertInstanceOf('Symfony\Component\Config\Definition\ArrayNode', $configuration->getConfigTree());
    }
}