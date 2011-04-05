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

use FOS\Bundle\JenkinsBundle\Logger\Build\BuildsSummaryLogger;

/**
 * The JenkinsDataCollector collector class collects builds information
 * from an RSS feed coming from the Jenkins continuous integration server.
 *
 * @author Hugo Hamon <hugo.hamon@sensio.com>
 * @author William DURAND <william.durand1@gmail.com>
 */
class JenkinsDataCollector extends DataCollector
{
    /**
     * The BuildsSummaryLogger instance.
     *
     * @var \FOS\Bundle\JenkinsBundle\Logger\Build\BuildsSummaryLogger
     */
    private $logger;

    /**
     * The Jenkins project endpoint
     * 
     * @var string
     */
    private $endpoint;

    /**
     * Constructor.
     *
     * @param BuildsSummaryLogger $logger A BuildsSummaryLogger instance
     * @param string $endpoint A Jenkins project endpoint
     */
    public function __construct(BuildsSummaryLogger $logger, $endpoint)
    {
        $this->logger = $logger;
        $this->endpoint = $endpoint;
    }

    /**
     * {@inheritdoc}
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->data = array(
            'builds'         => $this->logger->getBuildsSummary(),
            'builds_success' => $this->logger->getLastSuccessfullBuildsCount(),
            'builds_failed'  => $this->logger->getLastFailedBuildsCount(),
        );

        $this->data['endpoint'] = $this->endpoint;
    }

    /**
     * Returns whether or not the last build of the history is successfull.
     *
     * @return Boolean
     */
    public function isLastBuildSuccessfull()
    {
        return (Boolean) $this->data['builds'][0]['success'];
    }

    /**
     * Returns the last build number.
     *
     * @return integer
     */
    public function getLastBuildNumber()
    {
        return $this->data['builds'][0]['id'];
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
        $match = null;
        foreach ($this->data['builds'] as $build) {
            if ($build['id'] == $number) {
                $match = $build;
            }
        }

        return $match;
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
