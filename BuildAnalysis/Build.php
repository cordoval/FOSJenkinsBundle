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
 * The Build class represents a single build in Jenkins.
 *
 * @author Hugo Hamon <hugo.hamon@sensio.com>
 */
class Build
{
    /**
     * The build number.
     *
     * @var integer
     */
    private $number;

    /**
     * The build status.
     *
     * @var string
     */
    private $status;

    /**
     * The build uri.
     *
     * @var string
     */
    private $uri;

    /**
     * The build timestamp.
     *
     * @var string
     */
    private $date;

    /**
     * Whether or not the build is successfull.
     *
     * @var Boolean
     */
    private $isSucceeded;

    public function __construct($number, $status, $uri, $date, $isSucceeded)
    {
        $this->number      = (integer) $number;
        $this->status      = $status;
        $this->uri         = $uri;
        $this->date        = $date;
        $this->isSucceeded = (Boolean) $isSucceeded;
    }

    public function getNumber()
    {
        return $this->number;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function isSucceeded()
    {
        return $this->isSucceeded;
    }
}