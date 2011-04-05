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
        $this->data = array(
            'builds'         => $this->history->getBuilds(),
            'builds_success' => $this->history->getLastSuccessfullBuildsCount(),
            'builds_failed'  => $this->history->getLastFailedBuildsCount(),
        );

        $this->data['endpoint'] = $this->endpoint;
    }

    /**
     * Returns the last build instance.
     *
     * @return FOS\Bundle\JenkinsBundle\BuildAnalysis\Build
     */
    public function getLastBuild()
    {
        return $this->data['builds'][0];
    }

    /**
     * Returns whether or not the last build of the history is successfull.
     *
     * @return Boolean
     */
    public function isLastBuildSuccessfull()
    {
        return (Boolean) $this->getLastBuild()->isSucceeded();
    }

    /**
     * Returns the last build number.
     *
     * @return integer
     */
    public function getLastBuildNumber()
    {
        return $this->getLastBuild()->getNumber();
    }

    /**
     * Returns the number of successfull builds.
     *
     * @return integer
     */
    public function getSuccessfullBuildsCount()
    {
        return $this->data['builds_success'];
    }

    /**
     * Returns the number of failed builds.
     *
     * @return integer
     */
    public function getFailedBuildsCount()
    {
        return $this->data['builds_failed'];
    }

    /**
     * Returns whether or not there are some failed builds in the history.
     *
     * @return Boolean
     */
    public function hasFailedBuilds()
    {
        return $this->getFailedBuildsCount() > 0;
    }

    /**
     * Returns a collection of the last recorded builds.
     *
     * @return array The builds history
     */
    public function getBuilds()
    {
        return $this->data['builds'];
    }

    /**
     * Returns a build information from its single identifier.
     *
     * @param  integer    $number The build number
     * @return array|null $match  The build data
     */
    public function getBuild($number)
    {
        foreach ($this->data['builds'] as $build) {
            if ($build->getNumber() == $number) {
                return $build;
            }
        }

        return null;
    }

    /**
     * Returns the Jenkins project endpoint.
     *
     * @return string The Jenkins project endpoint.
     */
    public function getEndPoint()
    {
        return $this->data['endpoint'];
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'jenkins';
    }
}
