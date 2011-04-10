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

/**
 * The ProjectReportParser class gets data from a JSON output sent by the
 * Jenkins' API tool and converts to a pure PHP associative array.
 *
 * @author Hugo Hamon <hugo.hamon@sensio.com>
 */
class ProjectReportParser extends ReportParser
{
    /**
     * {@inheritDoc}
     */
    public function parse()
    {
        $data = $this->fetchData();

        $this->data = array(
            'job.name'                     => $data->name,
            'job.display_name'             => $data->displayName,
            'job.description'              => $data->description,
            'job.url'                      => $data->url,
            'job.buildable'                => $data->buildable,
            'job.builds.count'             => count($data->builds),
            'job.builds.first'             => $data->firstBuild->number,
            'job.builds.last'              => $data->lastBuild->number,
            'job.builds.last_completed'    => $data->lastCompletedBuild->number,
            'job.builds.last_failed'       => $data->lastFailedBuild->number,
            'job.builds.last_stable'       => $data->lastStableBuild->number,
            'job.builds.last_successful'   => $data->lastSuccessfulBuild->number,
            'job.builds.last_unstable'     => $data->lastUnstableBuild->number,
            'job.builds.last_unsuccessful' => $data->lastUnsuccessfulBuild->number,
            'job.builds.next'              => $data->nextBuildNumber,
        );
    }

    /**
     * Gets the JSON output from the API uri and converts it to a pure stdClass
     * object.
     *
     * The uri looks like the following scheme:
     *
     *     http://localhost:8080/job/<ProjectName>/api/json
     *
     * @return stdClass
     */
    private function fetchData()
    {
        $data = json_decode(file_get_contents($this->path));

        if (null === $data) {
            throw new \RuntimeException(sprintf('Unable to fetch job report from "%s". Ensure the path is valid or the server is running.', $this->path));
        }

        return $data;
    }
}