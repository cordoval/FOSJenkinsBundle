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

use FOS\Bundle\JenkinsBundle\Parser\JobDataParser;
use FOS\Bundle\JenkinsBundle\Parser\JobTestSuiteParser;

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
     * The Jenkins project endpoint
     * 
     * @var string
     */
    private $endpoint;

    /**
     * A JobDataParser instance.
     *
     * @var FOS\Bundle\JenkinsBundle\Parser\JobDataParser
     */
    private $jobDataParser;

    /**
     * A JobTestSuiteParser instance.
     *
     * @var FOS\Bundle\JenkinsBundle\Parser\JobTestSuiteParser
     */
    private $jobTestSuiteParser;

    /**
     * Constructor.
     *
     * @param string $endpoint A Jenkins project endpoint
     */
    public function __construct($endpoint)
    {
        $this->endpoint = $endpoint;
    }

    /**
     * Sets the JobDataParser instance.
     *
     * @param JobDataParser $parser
     */
    public function setJobDataParser(JobDataParser $parser)
    {
        $this->jobDataParser = $parser;
    }

    /**
     * Sets the JobTestSuiteParser instance.
     *
     * @param JobDataParser $parser
     */
    public function setJobTestSuiteParser(JobTestSuiteParser $parser)
    {
        $this->jobTestSuiteParser = $parser;
    }

    /**
     * {@inheritdoc}
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->data['job.endpoint'] = $this->endpoint;

        if ($this->jobDataParser) {
            $this->jobDataParser->parse();
            $this->data = array_merge($this->data, $this->jobDataParser->getData());
        }

        if ($this->jobTestSuiteParser) {
            $this->jobTestSuiteParser->parse();
            $this->data = array_merge($this->data, $this->jobTestSuiteParser->getData());
        }
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
