<?php

/*
 * This file is part of the FOSJenkins package.
 *
 * (c) Hugo Hamon <hugo.hamon@sensio.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FOS\Bundle\JenkinsBundle\Tests\BuildAnalysis;

use FOS\Bundle\JenkinsBundle\BuildAnalysis\Build;

class BuildTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Build
     */
    private $build;

    protected function setup()
    {
        $this->build = new Build(42, 'Build stable', 'http://localhost:8080/job/Syndication/42/', '2011-04-06T22:30:30Z', true);
    }

    public function testGetNumber()
    {
        return $this->assertEquals(42, $this->build->getNumber());
    }

    public function testGetStatus()
    {
        return $this->assertEquals('Build stable', $this->build->getStatus());
    }

    public function testGetUri()
    {
        return $this->assertEquals('http://localhost:8080/job/Syndication/42/', $this->build->getUri());
    }

    public function testGetDate()
    {
        return $this->assertEquals('2011-04-06T22:30:30Z', $this->build->getDate());
    }

    public function testIsSucceeded()
    {
        return $this->assertTrue($this->build->isSucceeded());
    }
}