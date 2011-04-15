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

use FOS\Bundle\JenkinsBundle\BuildAnalysis\TestSuite;

class TestSuiteTest extends \PHPUnit_Framework_TestCase
{
    private $testSuite;

    protected function setup()
    {
        $this->testSuite = new TestSuite(40, 30, 2);
    }

    public function testGetTotalTestsCount()
    {
        $this->assertEquals(40, $this->testSuite->getTotalTestsCount());
    }

    public function testGetFailedTestsCount()
    {
        $this->assertEquals(30, $this->testSuite->getFailedTestsCount());
    }

    public function testGetSkippedTestsCount()
    {
        $this->assertEquals(2, $this->testSuite->getSkippedTestsCount());
    }

    public function testGetPassedTestsCount()
    {
        $this->assertEquals(10, $this->testSuite->getPassedTestsCount());
    }

    /**
     * @dataProvider provideTestSuiteStatistics
     */
    public function testGetPassedTestsRate($total, $failed, $skipped, $rate)
    {
        $suite = new TestSuite($total, $failed, $skipped);
        $this->assertEquals($rate, $suite->getPassedTestsRate());
    }

    public function provideTestSuiteStatistics()
    {
        return array(
            array(0, 0, 0, 0),
            array(40, 0, 0, 100),
            array(40, 20, 0, 50),
            array(40, 27, 0, 32.50)
        );
    }
}