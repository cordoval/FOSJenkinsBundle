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

use FOS\Bundle\JenkinsBundle\BuildAnalysis\CommitHistory;

class CommitHistoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \SimpleXmlElement
     */
    private $xml;

    protected function setup()
    {
        $this->xml = new \SimpleXmlElement(file_get_contents(__DIR__.'/../Fixtures/last-commits.xml'));
    }

    protected function tearDown()
    {
        $this->xml = null;
    }

    public function testGetCommits()
    {
        $history = new CommitHistory($this->xml);
        $commits = $history->getCommits();

        $this->assertEquals(23, count($commits));
        $this->assertEquals(28, $commits[0]->getBuild());
    }
}