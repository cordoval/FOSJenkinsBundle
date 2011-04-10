<?php

/*
 * This file is part of the FOSJenkins package.
 *
 * (c) Hugo Hamon <hugo.hamon@sensio.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FOS\Bundle\JenkinsBundle\Tests\Parser;

use FOS\Bundle\JenkinsBundle\ReportParser\SingleBuildReportParser;

class SingleBuildReportParserTest extends \PHPUnit_Framework_TestCase
{
    private $parser;

    protected function setup()
    {
        $this->parser = new SingleBuildReportParser();
        $this->parser->setPath(__DIR__.'/../Fixtures/build.json');
        $this->parser->parse();
    }

    public function testGetPath()
    {
        $this->assertEquals(__DIR__.'/../Fixtures/build.json', $this->parser->getPath());
    }

    public function testGetData()
    {
        $data = $this->parser->getData();

        $this->assertTrue(is_array($data));
        $this->assertEquals(1, $data['job.tests.failed_count']);
        $this->assertEquals(0, $data['job.tests.skipped_count']);
        $this->assertEquals(40, $data['job.tests.total_count']);
        $this->assertEquals(39, $data['job.tests.passed_count']);
        $this->assertEquals(97.5, $data['job.tests.passed_rate']);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testParseThrowsRuntimeExceptionForNonJsonOutput()
    {
        $parser = new SingleBuildReportParser();
        $parser->setPath(__DIR__.'/../Fixtures/fake.txt');
        $parser->parse();
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testParseThrowsRuntimeExceptionForInvalidJsonOutput()
    {
        $parser = new SingleBuildReportParser();
        $parser->setPath(__DIR__.'/../Fixtures/invalid.json');
        $parser->parse();
    }
}