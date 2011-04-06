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

use FOS\Bundle\JenkinsBundle\BuildAnalysis\Commit;

class CommitTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Commit
     */
    private $commit;

    /**
     * @var array
     */
    private $files = array(
        'build.xml', 
        'Resources/config/jenkins.xml'
    );

    protected function setup()
    {
        $this->commit = new Commit(42, 'Hugo Hamon', 'Added unit tests', '2011-04-06T22:30:30Z', $this->files);
    }

    public function testGetBuild()
    {
        return $this->assertEquals(42, $this->commit->getBuild());
    }

    public function testGetAuthor()
    {
        return $this->assertEquals('Hugo Hamon', $this->commit->getAuthor());
    }

    public function testGetMessage()
    {
        return $this->assertEquals('Added unit tests', $this->commit->getMessage());
    }

    public function testGetDate()
    {
        return $this->assertEquals('2011-04-06T22:30:30Z', $this->commit->getDate());
    }

    public function testGetFiles()
    {
        return $this->assertEquals($this->files, $this->commit->getFiles());
    }
}