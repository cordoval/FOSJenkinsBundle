<?php

/*
 * This file is part of the FOSJenkins package.
 *
 * (c) Hugo Hamon <hugo.hamon@sensio.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FOS\Bundle\JenkinsBundle\BuildAnalysis;

/**
 * The UnitTestSuite class represents a single build in Jenkins.
 *
 * @author Hugo Hamon <hugo.hamon@sensio.com>
 */
class TestSuite
{
    private $failedTestsCount;

    private $skippedTestsCount;

    private $totalTestsCount;

    public function __construct($totalTestsCount, $failedTestsCount, $skippedTestsCount = 0)
    {
        $this->failedTestsCount  = (int) $failedTestsCount;
        $this->totalTestsCount   = (int) $totalTestsCount;
        $this->skippedTestsCount = (int) $skippedTestsCount;
    }

    public function getFailedTestsCount()
    {
        return $this->failedTestsCount;
    }

    public function getSkippedTestsCount()
    {
        return $this->skippedTestsCount;
    }

    public function getTotalTestsCount()
    {
        return $this->totalTestsCount;
    }

    public function getPassedTestsCount()
    {
        return $this->getTotalTestsCount() - $this->getFailedTestsCount();
    }

    public function getPassedTestsRate()
    {
        $rate = 0;

        if (0 !== $this->getTotalTestsCount()) {
            $rate = $this->getPassedTestsCount() / $this->getTotalTestsCount();
            $rate = round($rate * 100, 2);
        }

        return $rate;
    }
}
