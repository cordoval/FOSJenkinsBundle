<?php

/*
 * This file is part of the FOSJenkins package.
 *
 * (c) Hugo Hamon <hugo.hamon@sensio.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FOS\Bundle\JenkinsBundle\Parser;

/**
 * The JenkinsReportParser stores common methods for its concrete inherited
 * classes.
 *
 * @author Hugo Hamon <hugo.hamon@sensio.com>
 */
abstract class JenkinsReportParser implements JenkinsReportParserInterface
{
    /**
     * An uri to the data to parse.
     *
     * @var string
     */
    protected $path;

    /**
     * An associative array of parsed data.
     *
     * @var array
     */
    protected $data;

    /**
     * Sets the path to the string to parse.
     *
     * @param string $path The path to the string to parse.
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * Returns the path to the string to parse.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Returns the parsed data.
     *
     * @return array An associative array of data.
     */
    public function getData()
    {
        return $this->data;
    }
}