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
 * The ReportParserInterface defines how report parsers must behave.
 *
 * @author Hugo Hamon <hugo.hamon@sensio.com>
 */
interface ReportParserInterface
{
    /**
     * Sets the path to the string to parse.
     *
     * @param string $path The path to the string to parse.
     */
    public function setPath($path);

    /**
     * Returns the path to the string to parse.
     *
     * @return string
     */
    public function getPath();

    /**
     * Parses the report string coming from the path.
     *
     */
    public function parse();

    /**
     * Returns the parsed data.
     *
     * @return array An associative array of data.
     */
    public function getData();
}