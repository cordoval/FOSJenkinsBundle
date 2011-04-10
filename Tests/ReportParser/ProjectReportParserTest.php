<?php

/*
 * This file is part of the FOSJenkins package.
 *
 * (c) Hugo Hamon <hugo.hamon@sensio.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FOS\Bundle\JenkinsBundle\Tests\ReportParser;

use FOS\Bundle\JenkinsBundle\ReportParser\ProjectReportParser;

class ProjectReportParserTest extends \PHPUnit_Framework_TestCase
{
    private $parser;

    protected function setup()
    {
        $this->parser = new ProjectReportParser();
        $this->parser->setPath(__DIR__.'/../Fixtures/builds-summary.json');
        $this->parser->parse();
    }

    public function testGetPath()
    {
        $this->assertEquals(__DIR__.'/../Fixtures/builds-summary.json', $this->parser->getPath());
    }

    public function testGetData()
    {
        $data = $this->parser->getData();

        $this->assertTrue(is_array($data));
        $this->assertEquals('Syndication', $data['job.name']);
        $this->assertEquals('Syndication', $data['job.display_name']);
        $this->assertEquals('Continuous Integration for the Syndication component', $data['job.description']);
        $this->assertEquals('http://localhost:8080/job/Syndication/', $data['job.url']);
        $this->assertTrue($data['job.buildable']);
        $this->assertEquals(30, $data['job.builds.count']);
        $this->assertEquals(1, $data['job.builds.first']);
        $this->assertEquals(30, $data['job.builds.last']);
        $this->assertEquals(27, $data['job.builds.last_completed']);
        $this->assertEquals(11, $data['job.builds.last_failed']);
        $this->assertEquals(29, $data['job.builds.last_stable']);
        $this->assertEquals(30, $data['job.builds.last_successful']);
        $this->assertEquals(18, $data['job.builds.last_unstable']);
        $this->assertEquals(26, $data['job.builds.last_unsuccessful']);
        $this->assertEquals(31, $data['job.builds.next']);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testParseThrowsRuntimeException()
    {
        $parser = new ProjectReportParser();
        $parser->setPath(__DIR__.'/../Fixtures/fake.txt');
        $parser->parse();
    }
}