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
 * The Commit class represents a single commit in the SCM tool.
 *
 * @author Hugo Hamon <hugo.hamon@sensio.com>
 */
class Commit
{
    /**
     * The build number.
     *
     * @var integer
     */
    private $build;

    /**
     * The commit author (the committer).
     *
     * @var string
     */
    private $author;

    /**
     * The commit date.
     *
     * @var string
     */
    private $date;

    /**
     * The commit message.
     *
     * @var string
     */
    private $message;

    /**
     * The committed files.
     *
     * @var array
     */
    private $files;

    public function __construct($build, $author, $message, $date, array $files)
    {
        $this->build   = (integer) $build;
        $this->author  = $author;
        $this->message = $message;
        $this->date    = $date;
        $this->files   = $files;
    }

    public function getBuild()
    {
        return $this->build;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getFiles()
    {
        return $this->files;
    }
}