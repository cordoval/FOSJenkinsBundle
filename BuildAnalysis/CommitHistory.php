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
 * The CommitHistory class represents the commit history in the SCM tool.
 *
 * @author Hugo Hamon <hugo.hamon@sensio.com>
 */
class CommitHistory
{
    const COMMIT_TITLE_PATTERN = '/^\#(\d+) (.*) (\(.*\))$/i';

    /**
     * The SimpleXmlElement parser.
     *
     * @var \SimpleXmlElement
     */
    private $xml;

    /**
     * The commit history represented by an array of Commit objects.
     *
     * @var array
     */
    private $commits;

    /**
     * Anonymous function to use in array_map() function when parsing the XML
     * feed.
     *
     * @var \Closure
     */
    private $filesFilterCallback;

    public function __construct(\SimpleXmlElement $xml)
    {
        $this->xml     = $xml;
        $this->commits = array();
        $this->filesFilterCallback = function($item) {
            return trim($item);
        };

        $this->buildCommitHistory();
    }

    /**
     * Builds the commit history based on the RSS files entries.
     *
     */
    private function buildCommitHistory()
    {
        foreach ($this->xml->entry as $entry) {

            $infos = $this->extractCommitInfos((string) $entry->title);
            $files = $this->extractCommitFiles((string) $entry->content);

            $commit = new Commit($infos['build'], $infos['author'], $infos['message'], (string) $entry->published, $files);
            $this->commits[] = $commit;
        }
    }

    /**
     * Extracts committed files.
     *
     * @param string $content The contents of the <content> tag
     * @return array A list of committed files paths
     */
    private function extractCommitFiles($content)
    {
        $files = explode("\n", trim($content));

        return array_map($this->filesFilterCallback, $files);
    }

    /**
     * Extracts commit information from the commit title. This method extracts
     * the build number, the commit message and its author.
     *
     * @param string $title The title string from which to extract information
     * @return array $infos Commit informations as an associative array
     */
    private function extractCommitInfos($title)
    {
        $infos = array(
            'build' => '',
            'author' => '',
            'message' => ''
        );

        preg_match_all(self::COMMIT_TITLE_PATTERN, $title, $matches);

        if (!empty($matches[1][0])) {
            $infos['build'] = $matches[1][0];
        }

        if (!empty($matches[3][0])) {
            $infos['author'] = trim($matches[3][0], '()');
        }

        if (!empty($matches[2][0])) {
            $infos['message'] = $matches[2][0];
        }

        return $infos;
    }

    /**
     * Returns the commits history.
     *
     * @return array
     */
    public function getCommits()
    {
        return $this->commits;
    }
}