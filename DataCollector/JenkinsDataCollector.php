<?php

/*
 * This file is part of the FOSJenkins package.
 *
 * (c) Hugo Hamon <hugo.hamon@sensio.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FOS\Bundle\JenkinsBundle\DataCollector;

use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use FOS\Bundle\JenkinsBundle\ReportParser\ReportParserInterface;

/**
 * The JenkinsDataCollector collector class collects builds information
 * from several sources coming from the Jenkins continuous integration server.
 *
 * @author Hugo Hamon <hugo.hamon@sensio.com>
 * @author William Durand <william.durand1@gmail.com>
 */
class JenkinsDataCollector extends DataCollector
{
    /**
     * The Jenkins project endpoint.
     *
     * @var string
     */
    private $endpoint;

    /**
     * Whether or not the Jenkins is enabled.
     *
     * @var Boolean
     */
    private $isEnabled;

    /**
     * An array of registered ReportParserInterface instance.
     *
     * @var array
     */
    private $reports;

    /**
     * Constructor.
     *
     * @param Boolean $isEnabled Whether or not the server is running
     * @param string  $endpoint  A Jenkins project endpoint
     */
    public function __construct($isEnabled, $endpoint)
    {
        $this->data      = array();
        $this->isEnabled = (Boolean) $isEnabled;
        $this->endpoint  = $endpoint;
    }

    /**
     * Registers a new ReportParserInterface instance.
     *
     * @param string $name Name of the ReportParser instance
     * @param ReportParserInterface $report The parser to register
     */
    public function registerReportParser($name, ReportParserInterface $report)
    {
        $this->reports[$name] = $report;
    }

    /**
     * {@inheritdoc}
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        if ($this->isEnabled()) {
            $this->data['job.endpoint'] = $this->endpoint;

            foreach ($this->reports as $report) {
                $report->parse();
                $this->data = array_merge($this->data, $report->getData());
            }
        }
    }

    /**
     * Returns whether or not the Jenkins server is enabled.
     *
     * @return Boolean
     */
    public function isEnabled()
    {
        return $this->isEnabled;
    }

    /**
     * Returns a recorded statistic if it exists in the $data property.
     *
     * @return mixed|null
     */
    private function get($key)
    {
        return array_key_exists($key, $this->data) ? $this->data[$key] : null;
    }

    /**
     * Returns the Jenkins project name.
     *
     * @return string
     */
    public function getProjectName()
    {
        return $this->get('job.name');
    }

    /**
     * Returns the Jenkins display name.
     *
     * @return string
     */
    public function getDisplayName()
    {
        return $this->get('job.display_name');
    }

    /**
     * Returns the Jenkins project description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->get('job.description');
    }

    /**
     * Returns the Jenkins project url.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->get('job.url');
    }

    /**
     * Returns whether or not the job is buildable.
     *
     * @return Boolean
     */
    public function isBuildable()
    {
        return $this->get('job.buildable');
    }

    /**
     * Returns the total number of builds.
     *
     * @return integer
     */
    public function getBuildsCount()
    {
        return $this->get('job.builds.count');
    }

    /**
     * Returns the number of the first build.
     *
     * @return integer
     */
    public function getFirstBuild()
    {
        return $this->get('job.builds.first');
    }

    /**
     * Returns the last build number.
     *
     * @return integer
     */
    public function getLastBuild()
    {
        return $this->get('job.builds.last');
    }

    /**
     * Returns the last completed build number.
     *
     * @return integer
     */
    public function getLastCompletedBuild()
    {
        return $this->get('job.builds.last_completed');
    }

    /**
     * Returns the last failed build number.
     *
     * @return integer
     */
    public function getLastFailedBuild()
    {
        return $this->get('job.builds.last_failed');
    }

    /**
     * Returns the last stabled build number.
     *
     * @return integer
     */
    public function getLastStableBuild()
    {
        return $this->get('job.builds.last_stable');
    }

    /**
     * Returns the last successful build number.
     *
     * @return integer
     */
    public function getLastSuccessfulBuild()
    {
        return $this->get('job.builds.last_successful');
    }

    /**
     * Returns the last unsuccessful build number.
     *
     * @return integer
     */
    public function getLastUnsuccessfulBuild()
    {
        return $this->get('job.builds.last_unsuccessful');
    }

    /**
     * Returns the last unstable build number.
     *
     * @return integer
     */
    public function getLastUnstableBuild()
    {
        return $this->get('job.builds.last_unstable');
    }

    /**
     * Returns the next build number.
     *
     * @return integer
     */
    public function getNextBuild()
    {
        return $this->get('job.builds.next');
    }

    /**
     * Returns whether or not the last build of the history is successful.
     *
     * @return Boolean
     */
    public function isLastBuildSuccessful()
    {
        return $this->getLastBuild() === $this->getLastSuccessfulBuild();
    }

    /**
     * Returns the number of failed job tests.
     *
     * @return integer
     */
    public function getFailedTestsCount()
    {
        return (string) $this->get('job.tests.failed_count');
    }

    /**
     * Returns the number of skipped job tests.
     *
     * @return integer
     */
    public function getSkippedTestsCount()
    {
        return (string) $this->get('job.tests.skipped_count');
    }

    /**
     * Returns the total number of job tests.
     *
     * @return integer
     */
    public function getTotalTestsCount()
    {
        return $this->get('job.tests.total_count');
    }

    /**
     * Returns the rate of passed tests.
     *
     * @return float
     */
    public function getPassedTestsRate()
    {
        return $this->get('job.tests.passed_rate');
    }

    /**
     * Returns the number of successful job tests.
     *
     * @return integer
     */
    public function getPassedTestsCount()
    {
        return $this->get('job.tests.passed_count');
    }

    /**
     * Returns the health color.
     *
     * Color can be grey, yellow, red or blue (blue equals green).
     *
     * @return string
     */
    public function getHealthReportColor()
    {
        return $this->get('job.health_report.color');
    }

    /**
     * Returns the Jenkins project endpoint.
     *
     * @return string The Jenkins project endpoint.
     */
    public function getEndPoint()
    {
        return $this->get('job.endpoint');
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'jenkins';
    }
}
