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

use FOS\Bundle\JenkinsBundle\Logger\Build\BuildsSummaryLogger;
use FOS\Bundle\JenkinsBundle\DataCollector\JenkinsDataCollector;

class JenkinsDataCollectorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \SimpleXmlElement
     */
    private $xml;

    protected function setup()
    {
        $this->endpoint = 'http://foo.com/bar';
        $this->logger = new BuildsSummaryLogger(new  \SimpleXmlElement(file_get_contents(__DIR__.'/../Fixtures/last-builds.xml')));
    }

    protected function tearDown()
    {
        $this->logger = null;
    }

    public function testCollect()
    {
        $request = $this->getMock('Symfony\Component\HttpFoundation\Request');
        $response = $this->getMock('Symfony\Component\HttpFoundation\Response');

        $collector = new JenkinsDataCollector($this->logger, $this->endpoint);
        $this->assertEquals(0, count($collector->getBuilds()));

        $collector->collect($request, $response);
        $this->assertEquals(10, count($collector->getBuilds()));
    }

    public function testIsLastBuildSuccessfull()
    {
        $collector = $this->getCollector();
        $this->assertTrue($collector->isLastBuildSuccessfull());
    }

    public function testGetLastBuildNumber()
    {
        $collector = $this->getCollector();
        $this->assertEquals(28, $collector->getLastBuildNumber());
    }

    public function testGetSuccessfullBuildsCount()
    {
        $collector = $this->getCollector();
        $this->assertEquals(9, $collector->getSuccessfullBuildsCount());
    }

    public function testGetFailedBuildsCount()
    {
        $collector = $this->getCollector();
        $this->assertEquals(1, $collector->getFailedBuildsCount());
    }

    public function testHasFailedBuilds()
    {
        $collector = $this->getCollector();
        $this->assertTrue($collector->hasFailedBuilds());
    }

    public function testGetBuilds()
    {
        $collector = $this->getCollector();
        $this->assertEquals(10, count($collector->getBuilds()));
    }

    public function testGetBuild()
    {
        $collector = $this->getCollector();
        $this->assertNotNull($collector->getBuild(26));
        $this->assertNull($collector->getBuild(10));
    }

    public function testGetName()
    {
        $collector = $this->getCollector();
        $this->assertEquals('jenkins', $collector->getName());
    }

    private function getCollector()
    {
        $request = $this->getMock('Symfony\Component\HttpFoundation\Request');
        $response = $this->getMock('Symfony\Component\HttpFoundation\Response');

        $collector = new JenkinsDataCollector($this->logger, $this->endpoint);
        $collector->collect($request, $response);

        return $collector;
    }
}