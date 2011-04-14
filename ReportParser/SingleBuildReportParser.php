<?php

/*
 * This file is part of the FOSJenkins package.
 *
 * (c) Hugo Hamon <hugo.hamon@sensio.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FOS\Bundle\JenkinsBundle\ReportParser;

use FOS\Bundle\JenkinsBundle\BuildAnalysis\TestSuite;

/**
 * The SingleBuildReportParser class gets test suite data from a JSON output
 * sent by the Jenkins' API tool and converts to a pure PHP associative array.
 *
 * @author Hugo Hamon <hugo.hamon@sensio.com>
 * @author William Durand <william.durand1@gmail.com>
 */
class SingleBuildReportParser extends ReportParser
{
    /**
     * {@inheritDoc}
     */
    public function parse()
    {
        if ($data = $this->fetchData()) {

            $totalCount = isset($data->totalCount) ? $data->totalCount : 0;
            $failCount  = isset($data->failCount)  ? $data->failCount  : 0;
            $skipCount  = isset($data->skipCount)  ? $data->skipCount  : 0;

            $suite = new TestSuite($totalCount, $failCount,  $skipCount);

            $this->data = array(
                'job.tests.failed_count'  => $suite->getFailedTestsCount(),
                'job.tests.skipped_count' => $suite->getSkippedTestsCount(),
                'job.tests.total_count'   => $suite->getTotalTestsCount(),
                'job.tests.passed_count'  => $suite->getPassedTestsCount(),
                'job.tests.passed_rate'   => $suite->getPassedTestsRate(),
            );
        }
    }

    /**
     * Gets the JSON output from the API uri and converts it to a pure stdClass
     * object.
     *
     * The uri looks like the following scheme:
     *
     *     http://localhost:8080/job/<ProjectName>/<BuildNumber>/api/json
     *
     * @return stdClass
     */
    private function fetchData()
    {
        $data = json_decode(file_get_contents($this->path));

        if (null === $data) {
            throw new \RuntimeException(sprintf('Unable to fetch test suite report from "%s". Ensure the path is valid or the server is running.', $this->path));
        }

        if (!isset($data->actions)) {
            throw new \UnexpectedValueException('Unable to get test suite data from the JSON output.');
        }

        $stats = null;
        if (is_array($data->actions)) {
            $stats = array_pop($data->actions);
        }

        return $stats;
    }
}
