README
======

What is FOSJenkinsBundle?
-------------------------

The `FOSJenkinsBundle` is a Symfony2 bundle designed to collect information on a
Jenkins continuous integration server and show them in a prettier shape in the 
web debug toolbar and webprofiler tools.

This bundle shows the state of the last build in the web debug toolbar and
displays the ten last builds information in a panel of the web profiler
application.

The `FOSJenkinsBundle` is covered by a `PHPUnit` unit tests suite located under
the `Tests/` folder.

Requirements
------------

The `FOSJenkinsBundle` is only supported on PHP 5.3.2 and up. It works with a
Jenkins continuous integration server running.

Jenkins authentification
------------------------

If your Jenkins requires authentification, you will have to use the following 
format for the `endpoint` parameter:

    http://<login>:<password>@localhost:8080/job/Syndication/rssAll

Installation
------------

The `FOSJenkinsBundle` is easy to install. It doesn't require any particular
skill to make it work. You just need to follow the steps below to make it run.

First, create a new `FOS/Bundle` directory under the `vendor/bundles/` folder of 
your Symfony2 project.

    $ mkdir -p vendor/bundles/FOS/Bundle

Install the `FOSJenkinsBundle` under this `FOS` directory.

    $ git clone https://github.com/hhamon/FOSJenkinsBundle.git vendor/bundles/FOS/Bundle/JenkinsBundle

Register the `FOS` namespace prefix in the `app/autoload.php` file of your
application to make Symfony2 able to load the bundle.

    <?php

    # app/autoload.php
    use Symfony\Component\ClassLoader\UniversalClassLoader;

    $loader = new UniversalClassLoader();
    $loader->registerNamespaces(array(
        // ...
        'FOS' => __DIR__.'/../vendor/bundles',
    ));

Then, load the `FOSJenkinsBundle` bundle for your development and testing
environments only in your `AppKernel` class.

    # app/AppKernel.php
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            // ...
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Symfony\Bundle\WebConfiguratorBundle\SymfonyWebConfiguratorBundle();
            $bundles[] = new FOS\Bundle\JenkinsBundle\FOSJenkinsBundle();
        }

        return $bundles;
    }

Finally, configure the `FOSJenkinsBundle` bundle directly from the 
`config_dev.yml` and `config_test.yml` files located under the `app/config/`
directory of your Symfony2 installation.

    # app/config/config_dev.yml
    fos_jenkins:
        endpoint: http://localhost:8080/job/Syndication/rssAll

    --

    # app/config/config_test.yml
    fos_jenkins:
        endpoint: http://localhost:8080/job/Syndication/rssAll

Don't forget to publish assets:

    $ php app/console assets:install --symlink web
