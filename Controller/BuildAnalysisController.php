<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Hugo Hamon <hugo.hamon@sensio.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FOS\Bundle\JenkinsBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;

use FOS\Bundle\JenkinsBundle\DataCollector\JenkinsDataCollector;
use FOS\Bundle\JenkinsBundle\BuildAnalysis\BuildHistory;
use FOS\Bundle\JenkinsBundle\BuildAnalysis\CommitHistory;

class BuildAnalysisController extends ContainerAware
{
    public function commitHistoryAction(JenkinsDataCollector $collector)
    {
        $templating = $this->container->get('templating');
        $logs = $this->container->getParameter('jenkins.builds.commits.rss_uri');

        $history = new CommitHistory(new \SimpleXmlElement($logs, 0, true));

        return $templating->renderResponse(
            'FOSJenkinsBundle:Panel:commitHistory.html.twig', 
            array('commits'  => $history->getCommits())
        );
    }

    public function buildHistoryAction(JenkinsDataCollector $collector)
    {
        $templating = $this->container->get('templating');
        $logs = $this->container->getParameter('jenkins.builds.builds.rss_uri');

        $history = new BuildHistory(new \SimpleXmlElement($logs, 0, true));

        return $templating->renderResponse(
            'FOSJenkinsBundle:Panel:buildHistory.html.twig', 
            array('builds'  => $history->getBuilds())
        );
    }
}