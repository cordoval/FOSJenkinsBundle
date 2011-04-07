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

use FOS\Bundle\JenkinsBundle\BuildAnalysis\BuildHistory;

/**
 * The JenkinsDataCollector collector class collects builds information
 * from an RSS feed coming from the Jenkins continuous integration server.
 *
 * @author Hugo Hamon <hugo.hamon@sensio.com>
 * @author William Durand <william.durand1@gmail.com>
 */
class JenkinsDataCollector extends DataCollector
{
    /**
     * The BuildsSummaryLogger instance.
     *
     * @var \FOS\Bundle\JenkinsBundle\BuildAnalysis\BuildHistory
     */
    private $history;

    /**
     * The Jenkins project endpoint
     * 
     * @var string
     */
    private $endpoint;

    /**
     * Constructor.
     *
     * @param BuildHistory $history A BuildHistory instance
     * @param string $endpoint A Jenkins project endpoint
     */
    public function __construct(BuildHistory $history, $endpoint)
    {
        $this->history = $history;
        $this->endpoint = $endpoint;
    }

    /**
     * {@inheritdoc}
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $data = json_decode(file_get_contents(sprintf('%s/api/json', $this->endpoint)));

        $this->data = array(
            'project.endpoint'         => $this->endpoint,
            'project.name'             => $data->name,
            'project.display_name'     => $data->displayName,
            'project.description'      => $data->description,
            'project.url'              => $data->url,
            'project.buildable'        => $data->buildable,
            'builds.count'             => count($data->builds),
            'builds.first'             => $data->firstBuild->number,
            'builds.last'              => $data->lastBuild->number,
            'builds.last.completed'    => $data->lastCompletedBuild->number,
            'builds.last.failed'       => $data->lastFailedBuild->number,
            'builds.last.stable'       => $data->lastStableBuild->number,
            'builds.last.successful'   => $data->lastSuccessfulBuild->number,
            'builds.last.unstable'     => $data->lastUnstableBuild->number,
            'builds.last.unsuccessful' => $data->lastUnsuccessfulBuild->number,
            'builds.next'              => $data->nextBuildNumber,
        );

        $lastBuild = json_decode(file_get_contents(sprintf('%s/%u/api/json', $this->endpoint, $data->lastBuild->number)));

        $tests = array_pop($lastBuild->actions);

        $this->data = array_merge($this->data, array(
            'tests.failed_count'  => $tests->failCount,
            'tests.skipped_count' => $tests->skipCount,
            'tests.total_count'   => $tests->totalCount,
        ));
    }

    private function get($key)
    {
        return array_key_exists($key, $this->data) ? $this->data[$key] : null;
    }

    public function getProjectName()
    {
        return $this->get('project.name');
    }

    public function getDisplayName()
    {
        return $this->get('project.display_name');
    }

    public function getDescription()
    {
        return $this->get('project.description');
    }

    public function getUrl()
    {
        return $this->get('project.url');
    }

    public function isBuildable()
    {
        return $this->get('project.buildable');
    }

    public function getBuildsCount()
    {
        return $this->get('builds.count');
    }

    public function getFirstBuild()
    {
        return $this->get('builds.first');
    }

    /**
     * Returns the last build number.
     *
     * @return integer
     */
    public function getLastBuild()
    {
        return $this->get('builds.last');
    }

    public function getLastCompletedBuild()
    {
        return $this->get('builds.last.completed');
    }

    public function getLastFailedBuild()
    {
        return $this->get('builds.last.failed');
    }

    public function getLastStableBuild()
    {
        return $this->get('builds.last.stable');
    }

    public function getLastSuccessfulBuild()
    {
        return $this->get('builds.last.successful');
    }

    public function getLastUnsuccessfulBuild()
    {
        return $this->get('builds.last.unsuccessful');
    }

    public function getLastUnstableBuild()
    {
        return $this->get('builds.last.unstable');
    }

    public function getNextBuild()
    {
        return $this->get('builds.next');
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
     * Returns the number of failed tests.
     *
     * @return integer
     */
    public function getFailedTestsCount()
    {
        return (string) $this->get('tests.failed_count');
    }

    /**
     * Returns the number of skipped tests.
     *
     * @return integer
     */
    public function getSkippedTestsCount()
    {
        return (string) $this->get('tests.skipped_count');
    }

    /**
     * Returns the total number of tests.
     *
     * @return integer
     */
    public function getTotalTestsCount()
    {
        return $this->get('tests.total_count');
    }

    /**
     * Returns the number of successful tests.
     *
     * @return integer
     */
    public function getPassedTestsCount()
    {
        return $this->getTotalTestsCount() - $this->getFailedTestsCount();
    }

    /**
     * Returns the Jenkins project endpoint.
     *
     * @return string The Jenkins project endpoint.
     */
    public function getEndPoint()
    {
        return $this->get('project.endpoint');
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'jenkins';
    }
}
