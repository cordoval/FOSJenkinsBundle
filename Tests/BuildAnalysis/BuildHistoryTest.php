<?php

/*
 * This file is part of the FOSJenkins package.
 *
 * (c) Hugo Hamon <hugo.hamon@sensio.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FOS\Bundle\JenkinsBundle\Tests\Logger\Build;

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
        $logger = new BuildHistory($this->xml);
        $this->assertEquals(10, count($logger->getBuilds()));
    }

    public function testIsBuildSuccessfull()
    {
        $logger = new BuildHistory($this->xml);
        $this->assertTrue($logger->isBuildSuccessfull('stable'));
        $this->assertTrue($logger->isBuildSuccessfull('back to normal'));
        $this->assertFalse($logger->isBuildSuccessfull('1 test started to fail'));
        $this->assertFalse($logger->isBuildSuccessfull('2 tests started to fail'));
    }

    public function testGetLastSuccessfullBuildsCount()
    {
        $logger = new BuildHistory($this->xml);
        $this->assertEquals(9, $logger->getLastSuccessfullBuildsCount());
    }

    public function testGetLastFailedBuildsCount()
    {
        $logger = new BuildHistory($this->xml);
        $this->assertEquals(1, $logger->getLastFailedBuildsCount());
    }
}