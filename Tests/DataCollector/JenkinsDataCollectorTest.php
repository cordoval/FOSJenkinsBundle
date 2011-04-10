<?php

/*
 * This file is part of the FOSJenkins package.
 *
 * (c) Hugo Hamon <hugo.hamon@sensio.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FOS\Bundle\JenkinsBundle\Tests\DataCollector;

use FOS\Bundle\JenkinsBundle\Parser\JobDataParser;
use FOS\Bundle\JenkinsBundle\Parser\JobTestSuiteParser;
use FOS\Bundle\JenkinsBundle\DataCollector\JenkinsDataCollector;

class JenkinsDataCollectorTest extends \PHPUnit_Framework_TestCase
{
    private $collector;

    protected function setup()
    {
        $collector = new JenkinsDataCollector('http://localhost:8080/job/Syndication/');
        $collector->setJobReportUri(__DIR__.'/../Fixtures/builds-summary.json');
        $collector->setLastBuildReportUri(__DIR__.'/../Fixtures/build.json');
        $collector->setJobDataParser(new JobDataParser());
        $collector->setJobTestSuiteParser(new JobTestSuiteParser());

        $request = $this->getMock('Symfony\Component\HttpFoundation\Request');
        $response = $this->getMock('Symfony\Component\HttpFoundation\Response');
        $collector->collect($request, $response);

        $this->collector = $collector;
    }

    protected function tearDown()
    {
        $this->collector = null;
    }

    public function testGetProjectName()
    {
        $this->assertEquals('Syndication', $this->collector->getProjectName());
    }

    public function testGetDisplayName()
    {
        $this->assertEquals('Syndication', $this->collector->getDisplayName());
    }

    public function testGetDescription()
    {
        $this->assertEquals('Continuous Integration for the Syndication component', $this->collector->getDescription());
    }

    public function testGetUrl()
    {
        $this->assertEquals('http://localhost:8080/job/Syndication/', $this->collector->getUrl());
    }

    public function testIsBuildable()
    {
        $this->assertTrue($this->collector->isBuildable());
    }

    public function testGetBuildsCount()
    {
        $this->assertEquals(30, $this->collector->getBuildsCount());
    }

    public function testGetFirstBuild()
    {
        $this->assertEquals(1, $this->collector->getFirstBuild());
    }

    public function testGetLastBuild()
    {
        $this->assertEquals(30, $this->collector->getLastBuild());
    }

    public function testGetLastCompletedBuild()
    {
        $this->assertEquals(27, $this->collector->getLastCompletedBuild());
    }

    public function testGetLastFailedBuild()
    {
        $this->assertEquals(11, $this->collector->getLastFailedBuild());
    }

    public function testGetLastStableBuild()
    {
        $this->assertEquals(29, $this->collector->getLastStableBuild());
    }

    public function testGetLastSuccessfulBuild()
    {
        $this->assertEquals(30, $this->collector->getLastSuccessfulBuild());
    }

    public function testGetLastUnsuccessfulBuild()
    {
        $this->assertEquals(26, $this->collector->getLastUnsuccessfulBuild());
    }

    public function testGetLastUnstableBuild()
    {
        $this->assertEquals(18, $this->collector->getLastUnstableBuild());
    }

    public function testGetNextBuild()
    {
        $this->assertEquals(31, $this->collector->getNextBuild());
    }

    public function testIsLastBuildSuccessful()
    {
        $this->assertTrue($this->collector->isLastBuildSuccessful());
    }

    public function testGetFailedTestsCount()
    {
        $this->assertEquals(1, $this->collector->getFailedTestsCount());
    }

    public function testGetSkippedTestsCount()
    {
        $this->assertEquals(0, $this->collector->getSkippedTestsCount());
    }

    public function testGetTotalTestsCount()
    {
        $this->assertEquals(40, $this->collector->getTotalTestsCount());
    }

    public function testGetPassedTestsRate()
    {
        $this->assertEquals(97.5, $this->collector->getPassedTestsRate());
    }

    public function testGetPassedTestsCount()
    {
        $this->assertEquals(39, $this->collector->getPassedTestsCount());
    }

    public function testGetName()
    {
        $this->assertEquals('jenkins', $this->collector->getName());
    }

    public function testGetEndPoint()
    {
        $this->assertEquals('http://localhost:8080/job/Syndication/', $this->collector->getEndPoint());
    }
}
