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

use FOS\Bundle\JenkinsBundle\BuildAnalysis\BuildHistory;

class BuildHistoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \SimpleXmlElement
     */
    private $xml;

    protected function setup()
    {
        $this->xml = new \SimpleXmlElement(file_get_contents(__DIR__.'/../Fixtures/last-builds.xml'));
    }

    protected function tearDown()
    {
        $this->xml = null;
    }

    public function testGetBuilds()
    {
        $history = new BuildHistory($this->xml);
        $this->assertEquals(10, count($history->getBuilds()));
    }

    public function testIsBuildSuccessfull()
    {
        $history = new BuildHistory($this->xml);
        $this->assertTrue($history->isBuildSuccessfull('stable'));
        $this->assertTrue($history->isBuildSuccessfull('back to normal'));
        $this->assertFalse($history->isBuildSuccessfull('1 test started to fail'));
        $this->assertFalse($history->isBuildSuccessfull('2 tests started to fail'));
    }

    public function testGetLastSuccessfullBuildsCount()
    {
        $history = new BuildHistory($this->xml);
        $this->assertEquals(9, $history->getLastSuccessfullBuildsCount());
    }

    public function testGetLastFailedBuildsCount()
    {
        $history = new BuildHistory($this->xml);
        $this->assertEquals(1, $history->getLastFailedBuildsCount());
    }

    public function testGetMostRecentBuild()
    {
        $history = new BuildHistory($this->xml);
        $this->assertEquals(28, $history->getMostRecentBuild()->getNumber());
    }
}