<?php

/*
 * This file is part of the FOSJenkins package.
 *
 * (c) Hugo Hamon <hugo.hamon@sensio.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FOS\Bundle\JenkinsBundle\Tests;

use FOS\Bundle\JenkinsBundle\FOSJenkinsBundle;

class FOSJenkinsBundleTest extends \PHPUnit_Framework_TestCase
{
    public function testBundleVersion()
    {
        $this->assertEquals('0.1', FOSJenkinsBundle::VERSION);
    }
}